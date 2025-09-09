<?php

namespace App\Http\Controllers\vehicles;

use App\Models\Vehicle;
use App\Models\VehicleTechnicalIssue;
use Illuminate\Http\Request;

class WorkshopVehiclesController extends VehicleBaseController
{
    /**
     * Display workshop vehicles (xe trong xưởng)
     */
    public function index()
    {
        $vehicles = Vehicle::inactive()->latest()->get();
        
        // Add repair count for each vehicle
        $vehicles->each(function ($vehicle) {
            $vehicle->repair_count = VehicleTechnicalIssue::where('vehicle_id', $vehicle->id)
                ->where('issue_type', 'repair')
                ->whereIn('status', ['pending', 'in_progress'])
                ->count();
        });
        
        // Use vehicles_management with workshop filter
        return view('vehicles.vehicles_management', [
            'filter' => 'workshop',
            'vehicles' => $vehicles
        ]);
    }

    /**
     * Get workshop vehicles for API
     */
    public function getWorkshopVehicles()
    {
        $vehicles = Vehicle::inactive()->latest()->get();
        
        // Add repair count for each vehicle
        $vehicles->each(function ($vehicle) {
            $vehicle->repair_count = VehicleTechnicalIssue::where('vehicle_id', $vehicle->id)
                ->where('issue_type', 'repair')
                ->whereIn('status', ['pending', 'in_progress'])
                ->count();
        });
        
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
