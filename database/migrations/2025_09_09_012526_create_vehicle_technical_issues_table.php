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
        Schema::create('vehicle_technical_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->enum('issue_type', ['repair', 'maintenance'])->comment('repair: sửa chữa, maintenance: bảo trì');
            $table->string('category', 100)->comment('Hạng mục sửa chữa/bảo trì');
            $table->text('description')->nullable()->comment('Mô tả chi tiết');
            $table->text('notes')->nullable()->comment('Ghi chú thêm');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('reported_at')->useCurrent()->comment('Thời gian báo cáo');
            $table->timestamp('completed_at')->nullable()->comment('Thời gian hoàn thành');
            $table->foreignId('reported_by')->nullable()->constrained('users')->onDelete('set null')->comment('Người báo cáo');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null')->comment('Người được giao');
            $table->timestamps();
            
            $table->index(['vehicle_id', 'issue_type']);
            $table->index(['status', 'issue_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_technical_issues');
    }
};
