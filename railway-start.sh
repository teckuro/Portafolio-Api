#!/bin/bash

echo "🚀 Iniciando Portfolio API en Railway..."

# Instalar dependencias
echo "📦 Instalando dependencias..."
composer install --no-dev --optimize-autoloader

# Generar APP_KEY si no existe
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generando APP_KEY..."
    php artisan key:generate
fi

# Limpiar cachés
echo "🧹 Limpiando cachés..."
php artisan config:clear
php artisan cache:clear

# Ejecutar migraciones
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

# Ejecutar seeders
echo "🌱 Ejecutando seeders..."
php artisan db:seed --force

# Crear enlace simbólico para storage
echo "🔗 Creando enlace simbólico..."
php artisan storage:link

echo "✅ Configuración completada!"
echo "🌐 Iniciando servidor en puerto $PORT..."

# Iniciar servidor
php artisan serve --host=0.0.0.0 --port=$PORT
