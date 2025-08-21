#!/bin/bash

# Copy the base environment file
cp railway-variables.env .env

# Export database environment variables to shell
export DB_HOST=$PGHOST
export DB_PORT=$PGPORT
export DB_DATABASE=$PGDATABASE
export DB_USERNAME=$PGUSER
export DB_PASSWORD=$PGPASSWORD

# Force HTTPS for Railway
export FORCE_HTTPS=true
export ASSET_URL=https://web-production-eeecb.up.railway.app

# Generate application key
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed --force

# Create storage link
php artisan storage:link

# Start the server
php -S 0.0.0.0:$PORT -t public/
