FROM php:8.0-cli-alpine as base

RUN apk update --no-cache && apk add --no-cache \
    git \
    unzip \
    zip

COPY --from=mlocati/php-extension-installer:1.2.23 /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
    pdo_pgsql \
    uuid \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www

RUN chown -R www-data:www-data /var/www

USER www-data:www-data
