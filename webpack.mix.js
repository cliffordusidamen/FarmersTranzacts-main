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
    // templateDir + '/js/bootstrap.bundle.min.js',
    // templateDir + '/js/main.js',
    'resources/js/app.js'
];

mix.js(jsFiles, 'public/js/')
    .vue()
    .css('resources/css/custom.css', 'public/css/')
    .webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.version();
} else {
    mix.browserSync({
        proxy: 'xpress1.test'
    });
}
