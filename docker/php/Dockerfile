FROM composer as composer-dev
WORKDIR /app
COPY ./composer.json ./
COPY ./composer.lock ./
RUN composer install --no-scripts --ignore-platform-reqs

FROM php:7.4-fpm-alpine as ci-dev
WORKDIR /var/www/html
COPY --from=composer-dev /app/vendor ./vendor
COPY /bin ./bin
COPY /config ./config
COPY /data ./data
COPY /public ./public
COPY /src ./src
COPY /templates ./templates
COPY /tests ./tests
COPY /composer.json ./
COPY /composer.lock ./
COPY /.env.dist ./.env.dist
COPY /.env.test ./.env.test
COPY /phpunit.xml.dist ./phpunit.xml.dist
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
RUN mkdir -p /var/www/html/var/log/

FROM composer as composer-prod
WORKDIR /app
COPY /composer.json ./
COPY /composer.lock ./
RUN composer install --no-dev --no-scripts --ignore-platform-reqs --optimize-autoloader

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
ENV APP_ENV="prod" \
    APP_SECRET="ca6777ca3b9d07d5a38d4f68036f232d" \
    DATABASE_URL="sqlite:////var/www/html/data/task.sqlite" \
    OAUTH_GOOGLE_ID="836716831376-p596ng37l4fvdqcdrb10l36balj70v8i.apps.googleusercontent.com" \
    OAUTH_GOOGLE_SECRET="4g2sRN2PtDnlkwgs_ZFWR3zP"
RUN  mkdir -p /var/www/html/var/log/
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./ /var/www/html
COPY --from=composer-prod /app/vendor /var/www/html/vendor
RUN chmod 777 -R /var/www/html
RUN chown www-data:www-data /var/www/html/data/task.sqlite