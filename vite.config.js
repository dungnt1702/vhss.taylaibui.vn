import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/vehicles-list.css',
                'resources/js/vehicles-list.js',
                'resources/js/vehicle-forms.js',
                'resources/css/ready-vehicles.css',
                'resources/js/ready-vehicles.js',
                'resources/css/waiting-vehicles.css',
                'resources/js/waiting-vehicles.js',
                'resources/css/running-vehicles.css',
                'resources/js/running-vehicles.js',
                'resources/css/paused-vehicles.css',
                'resources/js/paused-vehicles.js',
                'resources/css/expired-vehicles.css',
                'resources/js/expired-vehicles.js',
                'resources/css/workshop-vehicles.css',
                'resources/js/workshop-vehicles.js',
                'resources/css/repairing-vehicles.css',
                'resources/js/repairing-vehicles.js',
                'resources/css/maintaining-vehicles.css',
                'resources/js/maintaining-vehicles.js',
                'resources/css/attributes-list.css',
                'resources/js/attributes-list.js'
            ],
            refresh: true,
        }),
    ],
});
