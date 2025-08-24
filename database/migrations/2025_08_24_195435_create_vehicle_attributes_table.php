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
        Schema::create('vehicle_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // color, seats, power, wheel_size
            $table->string('value'); // Giá trị của thuộc tính
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
        Schema::dropIfExists('vehicle_attributes');
    }
};
