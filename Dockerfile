FROM php:8.4-cli

# System dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    zip \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    mbstring \
    zip \
    intl \
    bcmath \
    opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install dependencies (layer cache)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy source
COPY . .

# Create required Laravel runtime directories (may be absent or gitignored)
RUN mkdir -p \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache/data \
    storage/framework/testing \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Startup script
RUN chmod +x start.sh

EXPOSE 8000

CMD ["./start.sh"]
