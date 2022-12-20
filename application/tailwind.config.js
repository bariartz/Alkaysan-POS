const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
        colors: {
            'mode': {
                'dark': "#0f0f0f",
                'light': "#ffffff",
            },
            'white': '#ffffff',
            'black': '#000000',
        }
    },

    darkMode: 'media',

    plugins: [require('@tailwindcss/forms')],
};
