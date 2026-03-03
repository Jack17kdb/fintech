# Base image with PHP 8.2 and Apache
FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libicu-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install mysqli pdo pdo_mysql zip intl

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set Apache document root to /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Copy project files (including vendor)
COPY . /var/www/html

# Set permissions for writable folders
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/writable

EXPOSE 80
