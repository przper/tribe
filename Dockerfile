FROM composer:2.6.4 as composer-install
WORKDIR /var/www/html
COPY composer.json composer.lock symfony.lock ./
RUN composer install --no-dev --no-scripts

FROM php:8.3-apache as web
RUN apt-get update && apt-get install -y \
    # needed for intl php module
    libicu-dev
RUN docker-php-ext-install \
    bcmath \
    intl \
    mysqli \
    opcache
RUN a2enmod rewrite && a2enmod headers && a2enmod expires
COPY docker/apache/000-default.conf /etc/apache2/sites-enabled/000-default.conf
COPY docker/apache/apache.conf /etc/apache2/conf-enabled/apache.conf
COPY docker/php/php.base.ini $PHP_INI_DIR/conf.d/
WORKDIR /var/www/html
COPY . ./
COPY --from=composer-install /var/www/html/vendor vendor/
RUN mkdir -p var/cache var/log
ENV APP_ENV=prod
RUN bin/console assets:install
RUN chown --recursive www-data:www-data var/