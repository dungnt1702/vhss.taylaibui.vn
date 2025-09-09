<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'seats',
        'power',
        'wheel_size',
        'status',
        'start_time',
        'end_time',
        'paused_at',
        'paused_remaining_seconds',
        'notes',
        'current_location',
        'last_maintenance',
        'next_maintenance',
        'route_number',
        'status_changed_at',
    ];

    protected $casts = [
        'last_maintenance' => 'datetime',
        'next_maintenance' => 'datetime',
        'status_changed_at' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'paused_at' => 'datetime',
        'paused_remaining_seconds' => 'integer',
        'route_number' => 'integer',
    ];

    // Status constants
    const STATUS_READY = 'ready';        // Xe sẵn sàng chạy (thay thế cho Xe ngoài bãi)
    const STATUS_WORKSHOP = 'workshop';  // Xe trong xưởng
    const STATUS_RUNNING = 'running';    // Xe đang chạy
    const STATUS_WAITING = 'ready';      // Xe sẵn sàng (alias for ready)
    const STATUS_EXPIRED = 'expired';    // Xe hết giờ
    const STATUS_PAUSED = 'paused';     // Xe tạm dừng
    const STATUS_ROUTING = 'routing';    // Xe đang theo đường
    const STATUS_REPAIRING = 'repairing'; // Xe đang sửa chữa
    const STATUS_MAINTAINING = 'maintaining'; // Xe đang bảo trì

    const STATUS_GROUP = 'group';        // Xe ngoài bãi
    const STATUS_ROUTE = 'group';        // Alias cho STATUS_GROUP (để tương thích)

    // Color options
    const COLORS = ['Xanh biển', 'Xanh cây', 'Cam', 'Đỏ', 'Vàng', 'Đen'];
    
    // Seat options
    const SEATS = ['1', '2'];
    
    // Power options
    const POWER_OPTIONS = ['48V-1000W', '60V-1200W'];
    
    // Wheel size options
    const WHEEL_SIZES = ['7inch', '8inch'];

    // Get status display name
    public function getStatusDisplayNameAttribute()
    {
        return \App\Models\vehicles\VehicleStatus::getDisplayName($this->status);
    }

    // Get status color class
    public function getStatusColorClassAttribute()
    {
        return \App\Models\vehicles\VehicleStatus::getColorClass($this->status);
    }

    // Check if vehicle can be managed by user
    public function canBeManagedBy($user)
    {
        return $user && $user->role === 'admin';
    }

    // Scope for filtering by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope for ready vehicles (previously active)
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_READY);
    }

    // Scope for workshop vehicles (previously inactive)
    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_WORKSHOP);
    }

    // Scope for running vehicles (chỉ những xe còn thời gian để chạy)
    public function scopeRunning($query)
    {
        return $query->where('status', self::STATUS_RUNNING)
                    ->where('end_time', '>', now()); // Chỉ lấy xe còn thời gian
    }

    // Scope for expired vehicles (xe đã hết giờ nhưng vẫn có status running)
    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_RUNNING)
                    ->where('end_time', '<=', now()); // Lấy xe đã hết giờ
    }

    // Scope for truly expired vehicles (status = expired)
    public function scopeTrulyExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    // Scope for paused vehicles
    public function scopePaused($query)
    {
        return $query->where('status', self::STATUS_PAUSED);
    }



    // Scope for group vehicles (alias for route)
    public function scopeGroup($query)
    {
        return $query->where('status', self::STATUS_ROUTE);
    }

    // Scope for route vehicles (alias for group)
    public function scopeRoute($query)
    {
        return $query->where('status', self::STATUS_GROUP);
    }

    // Scope for vehicles that are not in workshop (for Xe ngoài bãi screen)
    public function scopeNotInactive($query)
    {
        return $query->where('status', '!=', self::STATUS_WORKSHOP);
    }

    // Scope for waiting vehicles (xe sẵn sàng chạy - ready status) - DEPRECATED: use scopeReady()
    public function scopeWaiting($query)
    {
        return $query->where('status', self::STATUS_READY);
    }

    // Scope for ready vehicles (alias for waiting - xe sẵn sàng chạy)
    public function scopeReady($query)
    {
        return $query->where('status', self::STATUS_READY);
    }

    /**
     * Get technical issues for this vehicle
     */
    public function technicalIssues()
    {
        return $this->hasMany(\App\Models\VehicleTechnicalIssue::class);
    }

    /**
     * Get repair issues for this vehicle
     */
    public function repairIssues()
    {
        return $this->hasMany(\App\Models\VehicleTechnicalIssue::class)->where('issue_type', 'repair');
    }

    /**
     * Get maintenance issues for this vehicle
     */
    public function maintenanceIssues()
    {
        return $this->hasMany(\App\Models\VehicleTechnicalIssue::class)->where('issue_type', 'maintenance');
    }

    public function maintenanceSchedules()
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }

    public function maintenanceRecords()
    {
        return $this->hasManyThrough(MaintenanceRecord::class, MaintenanceSchedule::class);
    }
}
