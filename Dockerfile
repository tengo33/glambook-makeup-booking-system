# Use PHP 8.2 Apache
FROM php:8.2-apache

# Install system dependencies WITH PostgreSQL
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    libpq-dev postgresql-client

# Install PHP extensions WITH PostgreSQL
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer globally (faster)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install dependencies (skip scripts for now)
RUN composer install --no-dev --no-scripts --optimize-autoloader

# Run composer scripts separately
RUN composer run-script post-autoload-dump

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage \
    && chmod -R 775 /var/www/html/storage

# Create .env file with placeholders (Render will replace)
RUN echo "APP_ENV=production" > .env \
    && echo "APP_DEBUG=false" >> .env \
    && echo "DB_CONNECTION=pgsql" >> .env

EXPOSE 80
CMD ["apache2-foreground"]