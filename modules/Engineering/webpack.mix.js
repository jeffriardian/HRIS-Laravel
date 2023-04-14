const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public').mergeManifest();

mix.js(__dirname + '/Resources/assets/js/app.js', 'js/engineering.js')
    .sass( __dirname + '/Resources/assets/sass/app.scss', 'css/engineering.css');

if (mix.inProduction()) {
    mix.version();
}