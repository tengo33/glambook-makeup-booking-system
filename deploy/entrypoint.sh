#!/bin/bash

# Run Laravel optimizations
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Run migrations (safe)
php artisan migrate --force || true

# Start Supervisor (nginx + php-fpm)
exec /usr/bin/supervisord -n
