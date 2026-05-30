FROM php:8.4-fpm-alpine AS base

RUN apk update && apk add --no-cache \
    libzip-dev \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    curl \
    libpq-dev \
    linux-headers \
    icu-dev \
    icu-libs \
    $PHPIZE_DEPS

RUN set -eux; \
    apk add --no-cache \
        git unzip $PHPIZE_DEPS \
        curl-dev libevent-dev icu-dev zlib-dev libzip-dev pkgconf \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j"$(nproc)" intl pdo_mysql zip dom sockets pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -f /usr/local/etc/php/conf.d/docker-php-ext-http.ini \
             /usr/local/etc/php/conf.d/docker-php-ext-raphf.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

COPY .. .

RUN composer install --no-dev --optimize-autoloader --prefer-dist

FROM base AS api

COPY --from=ghcr.io/roadrunner-server/roadrunner:2024.3 /usr/bin/rr /usr/local/bin/rr

RUN set -eux; \
    chmod +x /usr/local/bin/rr; \
    /usr/local/bin/rr --version

EXPOSE 8000

CMD ["/usr/local/bin/rr", "serve", "-c", ".rr.yaml"]
