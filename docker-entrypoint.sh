#!/bin/bash

# Create SQLite database if it doesn't exist
touch /tmp/database.sqlite

# Clear config cache (so env vars are picked up)
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache config with current env vars
php artisan config:cache
php artisan route:cache

# Run database migrations
php artisan migrate --force

# Create storage link
php artisan storage:link --force

# Start the server
exec php artisan serve --host=0.0.0.0 --port=8000
