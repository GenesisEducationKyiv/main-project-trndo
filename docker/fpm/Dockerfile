FROM php:8.2-fpm

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip librabbitmq-dev \
    && docker-php-ext-install intl opcache \
    && pecl install apcu \
    && pecl install amqp \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-enable amqp

RUN usermod -u 1000 www-data

WORKDIR /usr/src/app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /usr/src/app