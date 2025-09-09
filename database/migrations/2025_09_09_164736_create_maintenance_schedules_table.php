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
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('maintenance_type_id')->constrained()->onDelete('cascade');
            $table->date('scheduled_date'); // Ngày dự kiến bảo trì
            $table->date('last_performed')->nullable(); // Ngày thực hiện lần cuối
            $table->date('next_due'); // Ngày đến hạn tiếp theo
            $table->enum('status', ['pending', 'in_progress', 'completed', 'overdue', 'cancelled'])->default('pending');
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();
            
            $table->index(['vehicle_id', 'scheduled_date']);
            $table->index(['status', 'scheduled_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};