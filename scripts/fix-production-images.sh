#!/bin/bash

echo "=== Fixing Production Images in Railway ==="

# Ejecutar comandos de Laravel
php artisan fix:production-images
php artisan storage:link
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Verificar que las rutas funcionan
echo "=== Testing Routes ==="
curl -I "https://web-production-eeecb.up.railway.app/api/files/projects/ecommerceplatform.svg"

echo "=== Fix Complete ==="
