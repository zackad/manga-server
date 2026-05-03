FROM docker.io/library/alpine:latest AS base

FROM base AS downloader

ARG PHP_VERSION=8.5.5
ARG S6_OVERLAY_VERSION=3.2.2.0

ADD https://dl.static-php.dev/static-php-cli/bulk/php-${PHP_VERSION}-cli-linux-x86_64.tar.gz /tmp
ADD https://github.com/just-containers/s6-overlay/releases/download/v${S6_OVERLAY_VERSION}/s6-overlay-noarch.tar.xz /tmp
ADD https://github.com/just-containers/s6-overlay/releases/download/v${S6_OVERLAY_VERSION}/s6-overlay-x86_64.tar.xz /tmp

RUN mkdir -p /tmp/s6/root
RUN mkdir -p /tmp/bin
RUN tar -C /tmp/s6/root -Jxpf /tmp/s6-overlay-x86_64.tar.xz
RUN tar -C /tmp/s6/root -Jxpf /tmp/s6-overlay-noarch.tar.xz
RUN tar -C /tmp/bin -xzvf /tmp/php-${PHP_VERSION}-cli-linux-x86_64.tar.gz

FROM base AS runtime

COPY --from=downloader /tmp/s6/root /
COPY --from=downloader /tmp/bin /usr/local/bin
COPY --from=ghcr.io/roadrunner-server/roadrunner:2025.1 /usr/bin/rr /usr/local/bin/rr

EXPOSE 8000

ENTRYPOINT ["/init"]
