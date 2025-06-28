FROM php:8.2-apache

# Copy your PHP files into the Apache server root
COPY . /var/www/html/

# Enable URL rewriting (optional, for more advanced bots)
RUN a2enmod rewrite
