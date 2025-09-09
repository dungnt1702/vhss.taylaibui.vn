<?php

namespace App\Http\Controllers\vehicles;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class ReadyVehiclesController extends VehicleBaseController
{
    /**
     * Display ready vehicles (xe sẵn sàng)
     */
    public function index()
    {
        $vehicles = Vehicle::ready()->latest()->get();
        return view('vehicles.vehicles_management', [
            'filter' => 'ready',
            'vehicles' => $vehicles
        ]);
    }

    /**
     * Get ready vehicles for API
     */
    public function getReadyVehicles()
    {
        $vehicles = Vehicle::ready()->latest()->get();
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
