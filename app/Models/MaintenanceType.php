<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'interval_days',
        'is_active',
        'color',
        'priority'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'interval_days' => 'integer',
        'priority' => 'integer'
    ];

    // Relationships
    public function tasks()
    {
        return $this->hasMany(MaintenanceTask::class);
    }

    public function schedules()
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'asc');
    }

    // Static methods
    public static function getDailyTypes()
    {
        return self::active()->where('interval_days', 1)->get();
    }

    public static function getWeeklyTypes()
    {
        return self::active()->where('interval_days', 7)->get();
    }

    public static function getMonthlyTypes()
    {
        return self::active()->where('interval_days', 30)->get();
    }

    public static function getQuarterlyTypes()
    {
        return self::active()->where('interval_days', 90)->get();
    }

    public static function getYearlyTypes()
    {
        return self::active()->where('interval_days', 365)->get();
    }
}