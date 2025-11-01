#!/bin/sh
set -e

php artisan config:clear
php artisan package:discover

php artisan migrate --force

php artisan key:generate --force
php artisan optimize:clear
php artisan optimize


php artisan l5-swagger:generate

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
chmod -R 660 storage/oauth-private.key

composer dump-autoload -o

echo "Laravel setup is complete. Starting PHP-FPM..."
exec "$@"
