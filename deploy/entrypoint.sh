#!/bin/sh
set -e

# Wait a few seconds for DB network to be ready (optional)
sleep 2

# Ensure composer autoload exists (skip if vendor present)
if [ -f composer.json ] && [ ! -d vendor ]; then
  composer install --no-dev --optimize-autoloader
fi

# Run migrations (do not fail deployment if something goes wrong)
php artisan migrate --force || true

# Clear caches so Laravel picks up env vars
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Start supervisord in foreground
exec /usr/bin/supervisord -n
