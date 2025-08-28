<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\VehicleAttribute;
use Illuminate\Http\Request;

class AttributesListController extends Controller
{
    /**
     * Display vehicle attributes (thuộc tính xe)
     */
    public function index()
    {
        $colors = VehicleAttribute::getColors();
        $seats = VehicleAttribute::getSeats();
        $powerOptions = VehicleAttribute::getPowerOptions();
        $wheelSizes = VehicleAttribute::getWheelSizes();

        return view('vehicles.attributes_list', compact(
            'colors', 
            'seats', 
            'powerOptions', 
            'wheelSizes'
        ));
    }

    /**
     * Get all attributes for API
     */
    public function getAllAttributes()
    {
        $attributes = [
            'colors' => VehicleAttribute::getColors(),
            'seats' => VehicleAttribute::getSeats(),
            'power_options' => VehicleAttribute::getPowerOptions(),
            'wheel_sizes' => VehicleAttribute::getWheelSizes()
        ];

        return response()->json([
            'success' => true,
            'attributes' => $attributes
        ]);
    }
}
