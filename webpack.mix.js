const {mix} = require('laravel-mix');

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
/*
mix.js([
    'resources/assets/js/app.js',
    //'resources/assets/js/common.js'
], 'public/js')
        .sass('resources/assets/sass/app.scss', 'public/css').version();
*/


mix.js([
    'resources/assets/js/app.js',
    'resources/assets/js/jquery-ui.js',
    'resources/assets/js/bootstrap.min.js',
    'resources/assets/js/metisMenu.min.js',
    'resources/assets/js/raphael.min.js',
    'resources/assets/js/bootstrap-select.min.js',
    'resources/assets/js/bootstrap-select-defaults-ar_AR.js',
    'resources/assets/js/jquery.dataTables.min.js',
    'resources/assets/js/dataTables.bootstrap.min.js',
    'resources/assets/js/sb-admin-2.js',
    'resources/assets/js/common.js'
], 'public/js/app.js').version();


mix.styles([
    'resources/assets/css/bootstrap.css',
    'resources/assets/css/metisMenu.min.css',
    'resources/assets/css/timeline.css',
    'resources/assets/css/sb-admin-2.css',
    'resources/assets/css/morris.css',
    'resources/assets/css/font-awesome.min.css',
    'resources/assets/css/dataTables.bootstrap.css',
    'resources/assets/css/dataTables.responsive.css',
    'resources/assets/css/jquery-ui.css',
    'resources/assets/css/responsive.css',
    'resources/assets/css/bootstrap-select.min.css',
    'resources/assets/css/custom.css',
    'node_modules/flatpickr/dist/flatpickr.css'
], 'public/css/app.css').version();


mix.js('resources/assets/js/prcoess_items.js', 'public/js').version();
mix.copy('resources/assets/fonts', 'public/fonts');