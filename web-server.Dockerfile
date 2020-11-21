FROM php:7.4-apache

# Install PHP PDO extension for MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite
