#!/bin/sh
set -e

echo "==> Discovering packages..."
php artisan package:discover --ansi

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Clearing caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Starting server on port ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
