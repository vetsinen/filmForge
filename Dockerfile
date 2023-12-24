FROM php:8.1-apache

# Install system dependencies
RUN apt-get update \
    && apt-get install -y \
        libzip-dev \
        unzip \
        libonig-dev \
        libxml2-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mysqli zip mbstring exif pcntl bcmath


# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the composer.json and composer.lock files for better Docker caching
#COPY composer.json composer.lock /var/www/html/

# Install project dependencies
# RUN composer install --no-interaction --optimize-autoloader

# Copy the application code to the container
#COPY . /var/www/html/


# Expose port 80 for Apache
EXPOSE 80

# Command to run on container start
# CMD ["apache2-foreground"]
