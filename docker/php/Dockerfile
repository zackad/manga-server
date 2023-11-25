FROM composer:lts AS composer

FROM php:8.2-cli-alpine AS builder

WORKDIR /app
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN apk add git nodejs yarn libzip-dev
RUN docker-php-ext-install zip ctype
RUN php -v
RUN php -m
RUN git clone https://github.com/zackad/manga-server /app
RUN /app/bin/build

FROM php:8.2-fpm-alpine

WORKDIR /app
COPY --from=builder /app/build/manga-server-latest /app
RUN apk add --no-cache libzip-dev \
    && docker-php-ext-install zip
