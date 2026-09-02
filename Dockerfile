FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files and install
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy application files
COPY . .

# Create SQLite database
RUN touch /tmp/database.sqlite

# Generate autoload
RUN composer dump-autoload --optimize

# Build frontend with correct asset URL
ARG APP_URL=https://esl-1.onrender.com
ENV APP_URL=${APP_URL}
ENV ASSET_URL=${APP_URL}
RUN npm install && ASSET_URL=${APP_URL} npm run build

# Storage permissions
RUN chmod -R 775 storage bootstrap/cache
RUN mkdir -p storage/framework/{sessions,views,cache}
RUN mkdir -p storage/logs
RUN touch storage/logs/laravel.log

EXPOSE 8000

# Use entrypoint script to run migrations at startup
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

CMD ["docker-entrypoint.sh"]
