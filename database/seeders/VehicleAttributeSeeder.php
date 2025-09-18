<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VehicleAttribute;

class VehicleAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Colors
        $colors = ['Xanh biển', 'Xanh cây', 'Cam', 'Đỏ', 'Vàng', 'Đen'];
        foreach ($colors as $index => $color) {
            VehicleAttribute::firstOrCreate(
                ['type' => 'color', 'value' => $color],
                [
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }

        // Seats
        $seats = ['1', '2'];
        foreach ($seats as $index => $seat) {
            VehicleAttribute::firstOrCreate(
                ['type' => 'seats', 'value' => $seat],
                [
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }

        // Power options
        $powerOptions = ['48V-1000W', '60V-1200W'];
        foreach ($powerOptions as $index => $power) {
            VehicleAttribute::firstOrCreate(
                ['type' => 'power', 'value' => $power],
                [
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }

        // Wheel sizes
        $wheelSizes = ['7inch', '8inch'];
        foreach ($wheelSizes as $index => $wheelSize) {
            VehicleAttribute::firstOrCreate(
                ['type' => 'wheel_size', 'value' => $wheelSize],
                [
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
