<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleAttribute;
use Illuminate\Http\Request;
use App\Http\Controllers\vehicles\VehicleOperationsController;

class ActiveVehiclesController extends Controller
{
    /**
     * Display active vehicles (xe hoạt động)
     */
    public function index()
    {
        // Lấy xe có trạng thái active (sẵn sàng chạy)
        $vehicles = Vehicle::active()->latest()->get();
        
        // Lấy xe đang chạy để hiển thị trong bảng "Xe chạy đường 1-2"
        $runningVehicles = Vehicle::running()->latest()->get();

        return view('vehicles.active_vehicles', compact('vehicles', 'runningVehicles'));
    }

    /**
     * Get active vehicles for API
     */
    public function getActiveVehicles()
    {
        $vehicles = Vehicle::active()->latest()->get();

        return response()->json([
            'success' => true,
            'vehicles' => $vehicles
        ]);
    }

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
     * Move vehicle to workshop
     */
    public function moveToWorkshop(Request $request)
    {
        $operationsController = new VehicleOperationsController();
        return $operationsController->moveToWorkshop($request);
    }
}
