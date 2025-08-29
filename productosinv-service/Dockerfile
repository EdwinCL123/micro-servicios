FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones necesarias
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libzip-dev zip netcat-traditional \
 && docker-php-ext-install pdo_mysql pdo_pgsql zip \
 && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar composer.json primero para mejor caché
COPY composer.json composer.lock ./ 

# Instalar dependencias PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copiar el resto del código
COPY . .

# Dar permisos a storage y bootstrap
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 8000

# Arrancar Laravel con artisan serve
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan serve --host=0.0.0.0 --port=8000
