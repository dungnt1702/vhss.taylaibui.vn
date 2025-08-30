<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Log;

class UpdateExpiredVehicles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicles:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật status của những xe đã hết giờ từ running sang expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Tìm những xe có status running nhưng đã hết giờ
            $expiredVehicles = Vehicle::where('status', Vehicle::STATUS_RUNNING)
                                    ->where('end_time', '<=', now())
                                    ->get();

            if ($expiredVehicles->isEmpty()) {
                $this->info('Không có xe nào cần cập nhật status expired.');
                return 0;
            }

            $count = 0;
            foreach ($expiredVehicles as $vehicle) {
                $vehicle->update([
                    'status' => Vehicle::STATUS_EXPIRED,
                    'status_changed_at' => now()
                ]);
                $count++;
                
                $this->line("Đã cập nhật xe {$vehicle->name} sang status expired");
            }

            $this->info("Đã cập nhật thành công {$count} xe sang status expired.");
            Log::info("Updated {$count} vehicles to expired status");
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Có lỗi xảy ra: ' . $e->getMessage());
            Log::error('Error updating expired vehicles: ' . $e->getMessage());
            return 1;
        }
    }
}
