import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/active-vehicles.css',
                'resources/js/active-vehicles.js',
                'resources/css/vehicles-list.css',
                'resources/js/vehicles-list.js'
            ],
            refresh: true,
        }),
    ],
});
