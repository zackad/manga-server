FROM php:8.2-fpm-alpine

WORKDIR /app
RUN apk add --no-cache libzip-dev \
    && docker-php-ext-install zip
