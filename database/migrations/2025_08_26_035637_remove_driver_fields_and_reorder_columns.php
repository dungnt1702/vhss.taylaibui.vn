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
        Schema::table('vehicles', function (Blueprint $table) {
            // Drop driver fields
            $table->dropColumn(['driver_name', 'driver_phone']);
            
            // Drop end_time column temporarily to reorder
            $table->dropColumn('end_time');
        });
        
        // Re-add end_time after start_time
        Schema::table('vehicles', function (Blueprint $table) {
            $table->timestamp('end_time')->nullable()->after('start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Re-add driver fields
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            
            // Reorder columns back
            $table->dropColumn('end_time');
            $table->timestamp('end_time')->nullable()->after('paused_at');
        });
    }
};
