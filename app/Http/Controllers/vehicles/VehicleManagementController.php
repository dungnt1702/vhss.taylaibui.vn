<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleAttribute;
use App\Models\VehicleTechnicalIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\vehicles\VehicleOperationsController;

class VehicleManagementController extends Controller
{
    /**
     * Display a listing of vehicles based on filter
     */
    public function index(Request $request)
    {
        // Support both RESTful routes (/vehicles/ready) and query parameters (?filter=ready)
        $filter = $request->get('filter', 'all');
        
        // If no filter in query, try to get from route segment
        if ($filter === 'all' && $request->route()) {
            $routeName = $request->route()->getName();
            if ($routeName) {
                $filter = match($routeName) {
                    'vehicles.active', 'vehicles.active.page' => 'active',
                    'vehicles.ready', 'vehicles.ready.page' => 'ready',
                    'vehicles.running', 'vehicles.running.page' => 'running',
                    'vehicles.paused', 'vehicles.paused.page' => 'paused',
                    'vehicles.expired', 'vehicles.expired.page' => 'expired',
                    'vehicles.workshop', 'vehicles.workshop.page' => 'workshop',
                    'vehicles.repairing', 'vehicles.repairing.page', 'vehicles.repairing.vehicle' => 'repairing',
                    'vehicles.attributes', 'vehicles.attributes.page' => 'attributes',
                    'vehicles.list', 'vehicles.list.page' => 'vehicles_list',
                    default => 'all'
                };
            }
        }
        
        $perPage = $request->get('per_page', 10);
        
        // Handle page parameter from route
        $page = $request->get('page', 1);
        if ($request->route('page')) {
            $page = $request->route('page');
        }

        // Redirect legacy maintaining filter to the new Maintenance module
        if ($filter === 'maintaining') {
            return redirect()->route('maintenance.schedules.index');
        }
        
        $vehicles = match($filter) {
            'vehicles_list' => Vehicle::latest()->paginate($perPage, ['*'], 'page', $page)->withPath(route('vehicles.list'))->appends($request->query()),
            'active' => Vehicle::active()->latest()->paginate($perPage, ['*'], 'page', $page)->withPath(route('vehicles.active'))->appends($request->query()),
            'ready' => Vehicle::ready()->latest()->paginate($perPage, ['*'], 'page', $page)->withPath(route('vehicles.ready'))->appends($request->query()),
            'workshop' => Vehicle::inactive()->latest()->paginate($perPage, ['*'], 'page', $page)->withPath(route('vehicles.workshop'))->appends($request->query()),
            'running' => Vehicle::running()->latest()->paginate($perPage, ['*'], 'page', $page)->withPath(route('vehicles.running'))->appends($request->query()),
            'expired' => Vehicle::expired()->paginate($perPage, ['*'], 'page', $page)->withPath(route('vehicles.expired'))->appends($request->query()),
            'paused' => Vehicle::paused()->latest()->paginate($perPage, ['*'], 'page', $page)->withPath(route('vehicles.paused'))->appends($request->query()),
            'route' => Vehicle::route()->latest()->paginate($perPage, ['*'], 'page', $page)->withPath(route('vehicles.index'))->appends($request->query()),
            'repairing' => Vehicle::where('status', 'repairing')->latest()->paginate($perPage, ['*'], 'page', $page)->withPath(route('vehicles.repairing'))->appends($request->query()),
            // 'maintaining' legacy handled above by redirect
            'attributes' => Vehicle::latest()->paginate($perPage, ['*'], 'page', $page)->withPath(route('vehicles.attributes'))->appends($request->query()),
            default => Vehicle::latest()->paginate($perPage, ['*'], 'page', $page)->withPath(route('vehicles.index'))->appends($request->query())
        };

        // Add repair count for workshop vehicles
        if ($filter === 'workshop') {
            $vehicles->getCollection()->each(function ($vehicle) {
                $vehicle->repair_count = VehicleTechnicalIssue::where('vehicle_id', $vehicle->id)
                    ->where('issue_type', 'repair')
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->count();
            });
        }

        // Get technical issues for repairing and maintaining views
        $repairIssues = null;
        $maintenanceIssues = null;
        
            if ($filter === 'repairing') {
                $query = \App\Models\VehicleTechnicalIssue::where('issue_type', 'repair')
                    ->with(['vehicle', 'reporter', 'assignee']);
                
                // Filter by vehicle_id if provided (from query parameter or route parameter)
                $vehicleId = $request->get('vehicle_id') ?? $request->route('vehicle_id');
                if ($vehicleId) {
                    $query->where('vehicle_id', $vehicleId);
                }
                
                // Sort: pending/in_progress first, then by reported_at desc
                $repairIssues = $query->get()->sortBy(function ($issue) {
                    $priority = match($issue->status) {
                        'pending' => 1,
                        'in_progress' => 2,
                        'completed' => 3,
                        'cancelled' => 4,
                        default => 5
                    };
                    return [$priority, $issue->reported_at->timestamp];
                })->values();
            }

        $pageTitle = match($filter) {
            'vehicles_list' => 'Danh sách xe',
            'active' => 'Xe hoạt động',
            'ready' => 'Xe sẵn sàng',
            'workshop' => 'Xe trong xưởng',
            'running' => 'Xe đang chạy',
            'expired' => 'Xe hết giờ',
            'paused' => 'Xe tạm dừng',
            'repairing' => 'Xe đang sửa chữa',
            // 'maintaining' legacy handled by redirect
            'attributes' => 'Thuộc tính xe',
            default => 'Danh sách xe'
        };

        // Get display mode based on filter
        $displayMode = in_array($filter, ['active', 'ready', 'vehicles_list', 'attributes']) ? 'list' : 'grid';

        // Initialize all vehicle arrays
        $activeVehicles = collect();
        $runningVehicles = collect();
        $pausedVehicles = collect();
        $expiredVehicles = collect();
        $routingVehicles = collect();
        
        // Get active vehicles for active_vehicles.blade.php when filter = 'active'
        if ($filter === 'active') {
            $activeVehicles = Vehicle::active()->orderBy('name')->get();
            $runningVehicles = Vehicle::running()->latest()->get();
            $pausedVehicles = Vehicle::paused()->latest()->get();
            $expiredVehicles = Vehicle::expired()->latest()->get();
            $routingVehicles = Vehicle::where('status', 'routing')
                ->orderBy('start_time', 'asc')
                ->get();
        } else {
            // Always initialize these variables to avoid undefined variable errors
            $activeVehicles = collect();
            $runningVehicles = collect();
            $pausedVehicles = collect();
            $expiredVehicles = collect();
            $routingVehicles = collect();
        }
        
        // Get ready vehicles for ready_vehicles.blade.php when filter = 'ready'
        if ($filter === 'ready') {
            $activeVehicles = Vehicle::ready()->latest()->get();
            $runningVehicles = Vehicle::running()->latest()->get();
        }

        // Get vehicle attributes for modal
        $colors = VehicleAttribute::getColors();
        $seats = VehicleAttribute::getSeats();
        $powerOptions = VehicleAttribute::getPowerOptions();
        $wheelSizes = VehicleAttribute::getWheelSizes();

        return view('vehicles.vehicles_management', compact(
            'vehicles', 
            'activeVehicles',
            'runningVehicles',
            'pausedVehicles',
            'expiredVehicles',
            'routingVehicles',
            'repairIssues',
            'maintenanceIssues',
            'filter', 
            'pageTitle', 
            'displayMode', 
            'colors', 
            'seats', 
            'powerOptions', 
            'wheelSizes'
        ));
    }

