const mix = require('laravel-mix');

mix
    .setPublicPath('public/resources')
    .setResourceRoot('/resources/')
    .js('resources/assets/js/app.js', 'js')
    .sass('resources/assets/sass/app.scss', 'css')
    .version();