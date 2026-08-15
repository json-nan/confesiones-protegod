# syntax=docker/dockerfile:1

# ---------------------------------------------------------------------------
# Base — FrankenPHP runtime shared by the build and final stages so that
# Composer resolves against exactly the PHP version that will serve traffic.
# ---------------------------------------------------------------------------
FROM dunglas/frankenphp:1-php8.4-alpine AS base

WORKDIR /app

RUN apk add --no-cache libcap curl \
    && install-php-extensions \
        opcache \
        pcntl \
        intl \
        zip \
        pdo_sqlite

# The base image only lays down php.ini via its own entrypoint, which this
# image replaces — so put the production baseline in place explicitly. Files
# in conf.d are read afterwards and win.
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini

# ---------------------------------------------------------------------------
# Vendor — PHP dependencies. composer.json/lock are copied first so the
# install layer is reused whenever only application code changes.
# ---------------------------------------------------------------------------
FROM base AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-interaction \
        --no-progress

COPY . .
RUN mkdir -p bootstrap/cache storage/framework/views \
    && composer dump-autoload --no-dev --optimize --classmap-authoritative \
    && php artisan package:discover --ansi

# ---------------------------------------------------------------------------
# Assets — Vite build. Tailwind scans blade files under vendor/, so the
# installed packages have to be present for the content globs to match.
# ---------------------------------------------------------------------------
FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
COPY --from=vendor /app/vendor ./vendor

RUN npm run build

# ---------------------------------------------------------------------------
# Final image
# ---------------------------------------------------------------------------
FROM base AS app

# FrankenPHP defaults to 2 threads per CPU, which is tuned for CPU-bound work.
# Here every query is an HTTP round trip to Turso, so threads spend most of
# their time blocked on the network and a 1-vCPU box wants more of them than
# the default 2. Eight covers ~20 concurrent users with room to spare; see the
# sizing table in DEPLOY.md before changing it.
ENV APP_ENV=production \
    APP_DEBUG=false \
    SERVER_NAME=:80 \
    SERVER_ROOT=/app/public \
    FRANKENPHP_CONFIG="num_threads 8"

COPY --chown=www-data:www-data . .
COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor
COPY --from=vendor --chown=www-data:www-data /app/bootstrap/cache ./bootstrap/cache
COPY --from=assets --chown=www-data:www-data /app/public/build ./public/build

COPY docker/Caddyfile /etc/frankenphp/Caddyfile
COPY --chmod=755 docker/entrypoint.sh /usr/local/bin/entrypoint

# Runtime state directories are excluded from the build context, so recreate
# the tree the framework expects and hand it to the unprivileged user.
RUN mkdir -p \
        storage/app/public \
        storage/app/private \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && mkdir -p /data/caddy /config/caddy \
    && chown -R www-data:www-data storage bootstrap/cache /data/caddy /config/caddy \
    && setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp

USER www-data

# 443 and HTTP/3 only matter when SERVER_NAME names a real host and Caddy
# manages its own certificate.
EXPOSE 80 443 443/udp

# Falls back to :443 because setting SERVER_NAME to a hostname makes Caddy
# redirect :80 to HTTPS, which a plain -f request would report as a failure.
HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD curl -fsS -o /dev/null http://127.0.0.1:80/up \
     || curl -fsSk -o /dev/null https://127.0.0.1:443/up \
     || exit 1

ENTRYPOINT ["entrypoint"]
CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile", "--adapter", "caddyfile"]
