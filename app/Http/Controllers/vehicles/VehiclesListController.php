<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleAttribute;
use Illuminate\Http\Request;

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
            'waiting' => Vehicle::waiting()->latest()->paginate($perPage),
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
            'waiting' => 'Xe đang chờ',
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

        return view('vehicles.vehicles_list', compact(
            'vehicles', 
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
        $filter = $request->get('filter', 'all');
        
        $vehicles = match($filter) {
            'all' => Vehicle::latest()->get(),
            'ready' => Vehicle::active()->latest()->get(),
            'workshop' => Vehicle::inactive()->latest()->get(),
            'running' => Vehicle::running()->latest()->get(),
            'waiting' => Vehicle::waiting()->latest()->get(),
            'expired' => Vehicle::expired()->latest()->get(),
            'paused' => Vehicle::paused()->latest()->get(),
            'route' => Vehicle::route()->latest()->get(),
            'group' => Vehicle::route()->latest()->get(),
            default => Vehicle::latest()->get()
        };

        return response()->json([
            'success' => true,
            'vehicles' => $vehicles
        ]);
    }
}
