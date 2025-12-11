#!/bin/bash

# Laravel Cloud Deployment Script
# This script ensures proper setup for production deployment

echo "Starting deployment setup..."

# Create storage link if it doesn't exist
if [ ! -L "public/storage" ]; then
    echo "Creating storage symlink..."
    php artisan storage:link
fi

# Clear and optimize caches
echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations if needed
echo "Running migrations..."
php artisan migrate --force

echo "Deployment setup complete!"
