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

        return view('vehicles.vehicles_management', [
            'filter' => 'attributes',
            'colors' => $colors, 
            'seats' => $seats, 
            'powerOptions' => $powerOptions, 
            'wheelSizes' => $wheelSizes
        ]);
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

    /**
     * Add new attribute
     */
    public function addAttribute(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:color,seats,power,wheel_size',
            'value' => 'required|string|max:255'
        ]);

        // Check if attribute already exists
        $existing = VehicleAttribute::where('type', $request->type)
            ->where('value', $request->value)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Thuộc tính này đã tồn tại'
            ], 400);
        }

        // Get next sort order
        $maxOrder = VehicleAttribute::where('type', $request->type)->max('sort_order') ?? 0;

        $attribute = VehicleAttribute::create([
            'type' => $request->type,
            'value' => $request->value,
            'is_active' => true,
            'sort_order' => $maxOrder + 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm thuộc tính thành công',
            'attribute' => $attribute
        ]);
    }

    /**
     * Delete attribute
     */
    public function deleteAttribute(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:color,seats,power,wheel_size',
            'value' => 'required|string'
        ]);

        // Check if this is the last attribute of this type
        $count = VehicleAttribute::where('type', $request->type)
            ->where('is_active', true)
            ->count();

        if ($count <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa thuộc tính cuối cùng của loại này'
            ], 400);
        }

        $attribute = VehicleAttribute::where('type', $request->type)
            ->where('value', $request->value)
            ->first();

        if (!$attribute) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thuộc tính'
            ], 404);
        }

        $attribute->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa thuộc tính thành công'
        ]);
    }

    /**
     * Edit attribute
     */
    public function editAttribute(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:color,seats,power,wheel_size',
            'old_value' => 'required|string',
            'new_value' => 'required|string|max:255'
        ]);

        $attribute = VehicleAttribute::where('type', $request->type)
            ->where('value', $request->old_value)
            ->first();

        if (!$attribute) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thuộc tính'
            ], 404);
        }

        // Check if new value already exists
        $existing = VehicleAttribute::where('type', $request->type)
            ->where('value', $request->new_value)
            ->where('id', '!=', $attribute->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Thuộc tính mới đã tồn tại'
            ], 400);
        }

        $attribute->update(['value' => $request->new_value]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thuộc tính thành công',
            'attribute' => $attribute
        ]);
    }

    /**
     * Get attributes count by type
     */
    public function getAttributesCount()
    {
        $counts = [];
        $types = ['color', 'seats', 'power', 'wheel_size'];
        
        foreach ($types as $type) {
            $counts[$type] = VehicleAttribute::where('type', $type)
                ->where('is_active', true)
                ->count();
        }

        return response()->json([
            'success' => true,
            'counts' => $counts
        ]);
    }
}
