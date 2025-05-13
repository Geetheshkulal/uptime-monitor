
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

# Set working directory
WORKDIR /var/www/html

# Copy all project files
COPY . .

# Set correct permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache 

# Fix Apache to use Laravel public directory
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Add <Directory> block for public
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/laravel.conf && \
    a2enconf laravel

# Install Laravel dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copy .env file (Make sure .env exists locally or mount it via Render env vars)
COPY .env.example .env

# Generate app key and cache config (will fail if no DB/.env, that's okay for build)
RUN php artisan key:generate || true
RUN php artisan config:cache || true

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]


# FROM php:8.2-apache


# RUN apt-get update && apt-get install -y \
#     git unzip zip libzip-dev libpng-dev libonig-dev libxml2-dev curl \
#     && docker-php-ext-install pdo pdo_mysql zip gd


# RUN a2enmod rewrite

# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# WORKDIR /var/www/html


# COPY . .

# COPY .env.example .env


# RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf


# RUN echo '<Directory /var/www/html/public>\n\
#     Options Indexes FollowSymLinks\n\
#     AllowOverride All\n\
#     Require all granted\n\
# </Directory>' > /etc/apache2/conf-available/laravel.conf && \
#     a2enconf laravel

# RUN chown -R www-data:www-data /var/www/html \
#     && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache


# RUN composer install --no-interaction --prefer-dist --optimize-autoloader


# RUN php artisan key:generate
# RUN php artisan config:cache


# EXPOSE 80


# CMD ["apache2-foreground"]
