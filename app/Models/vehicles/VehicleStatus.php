<?php

namespace App\Models\vehicles;

use Illuminate\Database\Eloquent\Model;

class VehicleStatus extends Model
{
    // Status constants
    const STATUS_READY = 'ready';        // Xe sẵn sàng chạy
    const STATUS_WORKSHOP = 'workshop';  // Xe trong xưởng
    const STATUS_RUNNING = 'running';    // Xe đang chạy
    const STATUS_WAITING = 'ready';      // Xe sẵn sàng (alias for ready)
    const STATUS_EXPIRED = 'expired';    // Xe hết giờ
    const STATUS_PAUSED = 'paused';      // Xe tạm dừng
    const STATUS_REPAIRING = 'repairing'; // Xe đang sửa chữa
    const STATUS_MAINTAINING = 'maintaining'; // Xe đang bảo trì
    const STATUS_GROUP = 'group';        // Xe trong tuyến (để tương thích)
    const STATUS_ROUTE = 'group';        // Alias cho STATUS_GROUP (để tương thích)

    /**
     * Get all available statuses
     */
    public static function getAllStatuses(): array
    {
        return [
            self::STATUS_READY,
            self::STATUS_WORKSHOP,
            self::STATUS_RUNNING,
            // self::STATUS_WAITING, // Deprecated: use STATUS_READY
            self::STATUS_EXPIRED,
            self::STATUS_PAUSED,
            self::STATUS_REPAIRING,
            self::STATUS_MAINTAINING,
            self::STATUS_GROUP
        ];
    }

    /**
     * Get status display name
     */
    public static function getDisplayName(string $status): string
    {
        return match($status) {
            self::STATUS_READY => 'Xe sẵn sàng chạy',
            self::STATUS_WORKSHOP => 'Xe trong xưởng',
            self::STATUS_RUNNING => 'Xe đang chạy',
            self::STATUS_WAITING => 'Xe sẵn sàng', // Alias for STATUS_READY
            self::STATUS_EXPIRED => 'Xe hết giờ',
            self::STATUS_PAUSED => 'Xe tạm dừng',
            self::STATUS_REPAIRING => 'Xe đang sửa chữa',
            self::STATUS_MAINTAINING => 'Xe đang bảo trì',
            self::STATUS_ROUTE => 'Xe trong tuyến',
            default => 'Không xác định'
        };
    }

    /**
     * Get status color class for UI
     */
    public static function getColorClass(string $status): string
    {
        return match($status) {
            self::STATUS_READY => 'bg-green-100 text-green-800',
            self::STATUS_WORKSHOP => 'bg-red-100 text-red-800',
            self::STATUS_RUNNING => 'bg-blue-100 text-blue-800',
            self::STATUS_WAITING => 'bg-green-100 text-green-800', // Same as STATUS_READY
            self::STATUS_EXPIRED => 'bg-orange-100 text-orange-800',
            self::STATUS_PAUSED => 'bg-gray-100 text-gray-800',
            self::STATUS_REPAIRING => 'bg-purple-100 text-purple-800',
            self::STATUS_MAINTAINING => 'bg-indigo-100 text-indigo-800',
            self::STATUS_ROUTE => 'bg-indigo-100 text-indigo-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get available status transitions
     */
    public static function getAvailableTransitions(string $currentStatus): array
    {
        return match($currentStatus) {
            self::STATUS_READY => [self::STATUS_RUNNING, self::STATUS_ROUTE, self::STATUS_WORKSHOP],
            self::STATUS_RUNNING => [self::STATUS_PAUSED, self::STATUS_EXPIRED, self::STATUS_READY],
            self::STATUS_PAUSED => [self::STATUS_RUNNING, self::STATUS_READY],
            self::STATUS_EXPIRED => [self::STATUS_READY, self::STATUS_WORKSHOP],
            self::STATUS_ROUTE => [self::STATUS_READY, self::STATUS_WORKSHOP],
            self::STATUS_WORKSHOP => [self::STATUS_READY, self::STATUS_REPAIRING, self::STATUS_MAINTAINING],
            self::STATUS_REPAIRING => [self::STATUS_READY, self::STATUS_WORKSHOP],
            self::STATUS_MAINTAINING => [self::STATUS_READY, self::STATUS_WORKSHOP],
            default => []
        };
    }

    /**
     * Check if status transition is valid
     */
    public static function isValidTransition(string $fromStatus, string $toStatus): bool
    {
        $availableTransitions = self::getAvailableTransitions($fromStatus);
        return in_array($toStatus, $availableTransitions);
    }

    /**
     * Get statuses that represent active vehicles (not in workshop)
     */
    public static function getActiveStatuses(): array
    {
        return [
            self::STATUS_READY,
            self::STATUS_RUNNING,
            // self::STATUS_WAITING, // Deprecated: use STATUS_READY
            self::STATUS_EXPIRED,
            self::STATUS_PAUSED,
            self::STATUS_ROUTE
        ];
    }

    /**
     * Get statuses that represent vehicles that can be managed
     */
    public static function getManageableStatuses(): array
    {
        return [
            self::STATUS_READY,
            self::STATUS_RUNNING,
            self::STATUS_PAUSED,
            self::STATUS_EXPIRED,
            self::STATUS_ROUTE
        ];
    }

    /**
     * Get remaining time info for a vehicle
     */
    public static function getRemainingTimeInfo(string $status, $startTime, $endTime, $pausedAt, $pausedRemainingSeconds): ?array
    {
        if ($status !== self::STATUS_RUNNING && $status !== self::STATUS_PAUSED) {
            return null;
        }

        if ($status === self::STATUS_PAUSED && $pausedRemainingSeconds !== null) {
            return [
                'remaining_seconds' => $pausedRemainingSeconds,
                'remaining_minutes' => ceil($pausedRemainingSeconds / 60),
                'is_paused' => true
            ];
        }

        if ($endTime && $startTime) {
            $remainingSeconds = max(0, strtotime($endTime) - time());
            return [
                'remaining_seconds' => $remainingSeconds,
                'remaining_minutes' => ceil($remainingSeconds / 60),
                'is_paused' => false
            ];
        }

        return null;
    }
}
