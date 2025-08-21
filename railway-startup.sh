#!/bin/bash

echo "🚀 Iniciando Portfolio API..."

# Configurar Laravel
php artisan key:generate --force
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link

echo "✅ Laravel configurado correctamente"
echo "🌐 Iniciando servidor Apache..."

# Iniciar Apache en el puerto correcto
echo "🌐 Iniciando Apache en puerto $PORT..."
exec vendor/bin/heroku-php-apache2 public/ -C apache.conf -p $PORT
