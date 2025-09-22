FROM docker.io/library/composer:2 AS builder

ENV COMPOSER_IGNORE_PLATFORM_REQS=1
ENV COMPOSER_HOME=/tmp/composer

WORKDIR /app
COPY . /app
RUN apk add --no-cache nodejs yarn
RUN --mount=type=cache,target=/tmp/composer \
    --mount=type=cache,target=/app/node_modules \
    bin/build

FROM docker.io/dunglas/frankenphp:1-php8.4-alpine AS runtime
ARG S6_OVERLAY_VERSION=3.2.1.0

COPY --from=docker.io/mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions imagick opcache zip

ADD https://github.com/just-containers/s6-overlay/releases/download/v${S6_OVERLAY_VERSION}/s6-overlay-noarch.tar.xz /tmp
RUN tar -C / -Jxpf /tmp/s6-overlay-noarch.tar.xz
ADD https://github.com/just-containers/s6-overlay/releases/download/v${S6_OVERLAY_VERSION}/s6-overlay-x86_64.tar.xz /tmp
RUN tar -C / -Jxpf /tmp/s6-overlay-x86_64.tar.xz

COPY s6 /

# Rebuild image to remove incorrect information such as EXPOSE, HEALTHCHECK etc.
# https://stackoverflow.com/a/72024605
FROM scratch

# Fix permission for frankenphp to manage their data and config
# https://github.com/php/frankenphp/issues/607#issuecomment-2690469396
ENV XDG_CONFIG_HOME=/config
ENV XDG_DATA_HOME=/data

COPY --from=runtime / /
COPY --from=builder /app/build/manga-server /app

RUN mkdir -p /{config,data} \
    && chown -R 1000:1000 /app /data /config

USER 1000:1000
WORKDIR /app

EXPOSE 8000

LABEL org.opencontainers.image.source=https://github.com/zackad/manga-server

ENTRYPOINT ["/init"]
