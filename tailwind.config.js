import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#fff3e0',
                    100: '#ffe0b2',
                    200: '#ffcc80',
                    300: '#ffb74d',
                    400: '#ffa726',
                    500: '#ff9800', // Primary brand color
                    600: '#f57c00',
                    700: '#ef6c00',
                    800: '#e65100',
                    900: '#b36b00', // Dark brand color
                },
                neutral: {
                    50: '#f5f5f5',
                    100: '#e0e0e0',
                    200: '#bdbdbd',
                    300: '#9e9e9e',
                    400: '#757575',
                    500: '#616161',
                    600: '#424242',
                    700: '#212121',
                    800: '#1a1a1a',
                    900: '#0a0a0a',
                }
            },
        },
    },

    plugins: [forms],
};