    /**
     * Show the form for creating a new vehicle (Admin only)
     */
    public function create()
    {
        if (!auth()->user()->canManageVehicles()) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        $colors = VehicleAttribute::getColors();
        $seats = VehicleAttribute::getSeats();
        $powerOptions = VehicleAttribute::getPowerOptions();
        $wheelSizes = VehicleAttribute::getWheelSizes();

        return view('vehicles.create', compact('colors', 'seats', 'powerOptions', 'wheelSizes'));
    }

    /**
     * Store a newly created vehicle (Admin only)
     */
    public function store(Request $request)
    {
        if (!auth()->user()->canManageVehicles()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:vehicles,name|max:50',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/|max:7',
            'seats' => 'required|in:1,2,"1","2"',  // Chấp nhận cả integer và string
            'power' => 'required|string|max:50',
            'wheel_size' => 'required|string|max:50',
            'current_location' => 'nullable|string|max:200',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $vehicle = Vehicle::create([
            'name' => $request->name,
            'color' => $request->color,
            'seats' => $request->seats,
            'power' => $request->power,
            'wheel_size' => $request->wheel_size,
            'status' => Vehicle::STATUS_READY,
            'current_location' => $request->current_location,
            'notes' => $request->notes,
            'status_changed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Xe Gokart đã được thêm thành công!',
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Display the specified vehicle
     */
    public function show(Vehicle $vehicle)
    {
        return view('vehicles.vehicles_management', compact('vehicle'));
    }

    /**
     * Get vehicle data for editing (API)
     */
    public function edit(Vehicle $vehicle)
    {
        if (!auth()->user()->canManageVehicles()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Update the specified vehicle (Admin only)
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        if (!auth()->user()->canManageVehicles()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:vehicles,name,' . $vehicle->id,
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/|max:7',
            'seats' => 'required|in:1,2,"1","2"',  // Chấp nhận cả integer và string
            'power' => 'required|string|max:50',
            'wheel_size' => 'required|string|max:50',
            'current_location' => 'nullable|string|max:200',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            // Log validation errors for debugging
            \Log::error('Vehicle update validation failed', [
                'vehicle_id' => $vehicle->id,
                'request_data' => $request->all(),
                'validation_errors' => $validator->errors()->toArray()
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'debug_info' => [
                    'request_data' => $request->all(),
                    'validation_rules' => [
                        'name' => 'required|string|max:50|unique:vehicles,name,' . $vehicle->id,
                        'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/|max:7',
                        'seats' => 'required|in:1,2,"1","2"',
                        'power' => 'required|string|max:50',
                        'wheel_size' => 'required|string|max:50',
                        'current_location' => 'nullable|string|max:200',
                        'notes' => 'nullable|string|max:500',
                    ]
                ]
            ], 422);
        }

        $vehicle->update([
            'name' => $request->name,
            'color' => $request->color,
            'seats' => $request->seats,
            'power' => $request->power,
            'wheel_size' => $request->wheel_size,
            'current_location' => $request->current_location,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thông tin xe Gokart đã được cập nhật thành công!',
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Update vehicle notes only
     */
    public function updateNotes(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $vehicle->update([
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ghi chú đã được cập nhật thành công.',
            'vehicle' => $vehicle->fresh()
        ]);
    }

    /**
     * Update vehicle status
     */
    public function updateStatus(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:active,inactive,running,ready,expired,paused,route,group',
            'notes' => 'nullable|string|max:500',
            'end_time' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = [
            'status' => $request->status,
            'notes' => $request->notes,
            'status_changed_at' => now(),
        ];

        // Handle running status - set start_time and end_time
        if ($request->status === 'running') {
            if ($request->has('start_time') && $request->start_time) {
                // Convert milliseconds timestamp to datetime
                $startTime = $request->start_time / 1000;
                $updateData['start_time'] = date('Y-m-d H:i:s', $startTime);
            } else {
                $updateData['start_time'] = now();
            }
            
            if ($request->has('end_time') && $request->end_time) {
                // Convert milliseconds timestamp to datetime
                $endTime = $request->end_time / 1000;
                $updateData['end_time'] = date('Y-m-d H:i:s', $endTime);
            }
            
            // Clear paused data when starting
            $updateData['paused_at'] = null;
            $updateData['paused_remaining_seconds'] = null;
        }
        
        // Handle paused status - store pause time and remaining seconds
        if ($request->status === 'paused') {
            $updateData['paused_at'] = now();
            // Calculate remaining seconds from end_time
            if ($vehicle->end_time) {
                $remainingSeconds = max(0, strtotime($vehicle->end_time) - time());
                $updateData['paused_remaining_seconds'] = $remainingSeconds;
            }
        }
        
        // Handle ready status - reset all timing data
        if ($request->status === 'ready') {
            $updateData['start_time'] = null;
            $updateData['end_time'] = null;
            $updateData['paused_at'] = null;
            $updateData['paused_remaining_seconds'] = null;
        }

        $vehicle->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái xe đã được cập nhật thành công!',
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Update vehicle route
     */
    public function updateRoute(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'route_number' => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $vehicle->update([
            'route_number' => $request->route_number,
            'status' => Vehicle::STATUS_ROUTE,
            'status_changed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cung đường xe đã được cập nhật thành công!',
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Remove the specified vehicle (Admin only)
     */
    public function destroy(Vehicle $vehicle)
    {
        if (!auth()->user()->canManageVehicles()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này.'
            ], 403);
        }

        $vehicle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xe đã được xóa thành công!'
        ]);
    }

    /**
     * Move vehicle to workshop
    
    public function moveToWorkshop(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $vehicle->update([
            'status' => Vehicle::STATUS_WORKSHOP,
            'status_changed_at' => now(),
            'notes' => 'Chuyển về xưởng: ' . $request->reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Xe đã được chuyển về xưởng thành công!',
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Get vehicle data by ID for modal (API endpoint)
     */
    public function getVehicleData($id)
    {
        try {
            $vehicle = Vehicle::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $vehicle->id,
                    'name' => $vehicle->name,
                    'color' => $vehicle->color,
                    'seats' => $vehicle->seats,
                    'power' => $vehicle->power,
                    'wheel_size' => $vehicle->wheel_size,
                    'current_location' => $vehicle->current_location,
                    'notes' => $vehicle->notes,
                    'status' => $vehicle->status,
                    'status_display_name' => $vehicle->status_display_name,
                    'status_color_class' => $vehicle->status_color_class,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy thông tin xe: ' . $e->getMessage()
            ], 404);
        }
    }

    // ===== DELEGATE OPERATIONS TO VehicleOperationsController =====
    
    /**
     * Start timer for selected vehicles
     */
    public function startTimer(Request $request)
    {
        $operationsController = new VehicleOperationsController();
        return $operationsController->startTimer($request);
    }

    /**
     * Assign route to selected vehicles
     */
    public function assignRoute(Request $request)
    {
        $operationsController = new VehicleOperationsController();
        return $operationsController->assignRoute($request);
    }

    /**
     * Return vehicles to yard
     */
    public function returnToYard(Request $request)
    {
        $operationsController = new VehicleOperationsController();
        return $operationsController->returnToYard($request);
    }

    /**
     * Pause vehicle
     */
    public function pause(Request $request, Vehicle $vehicle)
    {
        $operationsController = new VehicleOperationsController();
        return $operationsController->pause($request, $vehicle);
    }

    /**
     * Resume vehicle
     */
    public function resume(Request $request, Vehicle $vehicle)
    {
        $operationsController = new VehicleOperationsController();
        return $operationsController->resume($request, $vehicle);
    }

    /**
     * Move vehicle to workshop
     */
    public function moveToWorkshop(Request $request, Vehicle $vehicle)
    {
        $operationsController = new VehicleOperationsController();
        return $operationsController->moveToWorkshop($request);
    }

    /**
     * Get vehicles by status for API
     */
    public function getVehiclesByStatus(Request $request)
    {
        $operationsController = new VehicleOperationsController();
        return $operationsController->getVehiclesByStatus($request);
    }
}
