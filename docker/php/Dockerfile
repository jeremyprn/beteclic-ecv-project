FROM composer:2.0 as composer

FROM php:7.4-fpm

RUN docker-php-ext-install pdo_mysql

RUN pecl install apcu

RUN apt-get update && \
apt-get install -y \
libzip-dev

RUN docker-php-ext-install zip
RUN docker-php-ext-enable apcu

RUN apt-get install git unzip wait-for-it wget libicu-dev -y && \
    docker-php-ext-install intl && \
    echo 'extension=intl.so' > /usr/local/etc/php/conf.d/docker-php-ext-intl.ini 


WORKDIR /usr/src/app

COPY --chown=1000:1000 ./ /usr/src/app

RUN PATH=$PATH:/usr/src/app/vendor/bin:bin


#RUN composer install --no-scripts --prefer-dist \
#  && rm -rf "$(composer config cache-dir)" "$(composer config data-dir)"

RUN echo "$(curl -sS https://composer.github.io/installer.sig) -" > composer-setup.php.sig \
      && curl -sS https://getcomposer.org/installer | tee composer-setup.php | sha384sum -c composer-setup.php.sig \
      && php composer-setup.php && rm composer-setup.php* \
      && chmod +x composer.phar && mv composer.phar /usr/bin/composer

RUN composer --version

#RUN composer install