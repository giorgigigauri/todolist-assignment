#!/bin/sh
composer install
php artisan key:generate
php artisan migrate --force --seed

php-fpm
