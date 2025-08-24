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
        'notes',
        'current_location',
        'driver_name',
        'driver_phone',
        'last_maintenance',
        'next_maintenance',
        'route_number',
        'status_changed_at',
    ];

    protected $casts = [
        'last_maintenance' => 'datetime',
        'next_maintenance' => 'datetime',
        'status_changed_at' => 'datetime',
        'route_number' => 'integer',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';      // Xe ngoài bãi
    const STATUS_INACTIVE = 'inactive';  // Xe trong xưởng
    const STATUS_RUNNING = 'running';    // Xe đang chạy
    const STATUS_WAITING = 'waiting';    // Xe đang chờ
    const STATUS_EXPIRED = 'expired';    // Xe hết giờ
    const STATUS_PAUSED = 'paused';     // Xe tạm dừng
    const STATUS_ROUTE = 'route';        // Xe cung đường
    const STATUS_GROUP = 'group';        // Xe khách đoàn

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
        return match($this->status) {
            self::STATUS_ACTIVE => 'Xe ngoài bãi',
            self::STATUS_INACTIVE => 'Xe trong xưởng',
            self::STATUS_RUNNING => 'Xe đang chạy',
            self::STATUS_WAITING => 'Xe đang chờ',
            self::STATUS_EXPIRED => 'Xe hết giờ',
            self::STATUS_PAUSED => 'Xe tạm dừng',
            self::STATUS_ROUTE => 'Xe cung đường',
            self::STATUS_GROUP => 'Xe khách đoàn',
            default => 'Không xác định'
        };
    }

    // Get status color class
    public function getStatusColorClassAttribute()
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'bg-green-100 text-green-800',
            self::STATUS_INACTIVE => 'bg-red-100 text-red-800',
            self::STATUS_RUNNING => 'bg-blue-100 text-blue-800',
            self::STATUS_WAITING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_EXPIRED => 'bg-orange-100 text-orange-800',
            self::STATUS_PAUSED => 'bg-gray-100 text-gray-800',
            self::STATUS_ROUTE => 'bg-purple-100 text-purple-800',
            self::STATUS_GROUP => 'bg-indigo-100 text-indigo-800',
            default => 'bg-gray-100 text-gray-800'
        };
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

    // Scope for active vehicles
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    // Scope for inactive vehicles
    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    // Scope for running vehicles
    public function scopeRunning($query)
    {
        return $query->where('status', self::STATUS_RUNNING);
    }

    // Scope for waiting vehicles
    public function scopeWaiting($query)
    {
        return $query->where('status', self::STATUS_WAITING);
    }

    // Scope for expired vehicles
    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    // Scope for paused vehicles
    public function scopePaused($query)
    {
        return $query->where('status', self::STATUS_PAUSED);
    }

    // Scope for route vehicles
    public function scopeRoute($query)
    {
        return $query->where('status', self::STATUS_ROUTE);
    }

    // Scope for group vehicles
    public function scopeGroup($query)
    {
        return $query->where('status', self::STATUS_GROUP);
    }

    // Scope for list view (grid display)
    public function scopeListDisplay($query)
    {
        return $query->whereIn('status', [
            self::STATUS_ACTIVE,
            self::STATUS_INACTIVE,
            self::STATUS_RUNNING,
            self::STATUS_WAITING,
            self::STATUS_EXPIRED,
            self::STATUS_PAUSED
        ]);
    }

    // Scope for grid view (list display)
    public function scopeGridDisplay($query)
    {
        return $query->whereIn('status', [
            self::STATUS_ROUTE,
            self::STATUS_GROUP
        ]);
    }
}
