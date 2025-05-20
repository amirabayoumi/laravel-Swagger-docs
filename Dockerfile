FROM php:8.2-cli

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git zip unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev libsqlite3-dev mariadb-client \
    && docker-php-ext-install pdo pdo_mysql zip mbstring xml tokenizer ctype fileinfo

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files separately for better caching
COPY composer.json composer.lock ./

# Increase PHP memory limit temporarily to avoid composer out-of-memory errors
ENV COMPOSER_MEMORY_LIMIT=-1

# Install composer dependencies with verbose output
RUN composer install --no-dev --optimize-autoloader --no-interaction --verbose

# Copy the rest of your app files
COPY . .

# Expose port and run Laravel dev server
EXPOSE 8080
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
