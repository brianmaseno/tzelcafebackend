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
                sans: ['Montserrat', ...defaultTheme.fontFamily.sans],
                serif: ['Cinzel', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                tzel: {
                    ink: '#1a120b',
                    espresso: '#2c1f14',
                    roast: '#3d2a1a',
                    bronze: '#c5a059',
                    gold: '#d4b06a',
                    cream: '#f5efe6',
                    sand: '#e8dcc8',
                    muted: '#9a8b7a',
                },
            },
        },
    },

    plugins: [forms],
};
