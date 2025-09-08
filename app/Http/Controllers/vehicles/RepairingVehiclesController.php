<?php

namespace App\Http\Controllers\vehicles;

use App\Models\Vehicle;
use App\Models\VehicleTechnicalIssue;
use Illuminate\Http\Request;

class RepairingVehiclesController extends VehicleBaseController
{
    /**
     * Display repairing vehicles (xe đang sửa chữa)
     */
    public function index()
    {
        $vehicles = Vehicle::where('status', 'repairing')
            ->with(['technicalIssues' => function($query) {
                $query->where('issue_type', 'repair')
                      ->where('status', '!=', 'completed')
                      ->latest();
            }])
            ->latest()
            ->get();
            
        return view('vehicles.repairing_vehicles', compact('vehicles'));
    }

    /**
     * Get repairing vehicles for API
     */
    public function getRepairingVehicles()
    {
        $vehicles = Vehicle::where('status', 'repairing')
            ->with(['technicalIssues' => function($query) {
                $query->where('issue_type', 'repair')
                      ->where('status', '!=', 'completed')
                      ->latest();
            }])
            ->latest()
            ->get();
            
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
