FROM php:8.2-fpm-alpine

RUN apk add --no-cache git curl zip libpq

RUN docker-php-ext-install pdo pdo_pgsql zip

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
