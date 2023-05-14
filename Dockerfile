FROM richarvey/nginx-php-fpm:2.0.4
# Use the official PHP image as the base image
FROM php:8.1-fpm-alpine

# Set the working directory
WORKDIR /var/www/html

# Install dependencies
RUN apk add --no-cache \
    nginx \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql gd zip \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Copy the application files to the container
COPY . .

# Copy the NGINX configuration file
COPY ./docker/nginx.conf /etc/nginx/nginx.conf

# Expose port 80
EXPOSE 80

# Start NGINX and PHP-FPM
CMD ["./start.sh"]
