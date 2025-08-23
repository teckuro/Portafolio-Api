@echo off
echo ========================================
echo Build optimizado de Docker
echo ========================================

echo.
echo 1. Limpiando cache de Docker...
docker builder prune -f

echo.
echo 2. Construyendo imagen con cache optimizado...
docker build --cache-from jhuerta20/api-portafolio:latest -t jhuerta20/api-portafolio:latest .

echo.
echo 3. Subiendo imagen a Docker Hub...
docker push jhuerta20/api-portafolio:latest

echo.
echo ✅ Build completado exitosamente
echo.
echo Para usar la imagen optimizada:
echo docker run --rm -p 8000:80 jhuerta20/api-portafolio:latest
