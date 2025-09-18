<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MaintenanceType;
use App\Models\MaintenanceTask;

class MaintenanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bảo trì hàng ngày
        $daily = MaintenanceType::firstOrCreate(
            ['name' => 'Bảo trì hàng ngày'],
            [
                'description' => 'Kiểm tra và bảo trì cơ bản hàng ngày',
                'interval_days' => 1,
                'color' => '#10B981',
                'priority' => 1
            ]
        );

        // Tasks cho bảo trì hàng ngày
        $dailyTasks = [
            ['task_name' => 'Kiểm tra dầu động cơ', 'description' => 'Kiểm tra mức dầu và chất lượng dầu', 'is_required' => true, 'sort_order' => 1],
            ['task_name' => 'Kiểm tra áp suất lốp', 'description' => 'Kiểm tra áp suất tất cả các lốp', 'is_required' => true, 'sort_order' => 2],
            ['task_name' => 'Kiểm tra đèn, còi', 'description' => 'Kiểm tra hệ thống chiếu sáng và còi', 'is_required' => true, 'sort_order' => 3],
            ['task_name' => 'Vệ sinh nội thất', 'description' => 'Lau chùi và vệ sinh bên trong xe', 'is_required' => false, 'sort_order' => 4],
            ['task_name' => 'Kiểm tra nhiên liệu', 'description' => 'Kiểm tra mức nhiên liệu', 'is_required' => true, 'sort_order' => 5],
        ];

        foreach ($dailyTasks as $task) {
            $daily->tasks()->create($task);
        }

        // Bảo trì hàng tuần
        $weekly = MaintenanceType::create([
            'name' => 'Bảo trì hàng tuần',
            'description' => 'Kiểm tra và bảo trì định kỳ hàng tuần',
            'interval_days' => 7,
            'color' => '#3B82F6',
            'priority' => 2
        ]);

        $weeklyTasks = [
            ['task_name' => 'Kiểm tra hệ thống phanh', 'description' => 'Kiểm tra phanh tay, phanh chân và dầu phanh', 'is_required' => true, 'sort_order' => 1],
            ['task_name' => 'Kiểm tra hệ thống lái', 'description' => 'Kiểm tra vô lăng và hệ thống lái', 'is_required' => true, 'sort_order' => 2],
            ['task_name' => 'Kiểm tra hệ thống điện', 'description' => 'Kiểm tra ắc quy và hệ thống điện', 'is_required' => true, 'sort_order' => 3],
            ['task_name' => 'Vệ sinh ngoại thất', 'description' => 'Rửa xe và vệ sinh bên ngoài', 'is_required' => false, 'sort_order' => 4],
            ['task_name' => 'Kiểm tra gương chiếu hậu', 'description' => 'Kiểm tra và điều chỉnh gương', 'is_required' => true, 'sort_order' => 5],
        ];

        foreach ($weeklyTasks as $task) {
            $weekly->tasks()->create($task);
        }

        // Bảo trì hàng tháng
        $monthly = MaintenanceType::create([
            'name' => 'Bảo trì hàng tháng',
            'description' => 'Bảo dưỡng định kỳ hàng tháng',
            'interval_days' => 30,
            'color' => '#F59E0B',
            'priority' => 3
        ]);

        $monthlyTasks = [
            ['task_name' => 'Thay dầu động cơ', 'description' => 'Thay dầu động cơ và lọc dầu', 'is_required' => true, 'sort_order' => 1],
            ['task_name' => 'Kiểm tra hệ thống làm mát', 'description' => 'Kiểm tra nước làm mát và quạt gió', 'is_required' => true, 'sort_order' => 2],
            ['task_name' => 'Kiểm tra ắc quy', 'description' => 'Kiểm tra và sạc ắc quy', 'is_required' => true, 'sort_order' => 3],
            ['task_name' => 'Kiểm tra hệ thống xả', 'description' => 'Kiểm tra ống xả và bộ lọc khí', 'is_required' => true, 'sort_order' => 4],
            ['task_name' => 'Kiểm tra lốp xe', 'description' => 'Kiểm tra độ mòn và cân bằng lốp', 'is_required' => true, 'sort_order' => 5],
        ];

        foreach ($monthlyTasks as $task) {
            $monthly->tasks()->create($task);
        }

        // Bảo trì hàng quý
        $quarterly = MaintenanceType::create([
            'name' => 'Bảo trì hàng quý',
            'description' => 'Bảo dưỡng tổng thể hàng quý',
            'interval_days' => 90,
            'color' => '#8B5CF6',
            'priority' => 4
        ]);

        $quarterlyTasks = [
            ['task_name' => 'Bảo dưỡng tổng thể', 'description' => 'Kiểm tra toàn bộ hệ thống xe', 'is_required' => true, 'sort_order' => 1],
            ['task_name' => 'Kiểm tra hệ thống điều hòa', 'description' => 'Kiểm tra và bảo dưỡng điều hòa', 'is_required' => true, 'sort_order' => 2],
            ['task_name' => 'Kiểm tra hệ thống an toàn', 'description' => 'Kiểm tra dây an toàn, túi khí', 'is_required' => true, 'sort_order' => 3],
            ['task_name' => 'Kiểm tra phụ tùng', 'description' => 'Kiểm tra và thay thế phụ tùng cần thiết', 'is_required' => true, 'sort_order' => 4],
            ['task_name' => 'Kiểm tra hệ thống treo', 'description' => 'Kiểm tra giảm xóc và hệ thống treo', 'is_required' => true, 'sort_order' => 5],
        ];

        foreach ($quarterlyTasks as $task) {
            $quarterly->tasks()->create($task);
        }

        // Bảo trì hàng năm
        $yearly = MaintenanceType::create([
            'name' => 'Bảo trì hàng năm',
            'description' => 'Đại tu và bảo hiểm kỹ thuật hàng năm',
            'interval_days' => 365,
            'color' => '#EF4444',
            'priority' => 5
        ]);

        $yearlyTasks = [
            ['task_name' => 'Đại tu động cơ', 'description' => 'Kiểm tra và bảo dưỡng toàn bộ động cơ', 'is_required' => true, 'sort_order' => 1],
            ['task_name' => 'Thay thế phụ tùng chính', 'description' => 'Thay thế các phụ tùng quan trọng', 'is_required' => true, 'sort_order' => 2],
            ['task_name' => 'Kiểm tra toàn bộ hệ thống', 'description' => 'Kiểm tra tổng thể tất cả hệ thống', 'is_required' => true, 'sort_order' => 3],
            ['task_name' => 'Bảo hiểm kỹ thuật', 'description' => 'Thực hiện bảo hiểm kỹ thuật', 'is_required' => true, 'sort_order' => 4],
            ['task_name' => 'Kiểm tra khung xe', 'description' => 'Kiểm tra độ bền và an toàn khung xe', 'is_required' => true, 'sort_order' => 5],
        ];

        foreach ($yearlyTasks as $task) {
            $yearly->tasks()->create($task);
        }
    }
}