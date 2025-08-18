#!/bin/sh
set -e

php artisan config:clear
php artisan package:discover

echo "Waiting for database to be ready..."
until nc -z -v -w30 db-mysql 3306
do
  echo "Database is not ready yet. Retrying in 5 seconds..."
  sleep 5
done
echo "Database is ready!"

php artisan migrate --force

php artisan key:generate --force
php artisan optimize:clear
php artisan optimize


php artisan l5-swagger:generate

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

composer dump-autoload -o

echo "Laravel setup is complete. Starting PHP-FPM..."
exec "$@"
