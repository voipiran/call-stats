const mix = require('laravel-mix');

mix.setPublicPath('../../html/voipiran/call-stats')
mix.js('/var/www/voipiran/call-stats/resources/assets/js/app.js', 'js/').vue()