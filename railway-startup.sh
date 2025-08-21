#!/bin/bash

# Copy the base environment file
cp railway-variables.env .env

# Check if Railway PostgreSQL variables exist, otherwise use SQLite
if [ -n "$PGHOST" ]; then
    export DB_HOST=$PGHOST
    export DB_PORT=$PGPORT
    export DB_DATABASE=$PGDATABASE
    export DB_USERNAME=$PGUSER
    export DB_PASSWORD=$PGPASSWORD
    export DB_CONNECTION=pgsql
else
    # Use SQLite as fallback
    export DB_CONNECTION=sqlite
    export DB_DATABASE=database/database.sqlite
    # Create SQLite database file
    mkdir -p database
    touch database/database.sqlite
fi

# Force HTTPS for Railway
export FORCE_HTTPS=true
export ASSET_URL=https://web-production-eeecb.up.railway.app

# Debug: Print database connection info (without password)
echo "Database connection info:"
echo "PGHOST: $PGHOST"
echo "PGPORT: $PGPORT"
echo "PGDATABASE: $PGDATABASE"
echo "PGUSER: $PGUSER"
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"

# Generate application key
php artisan key:generate --force

# Test database connection before migrations
echo "Testing database connection..."
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connection successful'; } catch (Exception \$e) { echo 'Database connection failed: ' . \$e->getMessage(); }"

# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed --force

# Create storage link
php artisan storage:link

# Start the server
php -S 0.0.0.0:$PORT -t public/
