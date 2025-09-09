<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Quản trị viên',
            'description' => 'Có toàn quyền truy cập hệ thống',
            'is_active' => true,
        ]);

        $managerRole = Role::create([
            'name' => 'manager',
            'display_name' => 'Quản lý',
            'description' => 'Quản lý xe và người dùng',
            'is_active' => true,
        ]);

        $operatorRole = Role::create([
            'name' => 'operator',
            'display_name' => 'Vận hành',
            'description' => 'Vận hành xe và xem báo cáo',
            'is_active' => true,
        ]);

        $viewerRole = Role::create([
            'name' => 'viewer',
            'display_name' => 'Xem',
            'description' => 'Chỉ xem thông tin',
            'is_active' => true,
        ]);

        // Create permissions
        $permissions = [
            // Vehicle permissions
            ['name' => 'vehicles.view', 'display_name' => 'Xem xe', 'module' => 'vehicles', 'action' => 'view'],
            ['name' => 'vehicles.create', 'display_name' => 'Tạo xe', 'module' => 'vehicles', 'action' => 'create'],
            ['name' => 'vehicles.edit', 'display_name' => 'Sửa xe', 'module' => 'vehicles', 'action' => 'edit'],
            ['name' => 'vehicles.delete', 'display_name' => 'Xóa xe', 'module' => 'vehicles', 'action' => 'delete'],
            ['name' => 'vehicles.manage', 'display_name' => 'Quản lý xe', 'module' => 'vehicles', 'action' => 'manage'],
            
            // User permissions
            ['name' => 'users.view', 'display_name' => 'Xem người dùng', 'module' => 'users', 'action' => 'view'],
            ['name' => 'users.create', 'display_name' => 'Tạo người dùng', 'module' => 'users', 'action' => 'create'],
            ['name' => 'users.edit', 'display_name' => 'Sửa người dùng', 'module' => 'users', 'action' => 'edit'],
            ['name' => 'users.delete', 'display_name' => 'Xóa người dùng', 'module' => 'users', 'action' => 'delete'],
            ['name' => 'users.manage', 'display_name' => 'Quản lý người dùng', 'module' => 'users', 'action' => 'manage'],
            
            // Role permissions
            ['name' => 'roles.view', 'display_name' => 'Xem vai trò', 'module' => 'roles', 'action' => 'view'],
            ['name' => 'roles.create', 'display_name' => 'Tạo vai trò', 'module' => 'roles', 'action' => 'create'],
            ['name' => 'roles.edit', 'display_name' => 'Sửa vai trò', 'module' => 'roles', 'action' => 'edit'],
            ['name' => 'roles.delete', 'display_name' => 'Xóa vai trò', 'module' => 'roles', 'action' => 'delete'],
            ['name' => 'roles.manage', 'display_name' => 'Quản lý vai trò', 'module' => 'roles', 'action' => 'manage'],
            
            // Report permissions
            ['name' => 'reports.view', 'display_name' => 'Xem báo cáo', 'module' => 'reports', 'action' => 'view'],
            ['name' => 'reports.export', 'display_name' => 'Xuất báo cáo', 'module' => 'reports', 'action' => 'export'],
            
            // System permissions
            ['name' => 'system.settings', 'display_name' => 'Cài đặt hệ thống', 'module' => 'system', 'action' => 'settings'],
            ['name' => 'system.logs', 'display_name' => 'Xem nhật ký', 'module' => 'system', 'action' => 'logs'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::create(array_merge($permissionData, ['is_active' => true]));
        }

        // Assign permissions to roles
        $allPermissions = Permission::all();
        $adminRole->syncPermissions($allPermissions->pluck('id')->toArray());

        $managerPermissions = Permission::whereIn('name', [
            'vehicles.view', 'vehicles.create', 'vehicles.edit', 'vehicles.manage',
            'users.view', 'users.create', 'users.edit',
            'reports.view', 'reports.export'
        ])->get();
        $managerRole->syncPermissions($managerPermissions->pluck('id')->toArray());

        $operatorPermissions = Permission::whereIn('name', [
            'vehicles.view', 'vehicles.edit',
            'reports.view'
        ])->get();
        $operatorRole->syncPermissions($operatorPermissions->pluck('id')->toArray());

        $viewerPermissions = Permission::whereIn('name', [
            'vehicles.view',
            'reports.view'
        ])->get();
        $viewerRole->syncPermissions($viewerPermissions->pluck('id')->toArray());
    }
}
