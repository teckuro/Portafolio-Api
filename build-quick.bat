@echo off
echo ========================================
echo Build rápido para cambios pequeños
echo ========================================

echo.
echo 1. Construyendo imagen con cache...
docker build --cache-from jhuerta20/api-portafolio:latest -t jhuerta20/api-portafolio:latest .

echo.
echo 2. Subiendo imagen a Docker Hub...
docker push jhuerta20/api-portafolio:latest

echo.
echo ✅ Build rápido completado
echo.
echo Tiempo estimado: 2-3 minutos (vs 5-10 minutos sin cache)
