<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleAttribute;
use Illuminate\Http\Request;
use App\Http\Controllers\vehicles\VehicleOperationsController;

class ReadyVehiclesController extends Controller
{
    /**
     * Display ready vehicles (xe sẵn sàng chạy)
     */
    public function index()
    {
        // Lấy xe có trạng thái ready (sẵn sàng chạy)
        $vehicles = Vehicle::active()->latest()->get();

        return view('vehicles.ready_vehicles', compact('vehicles'));
    }

    /**
     * Get ready vehicles for API
     */
    public function getReadyVehicles()
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
