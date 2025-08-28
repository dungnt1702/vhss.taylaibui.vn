<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class WaitingVehiclesController extends Controller
{
    /**
     * Display waiting vehicles (xe đang chờ)
     */
    public function index()
    {
        $vehicles = Vehicle::waiting()->latest()->get();
        return view('vehicles.waiting_vehicles', compact('vehicles'));
    }

    /**
     * Get waiting vehicles for API
     */
    public function getWaitingVehicles()
    {
        $vehicles = Vehicle::waiting()->latest()->get();
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
