#!/bin/bash

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
