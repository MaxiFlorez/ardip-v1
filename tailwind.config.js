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
            breakpoints: {
                'xs': '320px',
                'sm': '640px',
                'md': '768px',
                'lg': '1024px',
                'xl': '1280px',
                '2xl': '1536px',
            },
            spacing: {
                'safe': 'max(1rem, env(safe-area-inset-left))',
                'safe-top': 'max(1rem, env(safe-area-inset-top))',
                'safe-bottom': 'max(1rem, env(safe-area-inset-bottom))',
                'safe-right': 'max(1rem, env(safe-area-inset-right))',
            },
            animation: {
                'slideIn': 'slideIn 0.3s ease-in-out',
                'slideOut': 'slideOut 0.3s ease-in-out',
            },
            keyframes: {
                slideIn: {
                    '0%': { transform: 'translateX(-100%)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                },
                slideOut: {
                    '0%': { transform: 'translateX(0)', opacity: '1' },
                    '100%': { transform: 'translateX(-100%)', opacity: '0' },
                },
            },
            transitionDuration: {
                '300': '300ms',
                '500': '500ms',
            },
        },
    },

    plugins: [forms],
};
