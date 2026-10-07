import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            
            colors: {
                primary: '#1f2937', // Dark gray for sidebar background
                secondary: '#3b82f6', // Blue accent color
                accent: '#f43f5e', // Red for important buttons/actions
                lightGray: '#f3f4f6', // Light gray for background
                darkGray: '#4b5563', // Darker gray for text
                gray: '#9ca3af', // Gray for text
                white: '#ffffff', // White for text
                black: '#000000', // Black for text

              },
        },
    },
    variants: {
        extend: {
            

        },
      },
    plugins: [forms],
};
