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

# Install PHP dependencies (in build; entrypoint will also ensure vendor if missing)
RUN composer install --no-dev --optimize-autoloader

# Laravel permissions
RUN chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache

# Copy nginx and supervisor config
COPY deploy/nginx.conf /etc/nginx/sites-enabled/default
COPY deploy/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# Copy entrypoint script and make executable
COPY deploy/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expose port expected by Render
EXPOSE 8080

# Use entrypoint that runs migrations and then starts supervisord
CMD ["/entrypoint.sh"]
