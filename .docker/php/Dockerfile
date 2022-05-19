FROM php:8.1-fpm

ARG UID
ARG GID

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    default-mysql-client

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo_mysql

WORKDIR /var/www/html/app

RUN usermod -s /bin/bash www-data \
    && groupmod --non-unique --gid $GID www-data \
    && usermod --non-unique --uid $UID --gid $GID www-data

RUN chown -R www-data:www-data /var/www/html/app
