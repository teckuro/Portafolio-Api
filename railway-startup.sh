#!/bin/bash

# Copy the base environment file
cp railway-variables.env .env

# Check if Railway PostgreSQL variables exist, otherwise use SQLite
if [ -n "$PGHOST" ]; then
    echo "PostgreSQL detected, configuring database connection..."
    export DB_HOST=$PGHOST
    export DB_PORT=$PGPORT
    export DB_DATABASE=$PGDATABASE
    export DB_USERNAME=$PGUSER
    export DB_PASSWORD=$PGPASSWORD
    export DB_CONNECTION=pgsql
    
    # Update .env file with PostgreSQL settings
    sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=pgsql/" .env
    sed -i "s/DB_HOST=.*/DB_HOST=$PGHOST/" .env
    sed -i "s/DB_PORT=.*/DB_PORT=$PGPORT/" .env
    sed -i "s/DB_DATABASE=.*/DB_DATABASE=$PGDATABASE/" .env
    sed -i "s/DB_USERNAME=.*/DB_USERNAME=$PGUSER/" .env
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$PGPASSWORD/" .env
else
    echo "PostgreSQL not detected, using SQLite..."
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
echo "DB_CONNECTION: $DB_CONNECTION"

# Generate application key
echo "Generating application key..."
php artisan key:generate --force

# Test database connection before migrations
echo "Testing database connection..."
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connection successful'; } catch (Exception \$e) { echo 'Database connection failed: ' . \$e->getMessage(); exit(1); }"

if [ $? -ne 0 ]; then
    echo "Database connection failed. Exiting..."
    exit 1
fi

# Run migrations with better error handling
echo "Running migrations..."
php artisan migrate --force

if [ $? -ne 0 ]; then
    echo "Migrations failed. Exiting..."
    exit 1
fi

# Seed database with better error handling
echo "Seeding database..."
php artisan db:seed --force

if [ $? -ne 0 ]; then
    echo "Seeding failed. Exiting..."
    exit 1
fi

# Create storage link
echo "Creating storage link..."
php artisan storage:link

echo "Setup completed successfully!"

# Start the server
echo "Starting server..."
php -S 0.0.0.0:$PORT -t public/
