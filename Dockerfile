FROM php:7.4.24-apache

RUN apt-get update

ENV TZ CET
RUN printf '[PHP]\ndate.timezone = "CET"\n' > /usr/local/etc/php/conf.d/tzone.ini

RUN docker-php-ext-install pdo_mysql
RUN apt install -y libgmp-dev
RUN docker-php-ext-install gmp
RUN apt-get install -y zlib1g-dev libpng-dev
RUN docker-php-ext-install gd