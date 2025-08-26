<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleAttribute;
use Illuminate\Http\Request;

class GridDisplayController extends Controller
{
    /**
     * Display vehicles in grid format
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'grid');
        $perPage = $request->get('per_page', 12); // Số lượng xe hiển thị trên mỗi trang cho grid
        
        $vehicles = match($filter) {
            'grid' => Vehicle::latest()->paginate($perPage),
            'active' => Vehicle::notInactive()->latest()->paginate($perPage),
            'inactive' => Vehicle::inactive()->latest()->paginate($perPage),
            'running' => Vehicle::running()->latest()->paginate($perPage),
            'waiting' => Vehicle::waiting()->latest()->paginate($perPage),
            'expired' => Vehicle::expired()->latest()->paginate($perPage),
            'paused' => Vehicle::paused()->latest()->paginate($perPage),
            'route' => Vehicle::route()->latest()->paginate($perPage),
            default => Vehicle::latest()->paginate($perPage)
        };

        $pageTitle = match($filter) {
            'grid' => 'Hiển thị dạng lưới',
            'active' => 'Xe ngoài bãi',
            'inactive' => 'Xe trong xưởng',
            'running' => 'Xe đang chạy',
            'waiting' => 'Xe đang chờ',
            'expired' => 'Xe hết giờ',
            'paused' => 'Xe tạm dừng',
            default => 'Hiển thị dạng lưới'
        };

        // Set display mode to grid
        $displayMode = 'grid';

        // Get vehicle attributes for modal
        $colors = VehicleAttribute::getColors();
        $seats = VehicleAttribute::getSeats();
        $powerOptions = VehicleAttribute::getPowerOptions();
        $wheelSizes = VehicleAttribute::getWheelSizes();

        return view('vehicles.grid_display', compact(
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
     * Get vehicles for grid display API
     */
    public function getVehiclesForGrid(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 12);
        
        $vehicles = match($filter) {
            'all' => Vehicle::latest()->paginate($perPage, ['*'], 'page', $page),
            'active' => Vehicle::active()->latest()->paginate($perPage, ['*'], 'page', $page),
            'inactive' => Vehicle::inactive()->latest()->paginate($perPage, ['*'], 'page', $page),
            'running' => Vehicle::running()->latest()->paginate($perPage, ['*'], 'page', $page),
            'waiting' => Vehicle::waiting()->latest()->paginate($perPage, ['*'], 'page', $page),
            'expired' => Vehicle::expired()->latest()->paginate($perPage, ['*'], 'page', $page),
            'paused' => Vehicle::paused()->latest()->paginate($perPage, ['*'], 'page', $page),
            'route' => Vehicle::route()->latest()->paginate($perPage, ['*'], 'page', $page),
            default => Vehicle::latest()->paginate($perPage, ['*'], 'page', $page)
        };

        return response()->json([
            'success' => true,
            'vehicles' => $vehicles->items(),
            'pagination' => [
                'current_page' => $vehicles->currentPage(),
                'last_page' => $vehicles->lastPage(),
                'per_page' => $vehicles->perPage(),
                'total' => $vehicles->total()
            ]
        ]);
    }
}
