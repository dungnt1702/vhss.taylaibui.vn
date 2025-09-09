<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleAttribute;
use Illuminate\Http\Request;
use App\Http\Controllers\vehicles\VehicleOperationsController;

class VehiclesListController extends Controller
{
    /**
     * Display vehicles list with filtering
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'vehicles_list');
        $perPage = $request->get('per_page', 10);
        
        $vehicles = match($filter) {
            'vehicles_list' => Vehicle::latest()->paginate($perPage),
            'ready' => Vehicle::active()->latest()->paginate($perPage),
            'workshop' => Vehicle::inactive()->latest()->paginate($perPage),
            'running' => Vehicle::running()->latest()->paginate($perPage),
            'expired' => Vehicle::expired()->latest()->paginate($perPage),
            'paused' => Vehicle::paused()->latest()->paginate($perPage),
            'route' => Vehicle::route()->latest()->paginate($perPage),
            default => Vehicle::latest()->paginate($perPage)
        };

        $pageTitle = match($filter) {
            'vehicles_list' => 'Danh sách xe',
            'ready' => 'Xe sẵn sàng chạy',
            'workshop' => 'Xe trong xưởng',
            'running' => 'Xe đang chạy',
            'expired' => 'Xe hết giờ',
            'paused' => 'Xe tạm dừng',
            default => 'Danh sách xe'
        };

        // Get display mode
        $displayMode = 'list';

        // Get vehicle attributes for modal
        $colors = VehicleAttribute::getColors();
        $seats = VehicleAttribute::getSeats();
        $powerOptions = VehicleAttribute::getPowerOptions();
        $wheelSizes = VehicleAttribute::getWheelSizes();

        // Initialize all vehicle arrays for consistency with VehicleManagementController
        $activeVehicles = collect();
        $runningVehicles = collect();
        $pausedVehicles = collect();
        $expiredVehicles = collect();
        $routingVehicles = collect();
        $repairIssues = null;
        $maintenanceIssues = null;

        return view('vehicles.vehicles_management', compact(
            'vehicles', 
            'activeVehicles',
            'runningVehicles',
            'pausedVehicles',
            'expiredVehicles',
            'routingVehicles',
            'repairIssues',
            'maintenanceIssues',
            'filter', 
            'pageTitle', 
            'displayMode', 
            'colors', 
            'seats', 
            'powerOptions', 
            'wheelSizes'
        ));
    }

    /**
     * Get vehicles for API
     */
    public function getVehicles(Request $request)
    {
        $operationsController = new VehicleOperationsController();
        return $operationsController->getVehiclesByStatus($request);
    }
}
