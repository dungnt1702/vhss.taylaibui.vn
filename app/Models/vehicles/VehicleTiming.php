<?php

namespace App\Models\vehicles;

use Illuminate\Database\Eloquent\Model;

class VehicleTiming extends Model
{
    /**
     * Calculate remaining time for a vehicle
     */
    public static function calculateRemainingTime($startTime, $endTime, $pausedAt = null, $pausedRemainingSeconds = null): ?array
    {
        if (!$endTime) {
            return null;
        }

        // If vehicle is paused, return paused remaining time
        if ($pausedAt && $pausedRemainingSeconds !== null) {
            return [
                'remaining_seconds' => $pausedRemainingSeconds,
                'remaining_minutes' => ceil($pausedRemainingSeconds / 60),
                'remaining_formatted' => self::formatTime($pausedRemainingSeconds),
                'is_paused' => true
            ];
        }

        // Calculate remaining time from end time
        $remainingSeconds = max(0, strtotime($endTime) - time());
        
        return [
            'remaining_seconds' => $remainingSeconds,
            'remaining_minutes' => ceil($remainingSeconds / 60),
            'remaining_formatted' => self::formatTime($remainingSeconds),
            'is_paused' => false
        ];
    }

    /**
     * Format time in MM:SS format
     */
    public static function formatTime(int $seconds): string
    {
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        
        return sprintf('%02d:%02d', $minutes, $remainingSeconds);
    }

    /**
     * Calculate session duration
     */
    public static function calculateSessionDuration($startTime, $endTime): ?int
    {
        if (!$startTime || !$endTime) {
            return null;
        }

        return strtotime($endTime) - strtotime($startTime);
    }

    /**
     * Check if vehicle session has expired
     */
    public static function isExpired($endTime): bool
    {
        if (!$endTime) {
            return false;
        }

        return time() > strtotime($endTime);
    }

    /**
     * Get time until expiration
     */
    public static function getTimeUntilExpiration($endTime): ?int
    {
        if (!$endTime) {
            return null;
        }

        $timeUntilExpiration = strtotime($endTime) - time();
        return max(0, $timeUntilExpiration);
    }

    /**
     * Calculate pause duration
     */
    public static function calculatePauseDuration($pausedAt): ?int
    {
        if (!$pausedAt) {
            return null;
        }

        return time() - strtotime($pausedAt);
    }

    /**
     * Get session progress percentage
     */
    public static function getSessionProgress($startTime, $endTime): ?float
    {
        if (!$startTime || !$endTime) {
            return null;
        }

        $totalDuration = strtotime($endTime) - strtotime($startTime);
        $elapsedTime = time() - strtotime($startTime);
        
        if ($totalDuration <= 0) {
            return 100.0;
        }

        $progress = ($elapsedTime / $totalDuration) * 100;
        return min(100.0, max(0.0, $progress));
    }

    /**
     * Check if vehicle is in warning time (last 5 minutes)
     */
    public static function isInWarningTime($endTime, int $warningMinutes = 5): bool
    {
        if (!$endTime) {
            return false;
        }

        $remainingSeconds = strtotime($endTime) - time();
        return $remainingSeconds > 0 && $remainingSeconds <= ($warningMinutes * 60);
    }

    /**
     * Get time status for display
     */
    public static function getTimeStatus($startTime, $endTime, $pausedAt = null): string
    {
        if (!$endTime) {
            return 'Chưa bắt đầu';
        }

        if (self::isExpired($endTime)) {
            return 'Đã hết giờ';
        }

        if ($pausedAt) {
            return 'Đang tạm dừng';
        }

        if ($startTime) {
            return 'Đang chạy';
        }

        return 'Chưa bắt đầu';
    }
}
