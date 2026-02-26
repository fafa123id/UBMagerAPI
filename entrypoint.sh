#!/bin/sh
set -e

php artisan config:clear
php artisan package:discover


php artisan key:generate --force
php artisan optimize:clear
php artisan optimize


php artisan l5-swagger:generate

RUN chown -R 108:111 /var/www/UBMagerAPI \
    && chmod -R 775 /var/www/UBMagerAPI/storage \
    && chmod -R 775 /var/www/UBMagerAPI/bootstrap/cache
chmod -R 660 storage/oauth-private.key
chmod -R 660 storage/oauth-public.key

composer dump-autoload -o

echo "Laravel setup is complete. Starting PHP-FPM..."
exec "$@"
