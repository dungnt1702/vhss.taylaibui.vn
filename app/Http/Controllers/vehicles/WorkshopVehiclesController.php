<?php

namespace App\Http\Controllers\vehicles;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class WorkshopVehiclesController extends VehicleBaseController
{
    /**
     * Display workshop vehicles (xe trong xưởng)
     */
    public function index()
    {
        $vehicles = Vehicle::inactive()->latest()->get();
        return view('vehicles.workshop_vehicles', compact('vehicles'));
    }

    /**
     * Get workshop vehicles for API
     */
    public function getWorkshopVehicles()
    {
        $vehicles = Vehicle::inactive()->latest()->get();
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
