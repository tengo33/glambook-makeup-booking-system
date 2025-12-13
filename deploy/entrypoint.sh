#!/bin/bash

# ----------------------------
# 0️⃣ Replace $PORT in nginx.conf dynamically
# ----------------------------
envsubst '${PORT}' < /etc/nginx/sites-enabled/default > /etc/nginx/sites-enabled/default.tmp
mv /etc/nginx/sites-enabled/default.tmp /etc/nginx/sites-enabled/default

# ----------------------------
# 1️⃣ Clear Laravel caches safely
# ----------------------------
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# ----------------------------
# 2️⃣ Run migrations safely
# ----------------------------
php artisan migrate --force || true

# ----------------------------
# 3️⃣ Start Supervisor (runs PHP-FPM + Nginx)
# ----------------------------
exec /usr/bin/supervisord -n
