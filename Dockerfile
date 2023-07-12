# Use the official PHP image as the base image
FROM php:8.0-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy the codebase into the container
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install project dependencies
RUN composer install --optimize-autoloader --no-dev

# Generate the application key
RUN php artisan key:generate

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]