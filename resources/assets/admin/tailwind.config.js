const { fontFamily } = require('tailwindcss/defaultTheme');
const forms = require('@tailwindcss/forms');
const typography = require('@tailwindcss/typography');
const aspectRatio = require('@tailwindcss/aspect-ratio');
const lineClamp = require('@tailwindcss/line-clamp');

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
                    300: '#C4CDD5',
                    700: '#2E2E2E',
                    900: '#212121',
                },
                blue: {
                    100: '#94D6FF',
                    500: '#0077C8',
                    700: '#004C97',
                },
            },
        },
    },
    plugins: [forms, typography, aspectRatio, lineClamp],
};
