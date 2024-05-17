# Use the official PHP image with Apache
FROM php:8.0.12-apache

# Enable the mysqli extension
RUN docker-php-ext-install mysqli && \
    docker-php-ext-enable mysqli

# Your additional configurations can go here if needed

# Start Apache service
CMD ["apache2-foreground"]
