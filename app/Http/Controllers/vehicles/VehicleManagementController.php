<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleManagementController extends Controller
{
    /**
     * Display a listing of vehicles based on filter
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $perPage = $request->get('per_page', 10);
        
        $vehicles = match($filter) {
            'vehicles_list' => Vehicle::latest()->paginate($perPage),
            'active' => Vehicle::notInactive()->latest()->paginate($perPage),
            'inactive' => Vehicle::inactive()->latest()->paginate($perPage),
            'running' => Vehicle::running()->latest()->paginate($perPage),
            'waiting' => Vehicle::waiting()->latest()->paginate($perPage),
            'expired' => Vehicle::expired()->paginate($perPage),
            'paused' => Vehicle::paused()->latest()->paginate($perPage),
            'route' => Vehicle::route()->latest()->paginate($perPage),
            'attributes' => Vehicle::latest()->paginate($perPage),
            default => Vehicle::latest()->paginate($perPage)
        };

        $pageTitle = match($filter) {
            'vehicles_list' => 'Danh sách xe',
            'active' => 'Xe ngoài bãi',
            'inactive' => 'Xe trong xưởng',
            'running' => 'Xe đang chạy',
            'waiting' => 'Xe đang chờ',
            'expired' => 'Xe hết giờ',
            'paused' => 'Xe tạm dừng',
            'attributes' => 'Thuộc tính xe',
            default => 'Danh sách xe'
        };

        // Get display mode based on filter
        $displayMode = in_array($filter, ['active', 'vehicles_list', 'attributes']) ? 'list' : 'grid';

        // Get vehicle attributes for modal
        $colors = VehicleAttribute::getColors();
        $seats = VehicleAttribute::getSeats();
        $powerOptions = VehicleAttribute::getPowerOptions();
        $wheelSizes = VehicleAttribute::getWheelSizes();

        return view('vehicles_management', compact(
            'vehicles', 
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
            'status' => Vehicle::STATUS_ACTIVE,
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
        return view('vehicles_management', compact('vehicle'));
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
     * Update vehicle status
     */
    public function updateStatus(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:active,inactive,running,waiting,expired,paused,route,group',
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
        
        // Handle active status - reset all timing data
        if ($request->status === 'active') {
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
     */
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
            'status' => Vehicle::STATUS_INACTIVE,
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
}
