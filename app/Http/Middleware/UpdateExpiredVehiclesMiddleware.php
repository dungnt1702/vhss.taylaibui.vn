<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Vehicle;

class UpdateExpiredVehiclesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Tự động cập nhật expired vehicles trước khi xử lý request
        try {
            $expiredCount = Vehicle::where('status', Vehicle::STATUS_RUNNING)
                                 ->where('end_time', '<=', now())
                                 ->update([
                                     'status' => Vehicle::STATUS_EXPIRED,
                                     'status_changed_at' => now()
                                 ]);
            
            if ($expiredCount > 0) {
                \Log::info("Auto-updated {$expiredCount} vehicles to expired status via middleware");
            }
        } catch (\Exception $e) {
            \Log::error('Error in UpdateExpiredVehiclesMiddleware: ' . $e->getMessage());
        }

        return $next($request);
    }
}
