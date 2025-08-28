<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class RepairingVehiclesController extends Controller
{
    /**
     * Display repairing vehicles (xe đang sửa chữa)
     */
    public function index()
    {
        $vehicles = Vehicle::where('status', 'repairing')->latest()->get();
        return view('vehicles.repairing_vehicles', compact('vehicles'));
    }

    /**
     * Get repairing vehicles for API
     */
    public function getRepairingVehicles()
    {
        $vehicles = Vehicle::where('status', 'repairing')->latest()->get();
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
