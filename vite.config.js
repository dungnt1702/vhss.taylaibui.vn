import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/vehicles/vehicles-list.css',
                'resources/js/vehicles/vehicles-list.js',
                'resources/js/vehicles/vehicle-forms.js',
                // Use the VehicleClasses.js entry point for proper dependency management
                'resources/js/vehicles/VehicleClasses.js',
                // Vehicle-specific CSS files
                'resources/css/vehicles/ready-vehicles.css',
                'resources/css/vehicles/waiting-vehicles.css',
                'resources/css/vehicles/running-vehicles.css',
                'resources/css/vehicles/paused-vehicles.css',
                'resources/css/vehicles/expired-vehicles.css',
                'resources/css/vehicles/workshop-vehicles.css',
                'resources/css/vehicles/repairing-vehicles.css',
                'resources/css/vehicles/maintaining-vehicles.css',
                'resources/css/vehicles/attributes-list.css',
                // Other vehicle-specific JS files (non-class files)
                'resources/js/vehicles/workshop-vehicles.js',
                'resources/js/vehicles/repairing-vehicles.js',
                'resources/js/vehicles/maintaining-vehicles.js',
                'resources/js/vehicles/attributes-list.js'
            ],
            refresh: true,
        }),
    ],
});
