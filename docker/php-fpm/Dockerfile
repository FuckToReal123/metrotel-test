FROM php:5.5.38-fpm

WORKDIR /var/www

RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql

RUN sed -i '/jessie-updates/d' /etc/apt/sources.list

RUN apt-get update

RUN apt-get install -y libpng-dev \
    libfreetype6-dev \
    libjpeg-dev

RUN docker-php-ext-configure gd \
    --enable-gd-native-ttf \
    --with-freetype-dir=/usr/include/freetype2 \
    --with-png-dir=/usr/include \
    --with-jpeg-dir=/usr/include

RUN docker-php-ext-install gd

RUN chmod -R 777 /var/www
