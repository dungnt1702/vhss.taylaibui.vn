<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

abstract class VehicleBaseController extends Controller
{
    protected $vehicleOperationsController;

    public function __construct()
    {
        $this->vehicleOperationsController = new VehicleOperationsController();
    }

    /**
     * Get vehicles by status for API
     */
    public function getVehiclesByStatus(Request $request)
    {
        return $this->vehicleOperationsController->getVehiclesByStatus($request);
    }

    /**
     * Start timer for selected vehicles
     */
    public function startTimer(Request $request)
    {
        return $this->vehicleOperationsController->startTimer($request);
    }

    /**
     * Assign route to selected vehicles
     */
    public function assignRoute(Request $request)
    {
        return $this->vehicleOperationsController->assignRoute($request);
    }

    /**
     * Return vehicles to yard
     */
    public function returnToYard(Request $request)
    {
        return $this->vehicleOperationsController->returnToYard($request);
    }

    /**
     * Pause vehicle
     */
    public function pause(Request $request, Vehicle $vehicle)
    {
        return $this->vehicleOperationsController->pause($request, $vehicle);
    }

    /**
     * Resume vehicle
     */
    public function resume(Request $request, Vehicle $vehicle)
    {
        return $this->vehicleOperationsController->resume($request, $vehicle);
    }

    /**
     * Move vehicle to workshop
     */
    public function moveToWorkshop(Request $request)
    {
        return $this->vehicleOperationsController->moveToWorkshop($request);
    }
}

