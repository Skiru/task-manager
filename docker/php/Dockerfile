###########################################################
FROM composer as composer-prod

WORKDIR /app

COPY /composer.json ./
COPY /composer.lock ./

RUN composer install --no-dev --no-scripts --ignore-platform-reqs --optimize-autoloader

###########################################################
FROM php:7.4-fpm-alpine

RUN apk add --no-cache \
    $PHPIZE_DEPS \
    sqlite-dev && \
    docker-php-ext-install bcmath && \
    docker-php-ext-enable bcmath && \
    docker-php-ext-install pdo pdo_sqlite && \
    docker-php-ext-enable pdo pdo_sqlite && \
    docker-php-ext-install opcache && \
    docker-php-ext-enable opcache && \
    rm -rf /tmp/pear

ENV APP_ENV=prod \
    APP_SECRET=ca6777ca3b9d07d5a38d4f68036f232d
    DATABASE_URL=sqlite:///%kernel.project_dir%/var/task.db \
    OAUTH_GOOGLE_ID=836716831376-p596ng37l4fvdqcdrb10l36balj70v8i.apps.googleusercontent.com \
    OAUTH_GOOGLE_SECRET=4g2sRN2PtDnlkwgs_ZFWR3zP

RUN  mkdir -p /var/www/html/var/log/

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

COPY ./ /var/www/html
COPY --from=composer-prod /app/vendor /var/www/html/vendor

RUN chmod 777 -R /var/www/html