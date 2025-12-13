#!/bin/bash

# ----------------------------
# Replace $PORT in nginx.conf dynamically
# ----------------------------
envsubst '${PORT}' < /etc/nginx/sites-enabled/default > /etc/nginx/sites-enabled/default.tmp
mv /etc/nginx/sites-enabled/default.tmp /etc/nginx/sites-enabled/default

# ----------------------------
# Ensure Laravel cache & log directories exist
# ----------------------------
mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache storage/logs

# ----------------------------
# Set proper permissions
# ----------------------------
chown -R www-data:www-data storage bootstrap/cache storage/logs
chmod -R 775 storage bootstrap/cache storage/logs

# ----------------------------
# Clear Laravel caches safely
# ----------------------------
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# ----------------------------
# Run migrations safely
# ----------------------------
php artisan migrate --force || true

# ----------------------------
# Start Supervisor (runs PHP-FPM + Nginx)
# ----------------------------
exec /usr/bin/supervisord -n
