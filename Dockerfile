FROM php:5.6-fpm

RUN apt-get update -y && \
    apt-get install -y libjpeg-dev libpng-dev

RUN docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr; \
    docker-php-ext-install mysqli gd
