#!/bin/bash

# Clear cached config so env vars from Render are used fresh
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache config with current env vars
php artisan config:cache
php artisan route:cache

# Run database migrations
php artisan migrate --force

# Seed roles, permissions, and admin user
php artisan db:seed --force

# Create storage link
php artisan storage:link --force

# Start the server
exec php artisan serve --host=0.0.0.0 --port=8000
