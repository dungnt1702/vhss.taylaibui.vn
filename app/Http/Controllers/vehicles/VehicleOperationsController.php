<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehicleOperationsController extends Controller
{
    /**
     * Start timer for selected vehicles
     */
    public function startTimer(Request $request)
    {
        try {
            $request->validate([
                'duration' => 'required|integer|min:1|max:120'
            ]);

            // Handle both vehicle_ids (array) and vehicle_id (single) formats
            $vehicleIds = $this->getVehicleIds($request);
            
            if (empty($vehicleIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng chọn ít nhất một xe'
                ], 400);
            }

            // Validate vehicle IDs exist
            $this->validateVehicleIds($vehicleIds);

            $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
            
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
                'route_number' => 'required|integer|min:1|max:100'
            ]);

            // Handle both vehicle_ids (array) and vehicle_id (single) formats
            $vehicleIds = $this->getVehicleIds($request);
            
            if (empty($vehicleIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng chọn ít nhất một xe'
                ], 400);
            }

            // Validate vehicle IDs exist
            $this->validateVehicleIds($vehicleIds);

            $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
            
            foreach ($vehicles as $vehicle) {
                $vehicle->update([
                    'status' => Vehicle::STATUS_ROUTING,
                    'route_number' => $request->route_number,
                    'start_time' => now(),
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
            // Handle both vehicle_ids (array) and vehicle_id (single) formats
            $vehicleIds = $this->getVehicleIds($request);
            
            if (empty($vehicleIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng chọn ít nhất một xe'
                ], 400);
            }

            // Validate vehicle IDs exist
            $this->validateVehicleIds($vehicleIds);

            $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
            
            foreach ($vehicles as $vehicle) {
                $vehicle->update([
                    'status' => Vehicle::STATUS_READY,
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
     * Return vehicles to yard with notes (for workshop)
     */
    public function returnToYardWithNotes(Request $request)
    {
        try {
            // Validate request data
            $request->validate([
                'vehicle_ids' => 'required|array|min:1',
                'vehicle_ids.*' => 'integer|exists:vehicles,id',
                'notes' => 'nullable|string|max:500'
            ]);

            $vehicleIds = $request->input('vehicle_ids');
            $notes = $request->input('notes', '');

            $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
            
            foreach ($vehicles as $vehicle) {
                $vehicle->update([
                    'status' => Vehicle::STATUS_READY,
                    'start_time' => null,
                    'end_time' => null,
                    'paused_at' => null,
                    'paused_remaining_seconds' => null,
                    'route_number' => null,
                    'notes' => $notes, // Always update notes for workshop
                    'status_changed_at' => now()
                ]);
            }

            $message = !empty($notes) ? 
                'Đã đưa ' . count($vehicles) . ' xe về bãi và cập nhật ghi chú' : 
                'Đã đưa ' . count($vehicles) . ' xe về bãi';

            return response()->json([
                'success' => true,
                'message' => $message,
                'vehicles' => $vehicles
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi đưa xe về bãi với ghi chú: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đưa xe về bãi'
            ], 500);
        }
    }

    /**
     * Update vehicle notes only
     */
    public function updateNotes(Request $request, Vehicle $vehicle)
    {
        try {
            // Validate request data
            $request->validate([
                'notes' => 'nullable|string|max:500'
            ]);

            $vehicle->update([
                'notes' => $request->input('notes', ''),
                'status_changed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ghi chú xe đã được cập nhật thành công!',
                'vehicle' => $vehicle
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật ghi chú xe: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật ghi chú xe'
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
                'notes' => $request->reason,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Xe đã được chuyển về xưởng thành công!',
                'vehicle' => $vehicle
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi chuyển xe về xưởng: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi chuyển xe về xưởng'
            ], 500);
        }
    }

    /**
     * Add time to vehicle timer
     */
    public function addTime(Request $request)
    {
        try {
            // Log request details
            Log::info('Add time request received', [
                'request_data' => $request->all(),
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'user_agent' => $request->header('User-Agent')
            ]);
            
            $request->validate([
                'vehicle_ids' => 'required|array',
                'vehicle_ids.*' => 'exists:vehicles,id',
                'duration' => 'required|integer|min:1|max:120'
            ]);

            $vehicleIds = $request->vehicle_ids;
            $duration = $request->duration;
            
            Log::info('Add time validation passed', [
                'vehicle_ids' => $vehicleIds,
                'duration' => $duration
            ]);

            // Validate vehicle IDs exist
            $this->validateVehicleIds($vehicleIds);

            $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
            
            foreach ($vehicles as $vehicle) {
                // Log vehicle state before update
                Log::info('Processing vehicle for add time', [
                    'vehicle_id' => $vehicle->id,
                    'current_status' => $vehicle->status,
                    'current_end_time' => $vehicle->end_time ? $vehicle->end_time->format('Y-m-d H:i:s') : 'null',
                    'request_duration' => $duration
                ]);
                
                if ($vehicle->status === Vehicle::STATUS_RUNNING && $vehicle->end_time) {
                    // Xe đang chạy: thêm thời gian vào end_time hiện tại
                    $oldEndTime = $vehicle->end_time;
                    $newEndTime = $oldEndTime->copy()->addMinutes($duration);
                    
                    Log::info('Adding time to running vehicle', [
                        'vehicle_id' => $vehicle->id,
                        'old_end_time' => $oldEndTime->format('Y-m-d H:i:s'),
                        'duration_minutes' => $duration,
                        'new_end_time' => $newEndTime->format('Y-m-d H:i:s'),
                        'difference_minutes' => $oldEndTime->diffInMinutes($newEndTime)
                    ]);
                    
                    $vehicle->update([
                        'end_time' => $newEndTime,
                        'status_changed_at' => now()
                    ]);
                    
                    // Log after update
                    Log::info('Vehicle updated successfully', [
                        'vehicle_id' => $vehicle->id,
                        'final_end_time' => $vehicle->fresh()->end_time->format('Y-m-d H:i:s')
                    ]);
                    
                } elseif ($vehicle->status === Vehicle::STATUS_EXPIRED) {
                    // Xe hết giờ: xử lý giống như xe khác - chỉ cập nhật thời gian
                    $currentTime = now();
                    $newEndTime = $currentTime->copy()->addMinutes($duration);
                    
                    Log::info('Adding time to expired vehicle', [
                        'vehicle_id' => $vehicle->id,
                        'current_time' => $currentTime->format('Y-m-d H:i:s'),
                        'duration_minutes' => $duration,
                        'new_end_time' => $newEndTime->format('Y-m-d H:i:s')
                    ]);
                    
                    $vehicle->update([
                        'end_time' => $newEndTime,
                        'status' => Vehicle::STATUS_RUNNING, // Chuyển sang running
                        'status_changed_at' => now(),
                        'paused_at' => null, // Xóa dữ liệu paused nếu có
                        'paused_remaining_seconds' => null // Xóa dữ liệu paused nếu có
                    ]);
                    
                    // Log after update
                    Log::info('Expired vehicle updated successfully', [
                        'vehicle_id' => $vehicle->id,
                        'final_end_time' => $vehicle->fresh()->end_time->format('Y-m-d H:i:s'),
                        'final_status' => $vehicle->fresh()->status
                    ]);
                }
            }

            // Tìm xe expired đã được chuyển sang running
            $expiredVehicles = $vehicles->where('status', Vehicle::STATUS_EXPIRED);
            $runningVehicles = $vehicles->where('status', Vehicle::STATUS_RUNNING);
            
            // Lấy end_time của xe expired (nếu có) để trả về
            $expiredEndTime = null;
            if ($expiredVehicles->isNotEmpty()) {
                $expiredVehicle = $expiredVehicles->first();
                $expiredEndTime = $expiredVehicle->end_time ? $expiredVehicle->end_time->timestamp * 1000 : null;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm ' . $duration . ' phút cho ' . count($vehicles) . ' xe',
                'new_end_time' => $newEndTime ? $newEndTime->timestamp * 1000 : null,
                'expired_end_time' => $expiredEndTime, // Thêm end_time cho xe expired
                'duration_added' => $duration,
                'vehicles' => $vehicles,
                'updated_vehicles' => $runningVehicles->count(),
                'expired_vehicles' => $expiredVehicles->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm thời gian: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm thời gian'
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
                'ready' => Vehicle::active()->latest()->get(),
                'running' => Vehicle::running()->latest()->get(),
                'paused' => Vehicle::paused()->latest()->get(),
                'expired' => Vehicle::expired()->latest()->get(),
                'route' => Vehicle::route()->latest()->get(),
                'waiting' => Vehicle::waiting()->latest()->get(),
                'workshop' => Vehicle::inactive()->latest()->get(),
                default => Vehicle::active()->latest()->get()
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

    /**
     * Helper method to get vehicle IDs from request
     */
    private function getVehicleIds(Request $request): array
    {
        if ($request->has('vehicle_ids') && is_array($request->vehicle_ids)) {
            return $request->vehicle_ids;
        } elseif ($request->has('vehicle_id')) {
            return [$request->vehicle_id];
        }
        return [];
    }

    /**
     * Helper method to validate vehicle IDs exist
     */
    private function validateVehicleIds(array $vehicleIds): void
    {
        foreach ($vehicleIds as $id) {
            if (!Vehicle::find($id)) {
                throw new \Exception('Xe không tồn tại');
            }
        }
    }
}

