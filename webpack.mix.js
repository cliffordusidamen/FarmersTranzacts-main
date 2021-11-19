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
    .js('resources/js/sw.js', 'public/sw.js')
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
        proxy: 'farmer-xpress.test'
    });
    mix.disableNotifications();
}
