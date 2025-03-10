import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import theme from 'tailwindcss/defaultTheme';

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
                "primary-color": "#111110",
                "secondary-color": "#A28879",
                "highlight-color": "#F7F1F0",
                "accent-color": "#515051",
            }
        },
    },

    plugins: [forms],
};
