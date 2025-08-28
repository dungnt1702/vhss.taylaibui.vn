<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class WorkshopVehiclesController extends Controller
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
