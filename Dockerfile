FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip nginx supervisor \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy app files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Laravel permissions
RUN chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache

# Copy Nginx config
COPY deploy/nginx.conf /etc/nginx/sites-enabled/default

# Copy Supervisor config
COPY deploy/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# Expose port expected by Render
EXPOSE 8080

# Start Supervisor (runs nginx + php-fpm)
/usr/bin/supervisord -n
CMD ["/usr/bin/supervisord"]
