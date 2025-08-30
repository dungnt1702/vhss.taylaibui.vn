<?php

namespace App\Http\Controllers\vehicles;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class RunningVehiclesController extends VehicleBaseController
{
    /**
     * Display running vehicles (xe đang chạy)
     */
    public function index()
    {
        $vehicles = Vehicle::running()->latest()->get();
        return view('vehicles.running_vehicles', compact('vehicles'));
    }

    /**
     * Get running vehicles for API
     */
    public function getRunningVehicles()
    {
        $vehicles = Vehicle::running()->latest()->get();
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
