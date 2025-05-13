FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    exif \
    unzip \
    libzip-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql zip intl mbstring

RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install gd
