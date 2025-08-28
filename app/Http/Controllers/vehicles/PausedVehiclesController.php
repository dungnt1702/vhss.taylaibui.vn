<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class PausedVehiclesController extends Controller
{
    /**
     * Display paused vehicles (xe tạm dừng)
     */
    public function index()
    {
        $vehicles = Vehicle::paused()->latest()->get();
        return view('vehicles.paused_vehicles', compact('vehicles'));
    }

    /**
     * Get paused vehicles for API
     */
    public function getPausedVehicles()
    {
        $vehicles = Vehicle::paused()->latest()->get();
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
