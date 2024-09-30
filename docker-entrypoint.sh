#!/bin/sh

php artisan migrate --force --seed

php-fpm
