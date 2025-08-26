<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clear all existing vehicles
        DB::table('vehicles')->delete();
        
        // Reset auto-increment for SQLite
        DB::statement('DELETE FROM sqlite_sequence WHERE name = "vehicles"');
        
        // Create 22 vehicles as specified
        $vehicles = [];
        
        // Vehicles 1-21: 1 chỗ, bánh 7inch, 48V-1000W
        for ($i = 1; $i <= 21; $i++) {
            $vehicles[] = [
                'name' => $i,
                'color' => 'Xanh biển',
                'seats' => '1',
                'power' => '48V-1000W',
                'wheel_size' => '7inch',
                'status' => 'active',
                'start_time' => null,
                'end_time' => null,
                'paused_at' => null,
                'paused_remaining_seconds' => null,
                'notes' => null,
                'current_location' => 'Bãi xe',
                'last_maintenance' => null,
                'next_maintenance' => null,
                'route_number' => null,
                'status_changed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Vehicle 22: 2 chỗ, bánh 8inch, 60V-1200W (special vehicle)
        $vehicles[] = [
            'name' => 22,
            'color' => 'Xanh biển',
            'seats' => '2',
            'power' => '60V-1200W',
            'wheel_size' => '8inch',
            'status' => 'active',
            'start_time' => null,
            'end_time' => null,
            'paused_at' => null,
            'paused_remaining_seconds' => null,
            'notes' => 'Xe đặc biệt - 22 chỗ ngồi',
            'current_location' => 'Bãi xe',
            'last_maintenance' => null,
            'next_maintenance' => null,
            'route_number' => null,
            'status_changed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        // Insert all vehicles
        DB::table('vehicles')->insert($vehicles);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be reversed as it would lose data
        // If you need to reverse, you would need to restore from backup
    }
};
