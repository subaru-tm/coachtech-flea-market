FROM php:7.4.9-fpm

COPY php.ini /usr/local/etc/php/

RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip \
  && docker-php-ext-install pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

RUN apt-get update && apt-get install -y libnss3-dev libxss1 libasound2 libxrandr2 libatk-bridge2.0-0 libcups2 libgtk-3-0 libgbm1

WORKDIR /var/www

RUN apt-get update \
  && apt-get install libfreetype6-dev libjpeg62-turbo-dev libpng-dev --assume-yes\
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd \