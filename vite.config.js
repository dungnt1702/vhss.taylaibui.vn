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
                'resources/css/vehicles/ready-vehicles.css',
                'resources/js/vehicles/ready-vehicles.js',
                'resources/css/vehicles/waiting-vehicles.css',
                'resources/js/vehicles/waiting-vehicles.js',
                'resources/css/vehicles/running-vehicles.css',
                'resources/js/vehicles/running-vehicles.js',
                'resources/css/vehicles/paused-vehicles.css',
                'resources/js/vehicles/paused-vehicles.js',
                'resources/css/vehicles/expired-vehicles.css',
                'resources/js/vehicles/expired-vehicles.js',
                'resources/css/vehicles/workshop-vehicles.css',
                'resources/js/vehicles/workshop-vehicles.js',
                'resources/css/vehicles/repairing-vehicles.css',
                'resources/js/vehicles/repairing-vehicles.js',
                'resources/css/vehicles/maintaining-vehicles.css',
                'resources/js/vehicles/maintaining-vehicles.js',
                'resources/css/vehicles/attributes-list.css',
                'resources/js/vehicles/attributes-list.js'
            ],
            refresh: true,
        }),
    ],
});
