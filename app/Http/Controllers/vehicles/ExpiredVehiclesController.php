<?php

namespace App\Http\Controllers\vehicles;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class ExpiredVehiclesController extends VehicleBaseController
{
    /**
     * Display expired vehicles (xe hết giờ)
     */
    public function index()
    {
        $vehicles = Vehicle::expired()->latest()->get();
        return view('vehicles.expired_vehicles', compact('vehicles'));
    }

    /**
     * Get expired vehicles for API
     */
    public function getExpiredVehicles()
    {
        $vehicles = Vehicle::expired()->latest()->get();
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
