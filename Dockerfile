FROM php:5.6-apache

RUN sed -i \
    -e 's|deb.debian.org/debian|archive.debian.org/debian|g' \
    -e 's|security.debian.org/debian-security|archive.debian.org/debian-security|g' \
    /etc/apt/sources.list \
    && sed -i '/stretch-updates/d' /etc/apt/sources.list \
    && apt-get update \
    && apt-get install -y --allow-unauthenticated \
        libgeoip-dev \
        geoip-database \
        geoip-database-extra \
    && pecl install geoip-1.1.1 \
    && docker-php-ext-enable geoip \
    && docker-php-ext-install mysql \
    && a2enmod rewrite \
    && echo 'date.timezone = Europe/Sofia' > /usr/local/etc/php/conf.d/timezone.ini