FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libpng-dev libjpeg-dev libzip-dev

RUN docker-php-ext-install pdo pdo_pgsql zip gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --optimize-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]