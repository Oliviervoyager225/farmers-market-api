#!/bin/sh
set -e

echo "==> Discovering packages..."
php artisan package:discover --ansi

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Seeding database..."
php artisan db:seed --force

echo "==> Clearing caches..."
php artisan cache:clear
php artisan config:cache
php artisan route:clear
php artisan view:clear

echo "==> Starting server on port ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
