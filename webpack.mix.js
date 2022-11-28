const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/app.js", "public/js/app.js")
    .postCss("resources/css/app.css", "public/css/app.css", [])
    .minify("public/js/app.js", "public/js/app.min.js")
    .minify("public/css/app.css", "public/css/app.min.css")
    .sourceMaps();

if (mix.inProduction()) mix.version();

mix.disableNotifications();
