<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RepairCategory;

class RepairCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'key' => 'engine',
                'name' => 'Động cơ',
                'description' => 'Các vấn đề liên quan đến động cơ xe',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'key' => 'brake_system',
                'name' => 'Hệ thống phanh',
                'description' => 'Phanh đĩa, phanh tang trống, dầu phanh',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'key' => 'transmission',
                'name' => 'Hộp số',
                'description' => 'Hộp số tự động, hộp số tay',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'key' => 'electrical',
                'name' => 'Hệ thống điện',
                'description' => 'Ắc quy, hệ thống điện, đèn xe',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'key' => 'suspension',
                'name' => 'Hệ thống treo',
                'description' => 'Giảm xóc, lò xo, thanh ổn định',
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'key' => 'steering',
                'name' => 'Hệ thống lái',
                'description' => 'Vô lăng, trợ lực lái, thanh nối',
                'is_active' => true,
                'sort_order' => 6
            ],
            [
                'key' => 'exhaust',
                'name' => 'Hệ thống xả',
                'description' => 'Ống xả, bộ lọc khí thải',
                'is_active' => true,
                'sort_order' => 7
            ],
            [
                'key' => 'cooling',
                'name' => 'Hệ thống làm mát',
                'description' => 'Nước làm mát, quạt gió, bơm nước',
                'is_active' => true,
                'sort_order' => 8
            ],
            [
                'key' => 'fuel_system',
                'name' => 'Hệ thống nhiên liệu',
                'description' => 'Bơm xăng, bộ lọc nhiên liệu, kim phun',
                'is_active' => true,
                'sort_order' => 9
            ],
            [
                'key' => 'tires',
                'name' => 'Lốp xe',
                'description' => 'Lốp, vành xe, cân bằng lốp',
                'is_active' => true,
                'sort_order' => 10
            ],
            [
                'key' => 'lights',
                'name' => 'Hệ thống đèn',
                'description' => 'Đèn pha, đèn hậu, đèn xi-nhan',
                'is_active' => true,
                'sort_order' => 11
            ],
            [
                'key' => 'air_conditioning',
                'name' => 'Điều hòa',
                'description' => 'Hệ thống điều hòa không khí',
                'is_active' => true,
                'sort_order' => 12
            ],
            [
                'key' => 'other',
                'name' => 'Khác',
                'description' => 'Các vấn đề khác không thuộc danh mục trên',
                'is_active' => true,
                'sort_order' => 99
            ]
        ];

        foreach ($categories as $category) {
            RepairCategory::updateOrCreate(
                ['key' => $category['key']],
                $category
            );
        }
    }
}