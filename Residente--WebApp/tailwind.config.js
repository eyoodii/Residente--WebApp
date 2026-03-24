import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue', // Keep this if you plan to use Vue later
    ],

    theme: {
        extend: {
            colors: {
                // Fresh Citrus Palette for RESIDENTE App
                'deep-forest': '#034732',
                'sea-green': '#008148',
                'golden-glow': '#c6c013',
                'tiger-orange': '#ef8a17',
                'burnt-tangerine': '#ef2917',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
