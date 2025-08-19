FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    netcat-openbsd \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:2.8.10 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/UBMagerAPI

COPY . .

COPY composer.json composer.lock ./

RUN composer install --no-scripts --no-dev --prefer-dist --no-interaction

ARG UID=1000
ARG GID=1000
RUN groupadd -g $GID -o appgroup
RUN useradd -m -u $UID -g $GID -o -s /bin/bash appuser

RUN chown -R appuser:appgroup /var/www/UBMagerAPI

USER appuser


COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

EXPOSE 9000
CMD ["php-fpm"]
