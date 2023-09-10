FROM php:8.2.8-fpm

RUN apt-get update -y && apt-get install git -y

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
