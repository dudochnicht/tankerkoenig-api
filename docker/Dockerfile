FROM php:8.3-cli-alpine

RUN apk add --no-cache curl

RUN docker-php-ext-install opcache

COPY docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "/app/public"]
