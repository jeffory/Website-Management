web: vendor/bin/heroku-php-nginx -C nginx.conf public/
queue: php artisan queue:work redis --sleep=3 --tries=3 --daemon