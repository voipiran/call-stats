/** local */
const mix = require('laravel-mix');

mix.js('resources/assets/js/app.js', 'public/js').vue()


/** server */
// const mix = require('laravel-mix');

// mix.setPublicPath('../../html/voipiran/stats')
// mix.js('/var/www/voipiran/stats/resources/assets/js/main.js', 'js/').vue()