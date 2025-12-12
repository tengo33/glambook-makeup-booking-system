#!/bin/bash

# ----------------------------
# 1️⃣ Clear Laravel caches
# ----------------------------
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# ----------------------------
# 2️⃣ Run migrations safely
# ----------------------------
php artisan migrate --force || true

# ----------------------------
# 3️⃣ Start Supervisor (runs nginx + php-fpm)
# ----------------------------
exec /usr/bin/supervisord -n
