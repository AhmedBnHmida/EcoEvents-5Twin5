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
    libzip-dev \
    libpq-dev \
    supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip pdo_pgsql

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user
RUN useradd -G www-data,root -u 1000 -d /home/laravel laravel
RUN mkdir -p /home/laravel/.composer && \
    chown -R laravel:laravel /home/laravel

# Set working directory
WORKDIR /var/www

COPY --chown=laravel:laravel . .

# Copy supervisor config
COPY docker/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

USER laravel

# Install dependencies (will be cached in layers)
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]