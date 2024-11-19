FROM composer:2 AS builder

ENV COMPOSER_IGNORE_PLATFORM_REQS=1
WORKDIR /app
COPY . /app
RUN apk add --no-cache nodejs yarn
RUN bin/build

FROM dunglas/frankenphp:1-php8.3-alpine

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=builder /app/build/manga-server /app

RUN mkdir -p /data \
    && chown -R 1000:1000 /app /data \
    && install-php-extensions imagick opcache zip

USER 1000:1000
WORKDIR /app

LABEL org.opencontainers.image.source=https://github.com/zackad/manga-server

ENTRYPOINT ["frankenphp", "php-server", "--root", "public", "--listen", ":8000"]
