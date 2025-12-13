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

# Clear Laravel caches
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Generate APP_KEY if needed
php artisan key:generate --force || true

# Migrate database
php artisan migrate:fresh --force || true	

# FINAL FIX for storage permission issues
chmod -R 777 storage storage/logs bootstrap/cache
chown -R www-data:www-data storage storage bootstrap/cache

# Start Supervisor
exec /usr/bin/supervisord -n
