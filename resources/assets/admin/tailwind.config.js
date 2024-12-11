const { fontFamily } = require('tailwindcss/defaultTheme');
const forms = require('@tailwindcss/forms');
const typography = require('@tailwindcss/typography');
const aspectRatio = require('@tailwindcss/aspect-ratio');
const containerQueries = require('@tailwindcss/container-queries');

module.exports = {
    content: [
        'src/**/*.js',
        'src/**/*.vue',
        '../../views/*.blade.php',
        '../../views/**/*.blade.php',
        '../../../modules/Way2Translate/Views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...fontFamily.sans],
            },
            fontSize: {
                '2xs': '0.6rem',
                'surf-h1': '2.25rem',
                'surf-h2': '1.88rem',
                'surf-h3': '1.5rem',
                'surf-h4': '1.25rem',
                'surf-h5': '1.13rem',
                'surf-h6': '1rem',
                'surf-body-large': '1.13rem',
                'surf-body': '1rem',
                'surf-body-small': '0.88rem',
            },
            fontWeight: {
                'surf-h1': 700,
                'surf-h2': 400,
                'surf-h3': 700,
                'surf-h4': 700,
                'surf-h5': 600,
                'surf-h6': 600,
                'surf-body-large': 400,
                'surf-body': 400,
                'surf-body-small': 400,
            },
            lineHeight: {
                'surf-h1': '2.63',
                'surf-h2': '2.25',
                'surf-h3': '1.88',
                'surf-h4': '1.88',
                'surf-h5': '1.63',
                'surf-h6': '1.88',
                'surf-body-large': '1.63',
                'surf-body': '1.5',
                'surf-body-small': '1.13',
            },
            maxHeight: {
                '1/2vh': '50vh',
                '1/4vh': '25vh',
                almost: 'calc(100vh - 2rem)',
            },
            maxWidth: {
                xxl: '100rem',
            },
            screens: {
                print: { raw: 'print' },
            },
            zIndex: {
                '-1': '-1',
            },
            colors: {
                gray: {
                    50: '#F9FAFB',
                    100: '#F4F6F8',
                    200: '#e5e7eb',
                    300: '#C4CDD5',
                    700: '#2E2E2E',
                    900: '#212121',
                },
                blue: {
                    100: '#94D6FF',
                    500: '#0077C8',
                    700: '#004C97',
                },
                yellow: {
                    200: '#fef8d3',
                    500: '#ffc100',
                },
                surf: {
                    white: 'rgb(255, 255, 255)',
                    gray: {
                        100: 'rgb(244, 246, 248)',
                        200: 'rgb(234, 236, 240)',
                        300: 'rgb(178, 182, 190)',
                        400: 'rgb(140, 150, 159)',
                        500: 'rgb(94, 104, 115)',
                    },
                    black: 'rgb(5, 14, 29)',
                },
            },
        },
    },
    plugins: [forms, typography, aspectRatio, containerQueries],
};
