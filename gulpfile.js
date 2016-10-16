var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.styles([
        'bootstrap.css',
        'metisMenu.min.css',
        'timeline.css',
        'sb-admin-2.css',
        'morris',
        'font-awesome.min.css',
        'dataTables.bootstrap.css',
        'dataTables.responsive.css',
        'jquery-ui.css',
        'responsive.css',
        'bootstrap-select.min.css',
        'custom.css'
    ]);

    mix.copy('resources/assets/fonts', 'public/build/fonts');

    mix.scripts([
        'jquery-1.10.2.js',
        'jquery-ui.js',
        'bootstrap.min.js',
        'metisMenu.min.js',
        'raphael-min.js',
        'sb-admin-2.js',
        'bootstrap-select.min.js',
        'bootstrap-select-defaults-ar_AR.js',
        'jquery.dataTables.min.js',
        'dataTables.bootstrap.min.js',
        'custom.js'
    ]);

    mix.version(['css/all.css', 'js/all.js']);

});
