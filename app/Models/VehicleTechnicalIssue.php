<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleTechnicalIssue extends Model
{
    protected $fillable = [
        'vehicle_id',
        'issue_type',
        'category',
        'description',
        'notes',
        'result',
        'status',
        'reported_at',
        'completed_at',
        'reported_by',
        'assigned_to'
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Constants for issue types
    const ISSUE_TYPE_REPAIR = 'repair';
    const ISSUE_TYPE_MAINTENANCE = 'maintenance';

    // Constants for status
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the vehicle that owns the technical issue
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the user who reported the issue
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the user assigned to handle the issue
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get available categories for repair issues
     */
    public static function getRepairCategories(): array
    {
        return [
            'engine' => 'Động cơ',
            'brake_system' => 'Hệ thống phanh',
            'transmission' => 'Hộp số',
            'electrical' => 'Hệ thống điện',
            'suspension' => 'Hệ thống treo',
            'steering' => 'Hệ thống lái',
            'exhaust' => 'Hệ thống xả',
            'cooling' => 'Hệ thống làm mát',
            'fuel_system' => 'Hệ thống nhiên liệu',
            'tires' => 'Lốp xe',
            'lights' => 'Hệ thống đèn',
            'air_conditioning' => 'Điều hòa',
            'other' => 'Khác'
        ];
    }

    /**
     * Get available categories for maintenance issues
     */
    public static function getMaintenanceCategories(): array
    {
        return [
            'oil_change' => 'Thay dầu',
            'filter_replacement' => 'Thay lọc',
            'brake_inspection' => 'Kiểm tra phanh',
            'tire_rotation' => 'Đảo lốp',
            'battery_check' => 'Kiểm tra ắc quy',
            'belt_replacement' => 'Thay dây curoa',
            'spark_plug' => 'Thay bugi',
            'air_filter' => 'Thay lọc gió',
            'coolant_check' => 'Kiểm tra nước làm mát',
            'general_inspection' => 'Kiểm tra tổng thể',
            'cleaning' => 'Vệ sinh',
            'other' => 'Khác'
        ];
    }

    /**
     * Get all available categories based on issue type
     */
    public static function getCategoriesByType(string $issueType): array
    {
        return $issueType === self::ISSUE_TYPE_REPAIR 
            ? self::getRepairCategories() 
            : self::getMaintenanceCategories();
    }

    /**
     * Get status labels
     */
    public static function getStatusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'Chờ xử lý',
            self::STATUS_IN_PROGRESS => 'Đang xử lý',
            self::STATUS_COMPLETED => 'Hoàn thành',
            self::STATUS_CANCELLED => 'Đã hủy'
        ];
    }

    /**
     * Get status color classes
     */
    public static function getStatusColorClasses(): array
    {
        return [
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_IN_PROGRESS => 'bg-blue-100 text-blue-800',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800'
        ];
    }

    /**
     * Get color class for a specific status
     */
    public function getStatusColorClass(): string
    {
        return self::getStatusColorClasses()[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get issue type labels
     */
    public static function getIssueTypeLabels(): array
    {
        return [
            self::ISSUE_TYPE_REPAIR => 'Sửa chữa',
            self::ISSUE_TYPE_MAINTENANCE => 'Bảo trì'
        ];
    }
}
