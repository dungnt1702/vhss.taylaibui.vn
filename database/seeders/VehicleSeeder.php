<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = ['Xanh biển', 'Xanh cây', 'Cam', 'Đỏ', 'Vàng', 'Đen'];
        $seatOptions = ['1', '2'];
        $powerOptions = ['48V-1000W', '60V-1200W'];
        $wheelSizes = ['7inch', '8inch'];
        
        // Create 22 Gokart vehicles
        for ($i = 1; $i <= 22; $i++) {
            $color = $colors[array_rand($colors)];
            $seat = $seatOptions[array_rand($seatOptions)];
            $power = $powerOptions[array_rand($powerOptions)];
            $wheelSize = $wheelSizes[array_rand($wheelSizes)];
            
            // Distribute vehicles across different statuses
            $statuses = [
                Vehicle::STATUS_READY,
                Vehicle::STATUS_WORKSHOP,
                Vehicle::STATUS_RUNNING,
                Vehicle::STATUS_WAITING,
                Vehicle::STATUS_EXPIRED,
                Vehicle::STATUS_PAUSED,
                Vehicle::STATUS_ROUTE,
                Vehicle::STATUS_GROUP
            ];
            
            $status = $statuses[array_rand($statuses)];
            
            $vehicle = Vehicle::firstOrCreate(
                ['name' => (string)$i],
                [
                    'color' => $color,
                    'seats' => $seat,
                    'power' => $power,
                    'wheel_size' => $wheelSize,
                    'status' => $status,
                    'current_location' => $this->getLocationByStatus($status),
                    'notes' => $this->getNotesByStatus($status, $i),
                    'route_number' => $status === Vehicle::STATUS_ROUTE ? rand(1, 10) : null,
                    'status_changed_at' => now(),
                ]
            );
        }
    }
    
    private function getLocationByStatus($status)
    {
        return match($status) {
            Vehicle::STATUS_READY => 'Bãi xe chính',
            Vehicle::STATUS_WORKSHOP => 'Xưởng sửa chữa',
            Vehicle::STATUS_RUNNING => 'Đang chạy tuyến',
            Vehicle::STATUS_WAITING => 'Bến xe chờ',
            Vehicle::STATUS_EXPIRED => 'Bãi xe phụ',
            Vehicle::STATUS_PAUSED => 'Bãi xe chính',
            Vehicle::STATUS_ROUTE => 'Tuyến cố định',
            Vehicle::STATUS_GROUP => 'Khu vực xe ngoài bãi',
            default => 'Bãi xe chính'
        };
    }
    
    private function getNotesByStatus($status, $vehicleNumber)
    {
        return match($status) {
            Vehicle::STATUS_READY => "Xe Gokart số {$vehicleNumber} đang hoạt động tốt",
            Vehicle::STATUS_WORKSHOP => "Xe Gokart số {$vehicleNumber} đang bảo dưỡng",
            Vehicle::STATUS_RUNNING => "Xe Gokart số {$vehicleNumber} đang chở khách",
            Vehicle::STATUS_WAITING => "Xe Gokart số {$vehicleNumber} đang chờ khách",
            Vehicle::STATUS_EXPIRED => "Xe Gokart số {$vehicleNumber} cần bảo dưỡng gấp",
            Vehicle::STATUS_PAUSED => "Xe Gokart số {$vehicleNumber} tạm dừng hoạt động",
            Vehicle::STATUS_ROUTE => "Xe Gokart số {$vehicleNumber} chạy tuyến cố định",
            Vehicle::STATUS_GROUP => "Xe Gokart số {$vehicleNumber} phục vụ xe ngoài bãi",
            default => "Xe Gokart số {$vehicleNumber}"
        };
    }
}
