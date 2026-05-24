FROM php:8.4.1-fpm-alpine AS base

RUN apk update && apk add --no-cache \
    libzip-dev \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    curl \
    libpq-dev \
    $PHPIZE_DEPS

RUN docker-php-ext-install pdo_mysql zip dom \
    && pecl install redis \
    && docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

COPY .. .

RUN composer install --no-dev --optimize-autoloader --prefer-dist

FROM base AS api
EXPOSE 8000
CMD ["php-fpm"]
