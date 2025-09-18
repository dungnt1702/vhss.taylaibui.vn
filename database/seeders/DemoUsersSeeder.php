<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy các vai trò
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $operatorRole = Role::where('name', 'operator')->first();
        $viewerRole = Role::where('name', 'viewer')->first();

        // Tạo Super Admin
        User::firstOrCreate(
            ['email' => 'admin@taylaibui.vn'],
            [
                'name' => 'TAY LÁI BỤI',
                'phone' => '0943036579',
                'password' => Hash::make('0943036579'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Tạo 10 users demo
        $demoUsers = [
            [
                'name' => 'Nguyễn Văn Admin',
                'email' => 'admin@demo.com',
                'phone' => '0123456789',
                'role_id' => $adminRole->id,
            ],
            [
                'name' => 'Trần Thị Manager',
                'email' => 'manager1@demo.com',
                'phone' => '0987654321',
                'role_id' => $managerRole->id,
            ],
            [
                'name' => 'Lê Văn Operator',
                'email' => 'operator1@demo.com',
                'phone' => '0369258147',
                'role_id' => $operatorRole->id,
            ],
            [
                'name' => 'Phạm Thị Viewer',
                'email' => 'viewer1@demo.com',
                'phone' => '0741852963',
                'role_id' => $viewerRole->id,
            ],
            [
                'name' => 'Hoàng Văn Manager',
                'email' => 'manager2@demo.com',
                'phone' => '0527419638',
                'role_id' => $managerRole->id,
            ],
            [
                'name' => 'Vũ Thị Operator',
                'email' => 'operator2@demo.com',
                'phone' => '0963852741',
                'role_id' => $operatorRole->id,
            ],
            [
                'name' => 'Đặng Văn Viewer',
                'email' => 'viewer2@demo.com',
                'phone' => '0147258369',
                'role_id' => $viewerRole->id,
            ],
            [
                'name' => 'Bùi Thị Manager',
                'email' => 'manager3@demo.com',
                'phone' => '0852741963',
                'role_id' => $managerRole->id,
            ],
            [
                'name' => 'Phan Văn Operator',
                'email' => 'operator3@demo.com',
                'phone' => '0741963852',
                'role_id' => $operatorRole->id,
            ],
            [
                'name' => 'Ngô Thị Viewer',
                'email' => 'viewer3@demo.com',
                'phone' => '0638520741',
                'role_id' => $viewerRole->id,
            ],
        ];

        foreach ($demoUsers as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'phone' => $userData['phone'],
                    'password' => Hash::make('12345678'),
                    'role_id' => $userData['role_id'],
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('Created 10 demo users successfully!');
        $this->command->info('All users have password: 12345678');
    }
}