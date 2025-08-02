FROM php:8.3.21-fpm-alpine3.20

ENV NODE_ENV=production

WORKDIR /var/www/html

RUN apk add --no-cache git unzip autoconf make g++ icu-dev libzip-dev zlib-dev postgresql-dev libpq
RUN docker-php-ext-install pgsql pdo_pgsql intl zip
RUN pecl install mongodb 
RUN docker-php-ext-enable mongodb

COPY --from=composer:2.6 /usr/bin/composer /usr/local/bin/composer

COPY . /var/www/html/

EXPOSE 8000

RUN composer install --no-dev --optimize-autoloader

CMD ["php", "-S", "0.0.0.0:${PORT:-8000}", "router.php"]