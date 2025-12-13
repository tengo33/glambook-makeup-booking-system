FROM php:8.2-fpm

# ------------------------------------------------
# 1️⃣ Install system dependencies + gettext for envsubst
# ------------------------------------------------
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip nginx supervisor gettext-base \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ------------------------------------------------
# 2️⃣ Install Composer
# ------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ------------------------------------------------
# 3️⃣ Set working directory
# ------------------------------------------------
WORKDIR /var/www

# ------------------------------------------------
# 4️⃣ Copy application code
# ------------------------------------------------
COPY . .

# ------------------------------------------------
# 5️⃣ Install PHP dependencies
# ------------------------------------------------
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# ------------------------------------------------
# 6️⃣ Laravel storage folders and permissions
# ------------------------------------------------
RUN mkdir -p storage/framework/{cache,sessions,views} \
    && mkdir -p bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# ------------------------------------------------
# 7️⃣ Copy configs
# ------------------------------------------------
COPY deploy/nginx.conf /etc/nginx/sites-enabled/default
COPY deploy/supervisor.conf /etc/supervisor/conf.d/supervisor.conf
COPY deploy/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# ------------------------------------------------
# 8️⃣ Expose port (Render uses $PORT)
# ------------------------------------------------
EXPOSE 8080

# ------------------------------------------------
# 9️⃣ Entrypoint
# ------------------------------------------------
CMD ["/entrypoint.sh"]
