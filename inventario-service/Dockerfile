# inventario-service/Dockerfile
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libzip-dev zip netcat-traditional \
 && docker-php-ext-install pdo_mysql pdo_pgsql zip \
 && rm -rf /var/lib/apt/lists/*



COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

COPY . .
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 8000
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan serve --host=0.0.0.0 --port=8000

    