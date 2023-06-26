#!/usr/bin/bash
echo "Updaring project"
git pull origin master --force

echo "Running composer"
composer install --no-dev

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache


echo  "Stop services"
pkill -f wafi

echo "Running server"
#nohup php artisan serve --host 0.0.0.0 --port 8000 > logs/prod/server.log &
nohup bash -c "exec -a wafi php artisan serve --host 0.0.0.0 --port 9000" > logs/prod/server.log &

echo "Running schedules"
nohup bash -c "exec -a wafi php artisan schedule:work" > logs/prod/schedule.log &
# echo "Running migrations..."
# php artisan migrate --force
echo "Service is running"
exit
