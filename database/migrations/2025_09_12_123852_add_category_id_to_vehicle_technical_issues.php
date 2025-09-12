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
        Schema::table('vehicle_technical_issues', function (Blueprint $table) {
            // Add category_id as foreign key with default value
            $table->unsignedBigInteger('category_id')->default(1)->after('vehicle_id');
            $table->foreign('category_id')->references('id')->on('repair_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_technical_issues', function (Blueprint $table) {
            // Drop foreign key and category_id column
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
