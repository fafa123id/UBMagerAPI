FROM php:8.4-fpm


RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    netcat-openbsd \
    git \
    curl \
    supervisor \
    libxml2-dev \
    libonig-dev \
    pkg-config \
    ; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring exif pcntl bcmath gd zip; \
    pecl install redis; \
    docker-php-ext-enable redis; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.8.10 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/UBMagerAPI

COPY . .

COPY supervisord.conf /etc/supervisor/supervisord.conf

COPY composer.json ./

RUN composer install --no-scripts --no-dev --prefer-dist --no-interaction

COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

EXPOSE 9000
CMD ["php-fpm"]
