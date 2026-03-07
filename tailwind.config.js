// tailwind.config.js
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                exo: ['"Exo 2"', ...defaultTheme.fontFamily.sans],
                rajdhani: ['Rajdhani', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                olive: {
                    400: '#8fa356',
                    500: '#6b8240',
                    600: '#4a5e3a',
                    700: '#374830',
                    800: '#263220',
                    900: '#1a2318',
                },
            },
        },
    },
    plugins: [forms],
};
