const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
const templateDir = 'public/molla-template'

const jsFiles = [
    'resources/js/app.js'
];

mix.js(jsFiles, 'public/js/')
    .vue()
    .styles([
        'resources/css/app.css',
        'resources/css/custom.css',
    ], 'public/css/app.css')
    .webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.version();
} else {
    mix.browserSync({
        proxy: 'xpress1.test'
    });
    mix.disableNotifications();
}
