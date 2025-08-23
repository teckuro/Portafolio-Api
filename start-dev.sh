#!/bin/bash

# Script para iniciar el entorno de desarrollo

echo "🚀 Iniciando entorno de desarrollo..."

# Verificar si Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker no está instalado"
    exit 1
fi

# Verificar si docker-compose está instalado
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose no está instalado"
    exit 1
fi

# Crear directorio de storage si no existe
mkdir -p storage/app/public/assets/uploads/{projects,works,temp}

# Iniciar servicios
echo "📦 Construyendo contenedores..."
docker-compose build

echo "🔄 Iniciando servicios..."
docker-compose up -d

# Esperar a que los servicios estén listos
echo "⏳ Esperando a que los servicios estén listos..."
sleep 10

# Verificar estado de los servicios
echo "📊 Estado de los servicios:"
docker-compose ps

echo "✅ Entorno de desarrollo listo!"
echo ""
echo "🌐 API disponible en: http://localhost:8000"
echo "🗄️  phpMyAdmin disponible en: http://localhost:8080"
echo ""
echo "Para detener: docker-compose down"
echo "Para ver logs: docker-compose logs -f"

