FROM php:7.4-fpm

RUN apt-get update && apt-get install -y libpq-dev git unzip zlib1g-dev libzip-dev libjpeg62-turbo-dev libmcrypt-dev libpng-dev libonig-dev
RUN docker-php-ext-install pdo pdo_pgsql zip bcmath opcache
RUN docker-php-ext-enable pdo pdo_pgsql zip bcmath opcache
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY .env.dist .env

ENV APP_ENV=prod \
    APP_SECRET=ca6777ca3b9d07d5a38d4f68036f232d 

COPY ./composer.json ./
COPY ./composer.lock ./
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

RUN composer install --no-dev --no-scripts --ignore-platform-reqs --optimize-autoloader --prefer-dist

RUN  mkdir -p /var/www/html/var/log/
COPY ./ /var/www/html

RUN chmod 777 -R /var/www/html
