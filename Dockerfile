FROM composer:2 AS builder

ENV COMPOSER_IGNORE_PLATFORM_REQS=1
WORKDIR /app
COPY . /app
RUN apk add --no-cache nodejs yarn
RUN bin/build

FROM dunglas/frankenphp:1-php8.3-alpine

# Configure and install additional php extension
# Option 1: gd extention with "jpeg png webp" support
RUN apk add --no-cache libjpeg-turbo-dev libpng-dev libwebp-dev libzip-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd opcache zip

# Option 2: gd extention with "avif freetype jpeg png xpm webp" support
#RUN apk add --no-cache freetype-dev libavif-dev libjpeg-turbo-dev libpng-dev libwebp-dev libxpm-dev libzip-dev \
#    && docker-php-ext-configure gd --with-avif --with-jpeg --with-xpm --with-webp --with-freetype \
#    && docker-php-ext-install gd zip

COPY --from=builder /app/build/manga-server /app
RUN mkdir -p /data \
    && chown -R 1000:1000 /app /data

USER 1000:1000
WORKDIR /app

LABEL org.opencontainers.image.source=https://github.com/zackad/manga-server

ENTRYPOINT ["frankenphp", "php-server", "--root", "public", "--listen", ":8000"]
