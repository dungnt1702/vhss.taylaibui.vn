<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceType;
use App\Models\Vehicle;
use App\Models\MaintenanceRecord;
use Carbon\Carbon;

class MaintenanceScheduleController extends Controller
{
    /**
     * Display a listing of maintenance schedules
     */
    public function index(Request $request)
    {
        $query = MaintenanceSchedule::with(['vehicle', 'maintenanceType', 'latestRecord']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by vehicle
        if ($request->has('vehicle_id') && $request->vehicle_id !== '') {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->where('scheduled_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('scheduled_date', '<=', $request->date_to);
        }

        // Default: show upcoming and overdue
        if (!$request->has('status')) {
            $query->whereIn('status', ['pending', 'overdue', 'in_progress']);
        }

        $schedules = $query->orderBy('scheduled_date', 'asc')->paginate(20);

        // Get filter options
        $vehicles = Vehicle::where('status', '!=', 'deleted')->get();
        $maintenanceTypes = MaintenanceType::active()->get();

        return view('maintenance.schedules.index', compact('schedules', 'vehicles', 'maintenanceTypes'));
    }

    /**
     * Show calendar view
     */
    public function calendar(Request $request)
    {
        $date = $request->get('date', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::parse($date)->startOfMonth();
        $endOfMonth = Carbon::parse($date)->endOfMonth();

        $schedules = MaintenanceSchedule::with(['vehicle', 'maintenanceType'])
            ->whereBetween('scheduled_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(function ($schedule) {
                return Carbon::parse($schedule->scheduled_date)->format('Y-m-d');
            });

        return view('maintenance.schedules.calendar', compact('schedules', 'date'));
    }

    /**
     * Show the form for creating a new maintenance schedule
     */
    public function create()
    {
        $vehicles = Vehicle::where('status', '!=', 'deleted')->get();
        $maintenanceTypes = MaintenanceType::active()->get();

        return view('maintenance.schedules.create', compact('vehicles', 'maintenanceTypes'));
    }

    /**
     * Store a newly created maintenance schedule
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'maintenance_type_id' => 'required|exists:maintenance_types,id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000'
        ]);

        $maintenanceType = MaintenanceType::find($request->maintenance_type_id);
        $nextDue = Carbon::parse($request->scheduled_date)->addDays($maintenanceType->interval_days);

        MaintenanceSchedule::create([
            'vehicle_id' => $request->vehicle_id,
            'maintenance_type_id' => $request->maintenance_type_id,
            'scheduled_date' => $request->scheduled_date,
            'next_due' => $nextDue,
            'notes' => $request->notes
        ]);

        return redirect()->route('maintenance.schedules.index')
            ->with('success', 'Lịch bảo trì đã được tạo thành công!');
    }

    /**
     * Display the specified maintenance schedule
     */
    public function show(MaintenanceSchedule $schedule)
    {
        $schedule->load(['vehicle', 'maintenanceType', 'records.performedBy']);
        
        return view('maintenance.schedules.show', compact('schedule'));
    }

    /**
     * Show the form for performing maintenance
     */
    public function perform(MaintenanceSchedule $schedule)
    {
        $schedule->load(['vehicle', 'maintenanceType.tasks']);
        
        return view('maintenance.schedules.perform', compact('schedule'));
    }

    /**
     * Store maintenance record
     */
    public function storeRecord(Request $request, MaintenanceSchedule $schedule)
    {
        $request->validate([
            'performed_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:1000',
            'task_results' => 'required|array',
            'task_results.*.task_id' => 'required|exists:maintenance_tasks,id',
            'task_results.*.completed' => 'required|boolean',
            'task_results.*.notes' => 'nullable|string|max:500'
        ]);

        $record = MaintenanceRecord::create([
            'maintenance_schedule_id' => $schedule->id,
            'performed_by' => auth()->id(),
            'performed_date' => $request->performed_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'notes' => $request->notes,
            'task_results' => $request->task_results,
            'status' => 'completed'
        ]);

        // Update schedule status
        $schedule->update([
            'status' => 'completed',
            'last_performed' => $request->performed_date
        ]);

        // Calculate next due date
        $schedule->calculateNextDue();

        return redirect()->route('maintenance.schedules.show', $schedule)
            ->with('success', 'Bảo trì đã được thực hiện thành công!');
    }

    /**
     * Mark schedule as overdue
     */
    public function markOverdue()
    {
        $overdueSchedules = MaintenanceSchedule::pending()
            ->where('scheduled_date', '<', Carbon::today())
            ->get();

        foreach ($overdueSchedules as $schedule) {
            $schedule->markAsOverdue();
        }

        return response()->json([
            'success' => true,
            'message' => "Đã cập nhật {$overdueSchedules->count()} lịch bảo trì quá hạn"
        ]);
    }

    /**
     * Get dashboard data
     */
    public function dashboard()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->endOfWeek();

        $stats = [
            'total_schedules' => MaintenanceSchedule::count(),
            'pending_today' => MaintenanceSchedule::dueToday()->pending()->count(),
            'overdue' => MaintenanceSchedule::overdue()->count(),
            'due_this_week' => MaintenanceSchedule::dueThisWeek()->pending()->count(),
            'completed_this_month' => MaintenanceSchedule::where('status', 'completed')
                ->whereMonth('last_performed', $today->month)
                ->whereYear('last_performed', $today->year)
                ->count()
        ];

        $upcomingSchedules = MaintenanceSchedule::with(['vehicle', 'maintenanceType'])
            ->whereIn('status', ['pending', 'overdue'])
            ->orderBy('scheduled_date', 'asc')
            ->limit(10)
            ->get();

        $overdueSchedules = MaintenanceSchedule::with(['vehicle', 'maintenanceType'])
            ->overdue()
            ->orderBy('scheduled_date', 'asc')
            ->limit(10)
            ->get();

        return view('maintenance.dashboard', compact('stats', 'upcomingSchedules', 'overdueSchedules'));
    }
}