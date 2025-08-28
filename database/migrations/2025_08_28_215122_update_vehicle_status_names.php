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
        // Cập nhật trạng thái xe từ 'active' thành 'ready'
        DB::table('vehicles')
            ->where('status', 'active')
            ->update(['status' => 'ready']);

        // Cập nhật trạng thái xe từ 'inactive' thành 'workshop'
        DB::table('vehicles')
            ->where('status', 'inactive')
            ->update(['status' => 'workshop']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Khôi phục trạng thái xe từ 'ready' thành 'active'
        DB::table('vehicles')
            ->where('status', 'ready')
            ->update(['status' => 'active']);

        // Khôi phục trạng thái xe từ 'workshop' thành 'inactive'
        DB::table('vehicles')
            ->where('status', 'workshop')
            ->update(['status' => 'inactive']);
    }
};
