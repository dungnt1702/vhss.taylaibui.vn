<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActiveVehiclesController extends Controller
{
    /**
     * Display active vehicles (xe ngoài bãi)
     */
    public function index()
    {
        // Lấy xe đang hoạt động (không phải trong xưởng)
        $vehicles = Vehicle::notInactive()->latest()->get();
        
        // Lấy xe đang chạy
        $runningVehicles = Vehicle::running()->latest()->get();
        
        // Lấy xe trong tuyến
        $routeVehicles = Vehicle::route()->latest()->get();

        return view('vehicles.active_vehicles', compact(
            'vehicles', 
            'runningVehicles', 
            'routeVehicles'
        ));
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
            Log::error('Lỗi khi bắt đầu bấm giờ: ' . $e->getMessage());
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
            Log::error('Lỗi khi phân tuyến: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi phân tuyến'
            ], 500);
        }
    }

    /**
     * Return vehicles to yard
     */
    public function returnToYard(Request $request)
    {
        try {
            $request->validate([
                'vehicle_ids' => 'required|array',
                'vehicle_ids.*' => 'exists:vehicles,id'
            ]);

            $vehicles = Vehicle::whereIn('id', $request->vehicle_ids)->get();
            
            foreach ($vehicles as $vehicle) {
                $vehicle->update([
                    'status' => Vehicle::STATUS_ACTIVE,
                    'start_time' => null,
                    'end_time' => null,
                    'paused_at' => null,
                    'paused_remaining_seconds' => null,
                    'route_number' => null,
                    'status_changed_at' => now()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã đưa ' . count($vehicles) . ' xe về bãi',
                'vehicles' => $vehicles
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi đưa xe về bãi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đưa xe về bãi'
            ], 500);
        }
    }

    /**
     * Pause vehicle
     */
    public function pause(Request $request, Vehicle $vehicle)
    {
        try {
            $vehicle->update([
                'status' => Vehicle::STATUS_PAUSED,
                'paused_at' => now(),
                'status_changed_at' => now()
            ]);

            // Tính toán thời gian còn lại
            if ($vehicle->end_time) {
                $remainingSeconds = max(0, strtotime($vehicle->end_time) - time());
                $vehicle->update(['paused_remaining_seconds' => $remainingSeconds]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã tạm dừng xe ' . $vehicle->name,
                'vehicle' => $vehicle
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạm dừng xe: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạm dừng xe'
            ], 500);
        }
    }

    /**
     * Resume vehicle
     */
    public function resume(Request $request, Vehicle $vehicle)
    {
        try {
            $updateData = [
                'status' => Vehicle::STATUS_RUNNING,
                'status_changed_at' => now()
            ];

            // Nếu có thời gian còn lại từ khi pause, tính toán end_time mới
            if ($vehicle->paused_remaining_seconds) {
                $updateData['end_time'] = now()->addSeconds($vehicle->paused_remaining_seconds);
                $updateData['paused_remaining_seconds'] = null;
            }

            $vehicle->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Đã tiếp tục xe ' . $vehicle->name,
                'vehicle' => $vehicle
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi tiếp tục xe: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tiếp tục xe'
            ], 500);
        }
    }

    /**
     * Get vehicles by status for API
     */
    public function getVehiclesByStatus(Request $request)
    {
        try {
            $status = $request->get('status', 'all');
            
            $vehicles = match($status) {
                'active' => Vehicle::active()->latest()->get(),
                'running' => Vehicle::running()->latest()->get(),
                'paused' => Vehicle::paused()->latest()->get(),
                'expired' => Vehicle::expired()->latest()->get(),
                'route' => Vehicle::route()->latest()->get(),
                'waiting' => Vehicle::waiting()->latest()->get(),
                default => Vehicle::notInactive()->latest()->get()
            };

            return response()->json([
                'success' => true,
                'vehicles' => $vehicles
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy danh sách xe theo trạng thái: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách xe'
            ], 500);
        }
    }
}
