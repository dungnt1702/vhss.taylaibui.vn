<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'value',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Attribute types
    const TYPE_COLOR = 'color';
    const TYPE_SEATS = 'seats';
    const TYPE_POWER = 'power';
    const TYPE_WHEEL_SIZE = 'wheel_size';

    // Get all active attributes by type
    public static function getActiveByType($type)
    {
        return static::where('type', $type)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->pluck('value')
                    ->toArray();
    }

    // Get all colors
    public static function getColors()
    {
        return static::getActiveByType(self::TYPE_COLOR);
    }

    // Get all seat options
    public static function getSeats()
    {
        return static::getActiveByType(self::TYPE_SEATS);
    }

    // Get all power options
    public static function getPowerOptions()
    {
        return static::getActiveByType(self::TYPE_POWER);
    }

    // Get all wheel sizes
    public static function getWheelSizes()
    {
        return static::getActiveByType(self::TYPE_WHEEL_SIZE);
    }

    // Check if attribute can be managed by user
    public function canBeManagedBy($user)
    {
        return $user && $user->role === 'admin';
    }
}
