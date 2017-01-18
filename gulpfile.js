var elixir = require('laravel-elixir');

require('laravel-elixir-vue');
require('elixir-typescript');

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
        'dataTables.bootstrap.min.js'
    ]);

    mix.version(['css/all.css', 'js/all.js']);

    mix.sass('app.scss')
        .webpack('app.js')
        .copy('node_modules/@angular', 'public/@angular')
        .copy('node_modules/anular2-in-memory-web-api', 'public/anular2-in-memory-web-api')
        .copy('node_modules/core-js', 'public/core-js')
        .copy('node_modules/reflect-metadata', 'public/reflect-metadata')
        .copy('node_modules/systemjs', 'public/systemjs')
        .copy('node_modules/rxjs', 'public/rxjs')
        .copy('node_modules/zone.js', 'public/zone.js')

        .typescript(
            [
                'app.component.ts',
                'app.module.ts',
                'main.ts'
            ],
            'public/app',
            {
                "target": "es5",
                "module": "system",
                "moduleResolution": "node",
                "sourceMap": true,
                "emitDecoratorMetadata": true,
                "experimentalDecorators": true,
                "removeComments": false,
                "noImplicitAny": false
            }
        );
});
