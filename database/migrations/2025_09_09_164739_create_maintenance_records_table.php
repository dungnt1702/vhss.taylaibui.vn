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
        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('performed_by')->constrained('users')->onDelete('cascade'); // Người thực hiện
            $table->date('performed_date'); // Ngày thực hiện
            $table->time('start_time')->nullable(); // Giờ bắt đầu
            $table->time('end_time')->nullable(); // Giờ kết thúc
            $table->text('notes')->nullable(); // Ghi chú thực hiện
            $table->json('task_results')->nullable(); // Kết quả các nhiệm vụ
            $table->json('attachments')->nullable(); // Hình ảnh đính kèm
            $table->enum('status', ['completed', 'incomplete', 'cancelled'])->default('completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_records');
    }
};