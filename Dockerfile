FROM webdevops/php-nginx:8.2
WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan view:clear
RUN php artisan key:generate

EXPOSE 8080
