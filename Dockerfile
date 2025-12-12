FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip nginx supervisor \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy app files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Laravel storage permissions
RUN chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache

# Ensure PHP-FPM socket directory exists
RUN mkdir -p /var/run/php && chown -R www-data:www-data /var/run/php

# Copy nginx config
COPY deploy/nginx.conf /etc/nginx/sites-enabled/default

# Remove old nginx default file if exists
RUN rm -f /etc/nginx/sites-enabled/default.conf || true

# Copy supervisor config
COPY deploy/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# Copy entrypoint script
COPY deploy/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expose port
EXPOSE 8080

# Start the entrypoint (runs migrations + supervisor)
CMD ["/entrypoint.sh"]
