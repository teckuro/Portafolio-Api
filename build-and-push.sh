#!/bin/bash

# Script para construir y subir la imagen a Docker Hub
# Uso: ./build-and-push.sh [version]

set -e

# Configuración
DOCKER_USERNAME="jhuerta20"
IMAGE_NAME="portfolio-api"
VERSION=${1:-latest}

echo "🐳 Construyendo imagen Docker..."
echo "📦 Usuario: $DOCKER_USERNAME"
echo "🏷️  Imagen: $IMAGE_NAME"
echo "📋 Versión: $VERSION"

# Construir la imagen
echo "🔨 Construyendo imagen..."
docker build -t $DOCKER_USERNAME/$IMAGE_NAME:$VERSION .

# Etiquetar como latest si no es la versión actual
if [ "$VERSION" != "latest" ]; then
    echo "🏷️  Etiquetando como latest..."
    docker tag $DOCKER_USERNAME/$IMAGE_NAME:$VERSION $DOCKER_USERNAME/$IMAGE_NAME:latest
fi

# Subir a Docker Hub
echo "📤 Subiendo a Docker Hub..."
docker push $DOCKER_USERNAME/$IMAGE_NAME:$VERSION

if [ "$VERSION" != "latest" ]; then
    echo "📤 Subiendo versión latest..."
    docker push $DOCKER_USERNAME/$IMAGE_NAME:latest
fi

echo "✅ ¡Imagen subida exitosamente!"
echo "🌐 URL: https://hub.docker.com/r/$DOCKER_USERNAME/$IMAGE_NAME"
echo "📋 Comando para usar: docker pull $DOCKER_USERNAME/$IMAGE_NAME:$VERSION"
