# =========================
# Stage 0: PHP base (runtime + build deps)
# =========================
FROM php:8.2-fpm AS php_base

# Sistem & build deps untuk ekstensi
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
      libpng-dev \
      libjpeg-dev \
      libfreetype6-dev \
      libpq-dev \
      libonig-dev \
      pkg-config \
      zip \
      unzip \
      netcat-openbsd \
      git \
      curl \
      libxml2-dev \
      supervisor \
      $PHPIZE_DEPS \
    ; \
    pecl install redis; \
    docker-php-ext-enable redis; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" \
      mbstring exif pcntl bcmath gd pdo_mysql pdo_pgsql pgsql \
    ; \
    rm -rf /var/lib/apt/lists/*

# Composer CLI
COPY --from=composer:2.8.10 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/absensi_track


# =========================
# Stage 1: Composer deps (vendor)
# =========================
FROM composer:2.8.10 AS composer_deps
WORKDIR /app
COPY composer.json composer.lock ./

RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist --no-progress


# =========================
# Stage 2: Vite build (Node)
# =========================
FROM node:22-alpine AS vite_build
WORKDIR /var/www/absensi_track

COPY package*.json ./
RUN npm ci

COPY --from=composer_deps /app/vendor ./vendor

COPY tailwind.config.js postcss.config.js vite.config.js ./
COPY resources ./resources

RUN npm run build

# =========================
# Stage 3: App (PHP-FPM runtime + code)
# =========================
FROM php_base AS app
WORKDIR /var/www/absensi_track

ARG GIT_HASH=unknown
RUN echo "${GIT_HASH}" > .version

COPY . .

COPY --from=vite_build /var/www/absensi_track/public/build ./public/build
COPY --from=composer_deps /app/vendor ./vendor

COPY supervisord.conf /etc/supervisor/supervisord.conf

RUN composer dump-autoload --no-dev --optimize --classmap-authoritative

RUN php artisan key:generate --force || true \
 && php artisan view:clear || true \
 && php artisan route:clear || true \
 && php artisan config:clear || true

RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]


FROM nginx:alpine AS web

COPY --from=app /var/www/absensi_track/public /var/www/absensi_track/public
