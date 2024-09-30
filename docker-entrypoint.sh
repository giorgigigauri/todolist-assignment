#!/bin/sh

php artisan key:generate
php artisan migrate --force --seed

php-fpm
