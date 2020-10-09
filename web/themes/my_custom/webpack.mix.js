let mix = require('laravel-mix');

mix.sass('resources/sass/style.scss', 'css/');
mix.js('resources/js/global.js', 'js/');
mix.options({
  processCssUrls: false
});
