#!/bin/sh

cd /var/www
php artisan key:generate
php artisan migrate:fresh --silent --force
php artisan schedule:work &
php artisan serve --host=0.0.0.0 --port=10000
