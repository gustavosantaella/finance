FROM richarvey/nginx-php-fpm:2.0.4
FROM php:8.0.0RC5-fpm-alpine3.12

# Install dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libzip-dev \
    unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr


# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1


CMD ["php-fpm", "composer install", "/start.sh"]
