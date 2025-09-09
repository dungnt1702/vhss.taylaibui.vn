<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'maintenance_type_id',
        'scheduled_date',
        'last_performed',
        'next_due',
        'status',
        'notes'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'last_performed' => 'date',
        'next_due' => 'date'
    ];

    // Relationships
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function maintenanceType()
    {
        return $this->belongsTo(MaintenanceType::class);
    }

    public function records()
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    public function latestRecord()
    {
        return $this->hasOne(MaintenanceRecord::class)->latest();
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeDueToday($query)
    {
        return $query->where('scheduled_date', Carbon::today());
    }

    public function scopeDueThisWeek($query)
    {
        return $query->whereBetween('scheduled_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    public function scopeForVehicle($query, $vehicleId)
    {
        return $query->where('vehicle_id', $vehicleId);
    }

    // Methods
    public function isOverdue()
    {
        return $this->scheduled_date < Carbon::today() && $this->status !== 'completed';
    }

    public function markAsOverdue()
    {
        if ($this->isOverdue()) {
            $this->update(['status' => 'overdue']);
        }
    }

    public function calculateNextDue()
    {
        if ($this->last_performed) {
            $this->next_due = Carbon::parse($this->last_performed)->addDays($this->maintenanceType->interval_days);
            $this->save();
        }
    }

    public function getStatusColor()
    {
        return match($this->status) {
            'pending' => 'blue',
            'in_progress' => 'yellow',
            'completed' => 'green',
            'overdue' => 'red',
            'cancelled' => 'gray',
            default => 'gray'
        };
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'Chờ thực hiện',
            'in_progress' => 'Đang thực hiện',
            'completed' => 'Hoàn thành',
            'overdue' => 'Quá hạn',
            'cancelled' => 'Đã hủy',
            default => 'Không xác định'
        };
    }
}