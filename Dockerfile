FROM php:7.4.24-apache

RUN apt-get update
RUN docker-php-ext-install pdo_mysql
RUN apt install -y libgmp-dev
RUN docker-php-ext-install gmp