FROM php:8.1-fpm

RUN apt-get update && \
apt-get install -y \
git \
unzip \
libzip-dev \
libicu-dev

RUN docker-php-ext-install pdo_mysql zip intl

RUN pecl install apcu && docker-php-ext-enable apcu

WORKDIR /usr/src/app

COPY --chown=1000:1000 ./ /usr/src/app
