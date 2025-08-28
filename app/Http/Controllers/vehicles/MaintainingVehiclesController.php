<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class MaintainingVehiclesController extends Controller
{
    /**
     * Display maintaining vehicles (xe đang bảo trì)
     */
    public function index()
    {
        $vehicles = Vehicle::where('status', 'maintaining')->latest()->get();
        return view('vehicles.maintaining_vehicles', compact('vehicles'));
    }

    /**
     * Get maintaining vehicles for API
     */
    public function getMaintainingVehicles()
    {
        $vehicles = Vehicle::where('status', 'maintaining')->latest()->get();
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
