FROM php:8.2-apache

# Install dependencies WITH PostgreSQL client
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    libpq-dev postgresql-client

# Install PHP extensions WITH PostgreSQL
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set Apache document root to public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Create .env from example if not exists
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate application key
RUN php artisan key:generate

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage \
    && chmod -R 775 /var/www/html/storage
# Add this before CMD
RUN echo "<?php echo 'Basic PHP OK at ' . date('Y-m-d H:i:s'); \
    echo '<br>PHP Version: ' . phpversion(); \
    echo '<br>Extensions: ' . implode(', ', get_loaded_extensions()); \
    ?>" > /var/www/html/public/test.php


# Add this BEFORE CMD at the end
RUN echo "<?php \
require __DIR__ . '/../vendor/autoload.php';
\$app = require_once __DIR__ . '/../bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Http\Kernel::class);
\$response = \$kernel->handle(\$request = Illuminate\Http\Request::capture());

echo '<h2>Laravel Debug</h2>';
echo '<pre>';
echo '1. APP_KEY: ' . (\$app['config']['app.key'] ? 'SET' : 'MISSING') . '\n';
echo '2. DB Connection: ';
try {
    \$app['db']->connection()->getPdo();
    echo 'OK - ' . \$app['db']->connection()->getDatabaseName();
} catch (Exception \$e) {
    echo 'FAILED: ' . \$e->getMessage();
}
echo '\n3. Storage writable: ' . (is_writable(__DIR__ . '/../storage') ? 'Yes' : 'No');
echo '\n4. Extensions: pdo_mysql=' . extension_loaded('pdo_mysql') . ', pdo_pgsql=' . extension_loaded('pdo_pgsql');
?>" > /var/www/html/public/debug-laravel.php

# Replace the .env creation section with:
# Create .env with Render environment variables
RUN echo "APP_ENV=production" > .env \
    && echo "APP_DEBUG=false" >> .env \
    && echo "APP_KEY=" >> .env \
    && echo "DB_CONNECTION=pgsql" >> .env \
    && echo "# Database credentials will be injected by Render" >> .env

EXPOSE 80
CMD ["apache2-foreground"]