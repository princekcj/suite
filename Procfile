web: chmod -R 775 storage/app/ storage/framework/ storage/logs/ bootstrap/cache/ && vendor/bin/heroku-php-apache2 public/
worker: php artisan queue:work
long_worker: php artisan queue:work --queue=long-running-tasks
