#!/bin/bash

echo "🔍 DEBUG: Iniciando diagnóstico completo..."
echo "📋 Variables de entorno:"
echo "PORT: $PORT"
echo "PWD: $(pwd)"
echo "PHP version: $(php -v | head -1)"

echo "📦 Verificando dependencias..."
if [ -f "composer.json" ]; then
    echo "✅ composer.json encontrado"
else
    echo "❌ composer.json NO encontrado"
fi

echo "🗄️ Verificando base de datos..."
if [ -n "$DB_HOST" ]; then
    echo "✅ Variables de DB configuradas"
    echo "DB_HOST: $DB_HOST"
    echo "DB_DATABASE: $DB_DATABASE"
else
    echo "❌ Variables de DB NO configuradas"
fi

echo "🔧 Configurando Laravel..."
php artisan key:generate --force
echo "✅ APP_KEY generado"

echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force
echo "✅ Migraciones completadas"

echo "🌱 Ejecutando seeders..."
php artisan db:seed --force
echo "✅ Seeders completados"

echo "🔗 Creando enlace simbólico..."
php artisan storage:link
echo "✅ Storage link creado"

echo "🌐 Iniciando servidor simple en puerto $PORT..."
echo "🔍 El servidor estará disponible en: http://0.0.0.0:$PORT"

# Usar PHP built-in server para debug
exec php -S 0.0.0.0:$PORT -t public/
