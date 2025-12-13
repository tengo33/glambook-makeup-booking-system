#!/bin/bash

# Replace $PORT in nginx.conf dynamically
envsubst '${PORT}' < /etc/nginx/sites-enabled/default > /etc/nginx/sites-enabled/default.tmp
mv /etc/nginx/sites-enabled/default.tmp /etc/nginx/sites-enabled/default

# Ensure Laravel cache directories exist
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p bootstrap/cache

# Set proper permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Clear Laravel caches safely
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Run migrations safely
php artisan migrate --force || true

# Start Supervisor (runs PHP-FPM + Nginx)
exec /usr/bin/supervisord -n
