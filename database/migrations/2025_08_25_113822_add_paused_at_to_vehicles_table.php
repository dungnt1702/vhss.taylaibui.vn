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
            $table->timestamp('paused_at')->nullable()->after('end_time'); // Thời điểm xe bị tạm dừng
            $table->integer('paused_remaining_seconds')->nullable()->after('paused_at'); // Số giây còn lại khi tạm dừng
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['paused_at', 'paused_remaining_seconds']);
        });
    }
};
