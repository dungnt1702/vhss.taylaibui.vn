<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_schedule_id',
        'performed_by',
        'performed_date',
        'start_time',
        'end_time',
        'notes',
        'task_results',
        'attachments',
        'status'
    ];

    protected $casts = [
        'performed_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'task_results' => 'array',
        'attachments' => 'array'
    ];

    // Relationships
    public function maintenanceSchedule()
    {
        return $this->belongsTo(MaintenanceSchedule::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeIncomplete($query)
    {
        return $query->where('status', 'incomplete');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('performed_date', [$startDate, $endDate]);
    }

    // Methods
    public function getDuration()
    {
        if ($this->start_time && $this->end_time) {
            return $this->start_time->diffInMinutes($this->end_time);
        }
        return null;
    }

    public function getFormattedDuration()
    {
        $duration = $this->getDuration();
        if ($duration) {
            $hours = floor($duration / 60);
            $minutes = $duration % 60;
            return $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
        }
        return 'N/A';
    }

    public function getStatusColor()
    {
        return match($this->status) {
            'completed' => 'green',
            'incomplete' => 'yellow',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'completed' => 'Hoàn thành',
            'incomplete' => 'Chưa hoàn thành',
            'cancelled' => 'Đã hủy',
            default => 'Không xác định'
        };
    }
}