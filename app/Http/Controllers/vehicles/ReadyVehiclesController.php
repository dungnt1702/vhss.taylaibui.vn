<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleAttribute;
use Illuminate\Http\Request;

class ReadyVehiclesController extends Controller
{
    /**
     * Display ready vehicles (xe sẵn sàng chạy)
     */
    public function index()
    {
        // Lấy xe có trạng thái ready (sẵn sàng chạy)
        $vehicles = Vehicle::active()->latest()->get();

        return view('vehicles.ready_vehicles', compact('vehicles'));
    }

    /**
     * Get ready vehicles for API
     */
    public function getReadyVehicles()
    {
        $vehicles = Vehicle::active()->latest()->get();

        return response()->json([
            'success' => true,
            'vehicles' => $vehicles
        ]);
    }

    /**
     * Start timer for selected vehicles
     */
    public function startTimer(Request $request)
    {
        try {
            $request->validate([
                'vehicle_ids' => 'required|array',
                'vehicle_ids.*' => 'exists:vehicles,id',
                'duration' => 'required|integer|min:1|max:120'
            ]);

            $vehicles = Vehicle::whereIn('id', $request->vehicle_ids)->get();
            
            foreach ($vehicles as $vehicle) {
                $vehicle->update([
                    'status' => Vehicle::STATUS_RUNNING,
                    'start_time' => now(),
                    'end_time' => now()->addMinutes($request->duration),
                    'status_changed_at' => now(),
                    'paused_at' => null,
                    'paused_remaining_seconds' => null
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã bắt đầu bấm giờ cho ' . count($vehicles) . ' xe',
                'vehicles' => $vehicles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi bắt đầu bấm giờ'
            ], 500);
        }
    }

    /**
     * Assign route to selected vehicles
     */
    public function assignRoute(Request $request)
    {
        try {
            $request->validate([
                'vehicle_ids' => 'required|array',
                'vehicle_ids.*' => 'exists:vehicles,id',
                'route_number' => 'required|integer|min:1|max:100'
            ]);

            $vehicles = Vehicle::whereIn('id', $request->vehicle_ids)->get();
            
            foreach ($vehicles as $vehicle) {
                $vehicle->update([
                    'status' => Vehicle::STATUS_ROUTE,
                    'route_number' => $request->route_number,
                    'status_changed_at' => now()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã phân tuyến ' . count($vehicles) . ' xe cho cung đường ' . $request->route_number,
                'vehicles' => $vehicles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi phân tuyến'
            ], 500);
        }
    }

    /**
     * Move vehicle to workshop
     */
    public function moveToWorkshop(Request $request)
    {
        try {
            $request->validate([
                'vehicle_id' => 'required|exists:vehicles,id',
                'reason' => 'required|string|max:500'
            ]);

            $vehicle = Vehicle::findOrFail($request->vehicle_id);
            $vehicle->update([
                'status' => Vehicle::STATUS_WORKSHOP,
                'status_changed_at' => now(),
                'notes' => 'Chuyển về xưởng: ' . $request->reason,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Xe đã được chuyển về xưởng thành công!',
                'vehicle' => $vehicle
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi chuyển xe về xưởng'
            ], 500);
        }
    }
}
