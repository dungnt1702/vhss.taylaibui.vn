<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\VehicleAttribute;
use Illuminate\Http\Request;

class VehicleAttributesController extends Controller
{
    /**
     * Show vehicle attributes management (Admin only)
     */
    public function index()
    {
        if (!auth()->user()->canManageVehicleAttributes()) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        $colors = VehicleAttribute::where('type', 'color')->orderBy('sort_order')->get();
        $seats = VehicleAttribute::where('type', 'seats')->orderBy('sort_order')->get();
        $powerOptions = VehicleAttribute::where('type', 'power')->orderBy('sort_order')->get();
        $wheelSizes = VehicleAttribute::where('type', 'wheel_size')->orderBy('sort_order')->get();

        return view('vehicles.vehicle_attributes', compact(
            'colors', 
            'seats', 
            'powerOptions', 
            'wheelSizes'
        ));
    }

    /**
     * Get all vehicle attributes for API
     */
    public function getAllAttributes()
    {
        try {
            $attributes = [
                'colors' => VehicleAttribute::getColors(),
                'seats' => VehicleAttribute::getSeats(),
                'powerOptions' => VehicleAttribute::getPowerOptions(),
                'wheelSizes' => VehicleAttribute::getWheelSizes()
            ];

            return response()->json([
                'success' => true,
                'attributes' => $attributes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thuộc tính xe'
            ], 500);
        }
    }

    /**
     * Get attributes by type
     */
    public function getAttributesByType(Request $request)
    {
        try {
            $type = $request->get('type');
            
            if (!in_array($type, ['color', 'seats', 'power', 'wheel_size'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Loại thuộc tính không hợp lệ'
                ], 400);
            }

            $attributes = VehicleAttribute::where('type', $type)
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'attributes' => $attributes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thuộc tính xe'
            ], 500);
        }
    }
}
