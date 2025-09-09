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
        Schema::create('maintenance_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên loại bảo trì
            $table->text('description')->nullable(); // Mô tả
            $table->integer('interval_days'); // Chu kỳ bảo trì (ngày)
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->string('color')->default('#3B82F6'); // Màu sắc hiển thị
            $table->integer('priority')->default(1); // Độ ưu tiên (1-5)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_types');
    }
};