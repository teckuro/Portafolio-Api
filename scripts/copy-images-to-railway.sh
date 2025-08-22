#!/bin/bash

echo "=== Copying Images to Railway ==="

# Crear directorios en Railway si no existen
echo "Creating directories..."
mkdir -p storage/app/public/assets/uploads/projects
mkdir -p storage/app/public/assets/uploads/works
mkdir -p storage/app/public/assets/uploads/temp

# Copiar imágenes de proyectos
echo "Copying project images..."
if [ -d "storage/app/public/assets/uploads/projects" ]; then
    cp storage/app/public/assets/uploads/projects/* storage/app/public/assets/uploads/projects/ 2>/dev/null || echo "No project images to copy"
fi

# Copiar imágenes de trabajos
echo "Copying work images..."
if [ -d "storage/app/public/assets/uploads/works" ]; then
    cp storage/app/public/assets/uploads/works/* storage/app/public/assets/uploads/works/ 2>/dev/null || echo "No work images to copy"
fi

# Copiar imágenes temporales
echo "Copying temp images..."
if [ -d "storage/app/public/assets/uploads/temp" ]; then
    cp storage/app/public/assets/uploads/temp/* storage/app/public/assets/uploads/temp/ 2>/dev/null || echo "No temp images to copy"
fi

echo "=== Copy Complete ==="
echo "Testing image routes..."
php artisan test:image-routes
