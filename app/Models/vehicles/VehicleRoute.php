<?php

namespace App\Models\vehicles;

use Illuminate\Database\Eloquent\Model;

class VehicleRoute extends Model
{
    // Default routes
    const DEFAULT_ROUTES = [
        1 => 'Cung đường 1',
        2 => 'Cung đường 2',
        3 => 'Cung đường 3',
        4 => 'Cung đường 4',
        5 => 'Cung đường 5'
    ];

    /**
     * Get route name by number
     */
    public static function getRouteName(int $routeNumber): string
    {
        return self::DEFAULT_ROUTES[$routeNumber] ?? "Cung đường {$routeNumber}";
    }

    /**
     * Get all available routes
     */
    public static function getAllRoutes(): array
    {
        return self::DEFAULT_ROUTES;
    }

    /**
     * Get vehicles by route number
     */
    public static function getVehiclesByRoute(int $routeNumber)
    {
        return \App\Models\Vehicle::where('route_number', $routeNumber)
            ->where('status', \App\Models\Vehicle::STATUS_ROUTE)
            ->latest()
            ->get();
    }

    /**
     * Get route statistics
     */
    public static function getRouteStatistics(): array
    {
        $statistics = [];
        
        foreach (self::DEFAULT_ROUTES as $routeNumber => $routeName) {
            $vehicleCount = \App\Models\Vehicle::where('route_number', $routeNumber)
                ->where('status', \App\Models\Vehicle::STATUS_ROUTE)
                ->count();
                
            $statistics[$routeNumber] = [
                'name' => $routeName,
                'vehicle_count' => $vehicleCount
            ];
        }
        
        return $statistics;
    }

    /**
     * Check if route number is valid
     */
    public static function isValidRoute(int $routeNumber): bool
    {
        return array_key_exists($routeNumber, self::DEFAULT_ROUTES);
    }

    /**
     * Get next available route number
     */
    public static function getNextAvailableRoute(): int
    {
        $usedRoutes = \App\Models\Vehicle::whereNotNull('route_number')
            ->where('status', \App\Models\Vehicle::STATUS_ROUTE)
            ->pluck('route_number')
            ->unique()
            ->toArray();
            
        foreach (self::DEFAULT_ROUTES as $routeNumber => $routeName) {
            if (!in_array($routeNumber, $usedRoutes)) {
                return $routeNumber;
            }
        }
        
        // If all routes are used, return the first one
        return array_key_first(self::DEFAULT_ROUTES);
    }

    /**
     * Get route with least vehicles
     */
    public static function getRouteWithLeastVehicles(): int
    {
        $routeCounts = [];
        
        foreach (self::DEFAULT_ROUTES as $routeNumber => $routeName) {
            $count = \App\Models\Vehicle::where('route_number', $routeNumber)
                ->where('status', \App\Models\Vehicle::STATUS_ROUTE)
                ->count();
                
            $routeCounts[$routeNumber] = $count;
        }
        
        if (empty($routeCounts)) {
            return array_key_first(self::DEFAULT_ROUTES);
        }
        
        return array_search(min($routeCounts), $routeCounts);
    }
}
