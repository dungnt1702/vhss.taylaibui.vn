import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                // Vehicle-specific CSS files
                'resources/css/vehicles/active-vehicles.css',
                'resources/css/vehicles/ready-vehicles.css',
                'resources/css/vehicles/running-vehicles.css',
                'resources/css/vehicles/paused-vehicles.css',
                'resources/css/vehicles/expired-vehicles.css',
                'resources/css/vehicles/workshop-vehicles.css',
                'resources/css/vehicles/repairing-vehicles.css',
                'resources/css/vehicles/attributes-list.css',
                'resources/css/vehicles/vehicles-list.css',
                // Vehicle-specific JS files
                'resources/js/vehicles/VehicleBase.js',
                'resources/js/vehicles/ActiveVehicles.js',
                'resources/js/vehicles/ReadyVehicles.js',
                'resources/js/vehicles/RunningVehicles.js',
                'resources/js/vehicles/PausedVehicles.js',
                'resources/js/vehicles/ExpiredVehicles.js',
                'resources/js/vehicles/WorkshopVehicles.js',
                'resources/js/vehicles/RepairingVehicles.js',
                'resources/js/vehicles/AttributesList.js',
                'resources/js/vehicles/VehiclesList.js'
            ],
            refresh: true,
        }),
    ],
});
