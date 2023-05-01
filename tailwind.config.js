const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

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
            zIndex: {
                '1': '1',
            },
            spacing: {
                '15': '52px',
            }
        },
        colors: {
            'mode': {
                'dark': "#212121",
                'light': "#ffffff",
            },
            black: colors.black,
            white: colors.white,
            gray: colors.gray,
            emerald: colors.emerald,
            indigo: colors.indigo,
            yellow: colors.yellow,
            alkaysan: "#ed3237",
        },
    },

    darkMode: 'media',

    plugins: [require('@tailwindcss/forms')],
};
