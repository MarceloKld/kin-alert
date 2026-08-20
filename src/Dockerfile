FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    ca-certificates

# Aiven SSL
COPY docker/ssl/ca.pem /usr/local/share/ca-certificates/aiven-ca.crt
RUN update-ca-certificates

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy project
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Clear cache
RUN php artisan config:clear

# Make entrypoint executable
RUN chmod +x /var/www/entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["/var/www/entrypoint.sh"]

CMD ["php-fpm"]