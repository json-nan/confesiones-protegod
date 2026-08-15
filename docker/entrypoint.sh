#!/bin/sh
set -e

# Only bootstrap the application when this container is actually serving
# traffic. Ad-hoc invocations (`docker run <image> php artisan tinker`) fall
# straight through so they don't re-run migrations as a side effect.
if [ "$1" = "frankenphp" ]; then
    if [ -z "${APP_KEY}" ]; then
        echo "entrypoint: APP_KEY is not set. Generate one with 'php artisan key:generate --show' and pass it to the container." >&2
        exit 1
    fi

    if [ -z "${DB_URL}" ]; then
        echo "entrypoint: DB_URL is not set. Point it at your Turso database, e.g. https://<db>-<org>.turso.io" >&2
        exit 1
    fi

    mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache

    # Built at runtime rather than at image build time so that a single image
    # can be promoted across environments with different configuration.
    php artisan config:cache
    php artisan event:cache
    php artisan route:cache
    php artisan view:cache

    # Turso is a single-writer database. Run migrations from one place only —
    # set RUN_MIGRATIONS=false when scaling past a single container and apply
    # them as a separate release step instead.
    if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
        php artisan migrate --force
    fi
fi

exec "$@"
