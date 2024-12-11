const mix = require('laravel-mix');

const dotEnvConfig = { path: '../../../.env' };
const WebpackDotenv = require('dotenv-webpack');
require('dotenv').config(dotEnvConfig);

const tailwindcss = require('tailwindcss');

const themeRoot = '../../../public/dist/admin/';
const SentryCliPlugin = require('@sentry/webpack-plugin');

const config = require('./webpack.config');

/**
 * Set the resource root and the public path.
 */
mix.setResourceRoot('/dist/admin/');
mix.setPublicPath(themeRoot);

/**
 * Copy the public images.
 */
mix.copyDirectory('images', `${themeRoot}/images/`);
mix.copyDirectory('fonts', `${themeRoot}/fonts/`);

mix.js('src/main.js', 'js')
    .vue({ version: 2 })
    .extract()
    .sass('styles/main.scss', 'css')
    .options({
        processCssUrls: false,
        postCss: [tailwindcss('./tailwind.config.js')],
    })
    .sourceMaps(true);

/**
 * Alias the scripts folder for easier imports.
 */
mix.alias({
    '@': './src',
});

/**
 * Extend webpack config.
 */
const webpackPlugins = [new WebpackDotenv(dotEnvConfig)];

if (process.env.RELEASE_ID) {
    webpackPlugins.push(
        new SentryCliPlugin({
            release: process.env.RELEASE_ID,
            include: '../../../public/',
        })
    );
}

mix.webpackConfig({
    plugins: webpackPlugins,
    stats: {
        children: true,
    },
    ...config,
});

if (mix.inProduction()) {
    mix.version();
}
