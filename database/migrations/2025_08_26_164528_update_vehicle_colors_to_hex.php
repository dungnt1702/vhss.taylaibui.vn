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
        // First, update existing color values to hex codes
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

        // Update existing records
        foreach ($colorMapping as $oldColor => $newColor) {
            DB::table('vehicles')
                ->where('color', $oldColor)
                ->update(['color' => $newColor]);
        }

        // Change column type to accommodate hex codes
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('color', 7)->change(); // Hex codes are 7 characters (#RRGGBB)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert hex codes back to color names
        $colorMapping = [
            '#4169E1' => 'Xanh biển',
            '#32CD32' => 'Xanh cây',
            '#FF8C00' => 'Cam',
            '#FF0000' => 'Đỏ',
            '#FFD700' => 'Vàng',
            '#000000' => 'Đen',
            '#FFFFFF' => 'Trắng',
            '#808080' => 'Xám',
            '#C0C0C0' => 'Bạc',
            '#D2691E' => 'Nâu',
            '#8A2BE2' => 'Tím',
            '#FF69B4' => 'Hồng',
            '#00CED1' => 'Xanh dương',
            '#FF1493' => 'Hồng đậm',
            '#FF6347' => 'Cà chua',
            '#20B2AA' => 'Xanh biển nhạt',
            '#228B22' => 'Xanh rừng',
            '#FF4500' => 'Cam đỏ',
            '#DC143C' => 'Đỏ đậm',
            '#FF00FF' => 'Magenta'
        ];

        // Update existing records back to color names
        foreach ($colorMapping as $hexColor => $colorName) {
            DB::table('vehicles')
                ->where('color', $hexColor)
                ->update(['color' => $colorName]);
        }

        // Change column type back to original
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('color')->change();
        });
    }
};
