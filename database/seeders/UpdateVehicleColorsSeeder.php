<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateVehicleColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Color mapping from Vietnamese names to hex codes
        $colorMapping = [
            'Xanh biển' => '#4169E1',
            'Xanh cây' => '#32CD32',
            'Cam' => '#FF8C00',
            'Đỏ' => '#FF0000',
            'Vàng' => '#FFD700',
            'Đen' => '#000000',
            'Trắng' => '#FFFFFF',
            'Xám' => '#808080',
            'Bạc' => '#C0C0C0',
            'Nâu' => '#D2691E',
            'Tím' => '#8A2BE2',
            'Hồng' => '#FF69B4',
            'Xanh lá' => '#32CD32',
            'Xanh dương' => '#00CED1',
            'Xanh hoàng gia' => '#4169E1',
            'Xanh tím' => '#8A2BE2',
            'Hồng đậm' => '#FF1493',
            'Cà chua' => '#FF6347',
            'Xanh biển nhạt' => '#20B2AA',
            'Xanh rừng' => '#228B22',
            'Cam đỏ' => '#FF4500',
            'Đỏ đậm' => '#DC143C',
            'Magenta' => '#FF00FF'
        ];

        // Update existing vehicle colors
        foreach ($colorMapping as $oldColor => $newColor) {
            $updatedCount = DB::table('vehicles')
                ->where('color', $oldColor)
                ->update(['color' => $newColor]);
            
            if ($updatedCount > 0) {
                $this->command->info("Updated {$updatedCount} vehicles from color '{$oldColor}' to '{$newColor}'");
            }
        }

        // Also update vehicle attributes table if colors exist there
        foreach ($colorMapping as $oldColor => $newColor) {
            $updatedCount = DB::table('vehicle_attributes')
                ->where('type', 'color')
                ->where('value', $oldColor)
                ->update(['value' => $newColor]);
            
            if ($updatedCount > 0) {
                $this->command->info("Updated {$updatedCount} vehicle attributes from color '{$oldColor}' to '{$newColor}'");
            }
        }

        $this->command->info('Vehicle colors update completed!');
    }
}
