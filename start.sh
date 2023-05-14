#!/usr/bin/bash
echo "Running composer"
composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running app"
php artisan serve --host 0.0.0.0 --port 10000
# echo "Running migrations..."
# php artisan migrate --force
