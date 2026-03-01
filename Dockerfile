FROM docker.io/library/composer:2 AS builder

ENV COMPOSER_IGNORE_PLATFORM_REQS=1
ENV COMPOSER_HOME=/tmp/composer

WORKDIR /app
RUN apk add --no-cache nodejs yarn

COPY . /app
RUN --mount=type=cache,target=/tmp/composer \
    --mount=type=cache,target=/app/node_modules \
    bin/build

FROM docker.io/library/alpine:3 AS runtime

ARG FRANKENPHP_VERSION=1.11.2
ARG S6_OVERLAY_VERSION=3.2.2.0

# frankenphp provides static build that enough for our use case, no need to build our own version
ADD https://github.com/php/frankenphp/releases/download/v${FRANKENPHP_VERSION}/frankenphp-linux-x86_64 /usr/local/bin/frankenphp
ADD https://github.com/just-containers/s6-overlay/releases/download/v${S6_OVERLAY_VERSION}/s6-overlay-noarch.tar.xz /tmp
ADD https://github.com/just-containers/s6-overlay/releases/download/v${S6_OVERLAY_VERSION}/s6-overlay-x86_64.tar.xz /tmp

RUN mkdir -p /s6/root
RUN tar -C /s6/root -Jxpf /tmp/s6-overlay-noarch.tar.xz
RUN tar -C /s6/root -Jxpf /tmp/s6-overlay-x86_64.tar.xz
RUN chmod +x /usr/local/bin/frankenphp
COPY s6 /s6/root

FROM docker.io/library/alpine:3

# Fix permission for frankenphp to manage their data and config
# https://github.com/php/frankenphp/issues/607#issuecomment-2690469396
ENV XDG_CONFIG_HOME=/config
ENV XDG_DATA_HOME=/data

COPY --from=runtime /s6/root /
COPY --from=runtime /usr/local/bin/frankenphp /usr/local/bin/frankenphp
COPY --from=builder /app/build/manga-server /app

RUN apk add --no-cache curl

RUN mkdir /config \
  && mkdir /data \
  && chown -R 1000:1000 /app /data /config

USER 1000:1000
WORKDIR /app

EXPOSE 8000

LABEL org.opencontainers.image.source=https://github.com/zackad/manga-server

ENTRYPOINT ["/init"]
