<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks temporarily
        \DB::statement('PRAGMA foreign_keys=OFF');
        
        // Map old category keys to new category IDs
        $categoryMapping = [
            'engine' => 1,
            'brake_system' => 2,
            'transmission' => 3,
            'electrical' => 4,
            'suspension' => 5,
            'steering' => 6,
            'exhaust' => 7,
            'cooling' => 8,
            'fuel_system' => 9,
            'tires' => 10,
            'lights' => 11,
            'air_conditioning' => 12,
            'other' => 13
        ];
        
        // Update existing records
        foreach ($categoryMapping as $oldKey => $newId) {
            \DB::table('vehicle_technical_issues')
                ->where('category', $oldKey)
                ->update(['category_id' => $newId]);
        }
        
        // Update any remaining records with unknown categories to 'other'
        \DB::table('vehicle_technical_issues')
            ->whereNull('category_id')
            ->orWhere('category_id', 1) // default value
            ->update(['category_id' => 13]); // 'other' category
        
        // Re-enable foreign key checks
        \DB::statement('PRAGMA foreign_keys=ON');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset category_id to default value
        \DB::table('vehicle_technical_issues')
            ->update(['category_id' => 1]);
    }
};
