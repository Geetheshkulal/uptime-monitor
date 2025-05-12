
# Use the official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpng-dev libonig-dev libxml2-dev curl \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy all project files
COPY . .

# Give proper permissions to storage and bootstrap cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Install Laravel dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Laravel setup (you can also run these manually or via CMD later)
RUN php artisan key:generate
RUN php artisan config:cache

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
