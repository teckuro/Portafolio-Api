#!/bin/bash

echo "🚀 Iniciando Portfolio API (Fast Mode)..."

# Configurar Laravel rápidamente
php artisan key:generate --force
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link

echo "✅ Laravel configurado en modo rápido"
echo "🌐 Iniciando servidor PHP en puerto $PORT..."

# Usar PHP built-in server (más rápido que Apache)
exec php -S 0.0.0.0:$PORT -t public/
