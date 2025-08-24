<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    /**
     * Display a listing of vehicles based on filter
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        $vehicles = match($filter) {
            'all' => Vehicle::latest()->get(),
            'active' => Vehicle::active()->latest()->get(),
            'inactive' => Vehicle::inactive()->latest()->get(),
            'running' => Vehicle::running()->latest()->get(),
            'waiting' => Vehicle::waiting()->latest()->get(),
            'expired' => Vehicle::expired()->latest()->get(),
            'paused' => Vehicle::paused()->latest()->get(),
            'route' => Vehicle::route()->latest()->get(),
            'group' => Vehicle::group()->latest()->get(),
            default => Vehicle::latest()->get()
        };

        $pageTitle = match($filter) {
            'all' => 'Tất cả xe',
            'active' => 'Xe ngoài bãi',
            'inactive' => 'Xe trong xưởng',
            'running' => 'Xe đang chạy',
            'waiting' => 'Xe đang chờ',
            'expired' => 'Xe hết giờ',
            'paused' => 'Xe tạm dừng',
            'route' => 'Xe cung đường',
            'group' => 'Xe khách đoàn',
            default => 'Tất cả xe'
        };

        // Get display mode based on filter
        $displayMode = in_array($filter, ['route', 'group']) ? 'list' : 'grid';

        return view('vehicles.index', compact('vehicles', 'filter', 'pageTitle', 'displayMode'));
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
            'color' => 'required|string|max:50',
            'seats' => 'required|in:1,2',
            'power' => 'required|string|max:50',
            'wheel_size' => 'required|string|max:50',
            'driver_name' => 'nullable|string|max:100',
            'driver_phone' => 'nullable|string|max:20',
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
            'driver_name' => $request->driver_name,
            'driver_phone' => $request->driver_phone,
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
        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle (Admin only)
     */
    public function edit(Vehicle $vehicle)
    {
        if (!auth()->user()->canManageVehicles()) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        $colors = VehicleAttribute::getColors();
        $seats = VehicleAttribute::getSeats();
        $powerOptions = VehicleAttribute::getPowerOptions();
        $wheelSizes = VehicleAttribute::getWheelSizes();

        return view('vehicles.edit', compact('vehicle', 'colors', 'seats', 'powerOptions', 'wheelSizes'));
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
            'color' => 'required|string|max:50',
            'seats' => 'required|in:1,2',
            'power' => 'required|string|max:50',
            'wheel_size' => 'required|string|max:50',
            'driver_name' => 'nullable|string|max:100',
            'driver_phone' => 'nullable|string|max:20',
            'current_location' => 'nullable|string|max:200',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $vehicle->update([
            'name' => $request->name,
            'color' => $request->color,
            'seats' => $request->seats,
            'power' => $request->power,
            'wheel_size' => $request->wheel_size,
            'driver_name' => $request->driver_name,
            'driver_phone' => $request->driver_phone,
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $vehicle->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'status_changed_at' => now(),
        ]);

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
     * Get vehicles for API
     */
    public function getVehicles(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        $vehicles = match($filter) {
            'all' => Vehicle::latest()->get(),
            'active' => Vehicle::active()->latest()->get(),
            'inactive' => Vehicle::inactive()->latest()->get(),
            'running' => Vehicle::running()->latest()->get(),
            'waiting' => Vehicle::waiting()->latest()->get(),
            'expired' => Vehicle::expired()->latest()->get(),
            'paused' => Vehicle::paused()->latest()->get(),
            'route' => Vehicle::route()->latest()->get(),
            'group' => Vehicle::group()->latest()->get(),
            default => Vehicle::latest()->get()
        };

        return response()->json([
            'success' => true,
            'vehicles' => $vehicles
        ]);
    }

    /**
     * Show vehicle attributes management (Admin only)
     */
    public function attributes()
    {
        if (!auth()->user()->canManageVehicleAttributes()) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        $colors = VehicleAttribute::where('type', 'color')->orderBy('sort_order')->get();
        $seats = VehicleAttribute::where('type', 'seats')->orderBy('sort_order')->get();
        $powerOptions = VehicleAttribute::where('type', 'power')->orderBy('sort_order')->get();
        $wheelSizes = VehicleAttribute::where('type', 'wheel_size')->orderBy('sort_order')->get();

        return view('vehicles.attributes', compact('colors', 'seats', 'powerOptions', 'wheelSizes'));
    }
}
