#!/bin/bash

echo "=== Railway Startup Script ==="
echo "Starting at: $(date)"

# Función para manejar errores
handle_error() {
    echo "Error occurred in line $1"
    exit 1
}

# Configurar manejo de errores
trap 'handle_error $LINENO' ERR

# Verificar que estamos en el directorio correcto
echo "Current directory: $(pwd)"
echo "Files in directory: $(ls -la)"

# Copiar variables de entorno si existe
if [ -f "railway-variables.env" ]; then
    echo "Copying environment variables..."
    cp railway-variables.env .env
fi

# Generar clave de aplicación si no existe
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Verificar conexión a base de datos
echo "Testing database connection..."
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connection successful'; } catch (Exception \$e) { echo 'Database connection failed: ' . \$e->getMessage(); exit(1); }"

# Ejecutar migraciones
echo "Running migrations..."
php artisan migrate --force

# Ejecutar seeders
echo "Running seeders..."
php artisan db:seed --force

# Corregir imágenes
echo "Fixing production images..."
php artisan fix:production-images

# Crear enlace simbólico de storage
echo "Creating storage link..."
php artisan storage:link

# Limpiar cache
echo "Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Verificar estado
echo "Testing image routes..."
php artisan test:image-routes

# Verificar que el servidor puede iniciar
echo "Testing server startup..."
timeout 10s php -S 0.0.0.0:8000 -t public/ || echo "Server test completed"

echo "=== Startup Complete at $(date) ==="
echo "Starting production server..."

# Iniciar el servidor de producción
exec php -S 0.0.0.0:$PORT -t public/
