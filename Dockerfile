FROM php:8.2-cli

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    libzip-dev \
    libicu-dev

RUN docker-php-ext-configure intl && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . .

# Install PHP dependencies and build Vite assets
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN npm install && npm run build

# Permissions
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 8080

# Run migrations, seeder, and serve application
CMD php artisan storage:link || true; php artisan migrate --force; php artisan db:seed --force; php artisan serve --host=0.0.0.0 --port=8080
