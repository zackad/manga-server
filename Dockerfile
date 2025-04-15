FROM docker.io/library/composer:2 AS builder

ENV COMPOSER_IGNORE_PLATFORM_REQS=1
WORKDIR /app
COPY . /app
RUN apk add --no-cache nodejs yarn
RUN bin/build

FROM docker.io/dunglas/frankenphp:1-php8.4-alpine AS runtime

COPY --from=docker.io/mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions imagick opcache zip

# Rebuild image to remove incorrect information such as EXPOSE, HEALTHCHECK etc.
# https://stackoverflow.com/a/72024605
FROM scratch

COPY --from=runtime / /
COPY --from=builder /app/build/manga-server /app

RUN mkdir -p /data \
    && chown -R 1000:1000 /app /data

USER 1000:1000
WORKDIR /app

EXPOSE 8000

LABEL org.opencontainers.image.source=https://github.com/zackad/manga-server

ENTRYPOINT ["frankenphp", "php-server", "--root", "public", "--listen", ":8000"]
