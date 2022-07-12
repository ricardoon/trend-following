const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        fontFamily: {
            'sans': [
                'Inter',
                'sans-serif'
            ]
        },
        extend: {
            colors: {
                'primary': '#00bcd4',
                'secondary': '#ff9800',
                transparent: 'transparent',
                current: 'currentColor',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms')
    ],
};
