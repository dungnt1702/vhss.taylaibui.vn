<?php

namespace App\Http\Controllers\vehicles;

use App\Models\Vehicle;
use App\Models\VehicleTechnicalIssue;
use Illuminate\Http\Request;

class MaintainingVehiclesController extends VehicleBaseController
{
    /**
     * Display maintaining vehicles (xe đang bảo trì)
     */
    public function index()
    {
        $vehicles = Vehicle::where('status', 'maintaining')
            ->with(['technicalIssues' => function($query) {
                $query->where('issue_type', 'maintenance')
                      ->where('status', '!=', 'completed')
                      ->latest();
            }])
            ->latest()
            ->get();
            
        return view('vehicles.maintaining_vehicles', compact('vehicles'));
    }

    /**
     * Get maintaining vehicles for API
     */
    public function getMaintainingVehicles()
    {
        $vehicles = Vehicle::where('status', 'maintaining')
            ->with(['technicalIssues' => function($query) {
                $query->where('issue_type', 'maintenance')
                      ->where('status', '!=', 'completed')
                      ->latest();
            }])
            ->latest()
            ->get();
            
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
