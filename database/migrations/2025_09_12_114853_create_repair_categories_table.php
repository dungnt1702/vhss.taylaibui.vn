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
        Schema::create('repair_categories', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // engine, brake_system, etc.
            $table->string('name'); // Tên hiển thị
            $table->text('description')->nullable(); // Mô tả chi tiết
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->integer('sort_order')->default(0); // Thứ tự sắp xếp
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_categories');
    }
};
