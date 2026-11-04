#!/bin/sh
set -e

php artisan config:clear
php artisan package:discover


php artisan key:generate --force
php artisan optimize:clear
php artisan optimize


php artisan l5-swagger:generate

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
if [ ! -f storage/oauth-private.key ] || [ ! -f storage/oauth-public.key ]; then
    echo "Generating Passport keys..."
    php artisan passport:keys
else
    echo "Passport keys already exist."
fi

chmod 600 storage/oauth-private.key
chmod 644 storage/oauth-public.key

composer dump-autoload -o

echo "Laravel setup is complete. Starting PHP-FPM..."
exec "$@"
