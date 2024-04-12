#!/bin/bash
cd /var/www/api.qa.rs || exit
php artisan db:wipe
php artisan migrate
php artisan db:seed
