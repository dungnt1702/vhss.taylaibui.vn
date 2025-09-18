<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chạy các seeders theo thứ tự
        $this->call([
            RolePermissionSeeder::class,      // Tạo roles và permissions trước
            DemoUsersSeeder::class,           // Tạo users demo
            VehicleAttributeSeeder::class,    // Tạo vehicle attributes
            VehicleSeeder::class,             // Tạo vehicles demo
            MaintenanceTypeSeeder::class,     // Tạo maintenance types
            RepairCategorySeeder::class,      // Tạo repair categories
        ]);
    }
}
