FROM php:8.3-fpm-alpine

RUN apk add --no-cache git unzip icu-dev libzip-dev postgresql-dev \
    && docker-php-ext-install intl zip pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

ENV COMPOSER_HOME=/tmp/composer
RUN mkdir -p /var/www/html/var && chown -R www-data:www-data /var/www/html
USER www-data