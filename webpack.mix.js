const mix = require('laravel-mix');

mix.js('app/app.js', 'web/app.js')
    .disableSuccessNotifications()
    .setPublicPath('web')
    .vue();