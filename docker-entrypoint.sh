#!/bin/bash

# Clear cached config so env vars from Render are used fresh
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache config with current env vars
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize
php artisan icons:cache

# Run database migrations
php artisan migrate --force

# Seed roles, permissions, and admin user
php artisan db:seed --force

# Create storage link
php artisan storage:link --force

# Set permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Start services via supervisor (nginx + php-fpm)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
