<?php

namespace App\Http\Controllers\vehicles;

use App\Models\Vehicle;
use App\Models\VehicleTechnicalIssue;
use Illuminate\Http\Request;

class RepairingVehiclesController extends VehicleBaseController
{
    /**
     * Display repair issues history (lịch sử sửa chữa)
     */
    public function index()
    {
        // Get all repair issues with vehicle information
        $repairIssues = \App\Models\VehicleTechnicalIssue::where('issue_type', 'repair')
            ->with(['vehicle', 'reporter'])
            ->latest('reported_at')
            ->get();
            
        return view('vehicles.repairing_vehicles', compact('repairIssues'));
    }

    /**
     * Get repair issues for API
     */
    public function getRepairingVehicles()
    {
        $repairIssues = \App\Models\VehicleTechnicalIssue::where('issue_type', 'repair')
            ->with(['vehicle', 'reporter'])
            ->latest('reported_at')
            ->get();
            
        return response()->json(['success' => true, 'repairIssues' => $repairIssues]);
    }
}
