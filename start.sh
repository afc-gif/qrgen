#!/usr/bin/env sh
set -eu

mkdir -p bootstrap/cache \
    storage/app/public/qr-codes \
    storage/app/public/logo-uploads \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs

chmod -R ug+rwX bootstrap/cache storage || true

php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
