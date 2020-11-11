FROM php:7.4-fpm

RUN curl -s -f -L -o /tmp/installer.php https://getcomposer.org/installer \
    && php /tmp/installer.php --no-ansi --install-dir=/usr/bin --filename=composer \
    && rm /tmp/installer.php \
    && composer --ansi --version  --no-interaction

RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev libzip-dev \
        && docker-php-ext-install zip intl

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmemcached-dev \
        curl \
        libpng-dev \
        libcurl4-openssl-dev \
        pkg-config \
        libssl-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install pdo_mysql bcmath

# ImageMagick
RUN export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" \
    && apt-get update \
    && apt-get install -y libmagickwand-dev \
    && rm -rf /var/lib/apt/lists/* \
    && pecl install imagick-3.4.3 \
    && docker-php-ext-enable imagick

RUN mkdir -p /run/php

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN echo "alias phpunit='php /var/www/vendor/bin/phpunit'" >> /root/.bashrc

WORKDIR /var/www
