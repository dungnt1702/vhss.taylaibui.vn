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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên xe (số thứ tự 1-22)
            $table->string('color'); // Màu xe
            $table->enum('seats', ['1', '2']); // Số chỗ ngồi
            $table->enum('power', ['48V-1000W', '60V-1200W']); // Công suất
            $table->enum('wheel_size', ['7inch', '8inch']); // Kích cỡ bánh
            $table->string('status')->default('active'); // Trạng thái: active, inactive, running, waiting, expired, paused, route, group (group = xe ngoài bãi)
            $table->text('notes')->nullable(); // Ghi chú
            $table->string('current_location')->nullable(); // Vị trí hiện tại
            $table->string('driver_name')->nullable(); // Tên tài xế
            $table->string('driver_phone')->nullable(); // SĐT tài xế
            $table->timestamp('last_maintenance')->nullable(); // Lần bảo dưỡng cuối
            $table->timestamp('next_maintenance')->nullable(); // Lần bảo dưỡng tiếp theo
            $table->integer('route_number')->nullable(); // Số cung đường
            $table->timestamp('status_changed_at')->nullable(); // Thời gian thay đổi trạng thái
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
