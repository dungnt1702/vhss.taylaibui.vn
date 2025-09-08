<?php

namespace App\Http\Controllers\vehicles;

use App\Models\Vehicle;
use App\Models\VehicleTechnicalIssue;
use Illuminate\Http\Request;

class MaintainingVehiclesController extends VehicleBaseController
{
    /**
     * Display maintenance issues history (lịch sử bảo trì)
     */
    public function index()
    {
        // Get all maintenance issues with vehicle information
        $maintenanceIssues = \App\Models\VehicleTechnicalIssue::where('issue_type', 'maintenance')
            ->with(['vehicle', 'reporter'])
            ->latest('reported_at')
            ->get();
            
        return view('vehicles.maintaining_vehicles', compact('maintenanceIssues'));
    }

    /**
     * Get maintenance issues for API
     */
    public function getMaintainingVehicles()
    {
        $maintenanceIssues = \App\Models\VehicleTechnicalIssue::where('issue_type', 'maintenance')
            ->with(['vehicle', 'reporter'])
            ->latest('reported_at')
            ->get();
            
        return response()->json(['success' => true, 'maintenanceIssues' => $maintenanceIssues]);
    }
}
