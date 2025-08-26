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
        $colors = static::getActiveByType(self::TYPE_COLOR);
        
        // If no colors in database, return default hex color palette
        if (empty($colors)) {
            return [
                '#FF0000' => 'Đỏ',
                '#FF4500' => 'Cam đỏ',
                '#FF8C00' => 'Cam',
                '#FFD700' => 'Vàng',
                '#32CD32' => 'Xanh lá',
                '#00CED1' => 'Xanh dương',
                '#4169E1' => 'Xanh hoàng gia',
                '#8A2BE2' => 'Xanh tím',
                '#FF69B4' => 'Hồng',
                '#FF1493' => 'Hồng đậm',
                '#FF6347' => 'Cà chua',
                '#20B2AA' => 'Xanh biển nhạt',
                '#228B22' => 'Xanh rừng',
                '#DC143C' => 'Đỏ đậm',
                '#000000' => 'Đen',
                '#FFFFFF' => 'Trắng',
                '#808080' => 'Xám',
                '#C0C0C0' => 'Bạc',
                '#D2691E' => 'Nâu',
                '#4B0082' => 'Tím',
                '#FF00FF' => 'Magenta'
            ];
        }
        
        return $colors;
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
