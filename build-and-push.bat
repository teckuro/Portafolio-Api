@echo off
REM Script para construir y subir la imagen a Docker Hub
REM Uso: build-and-push.bat [version]

setlocal enabledelayedexpansion

REM Configuración
set DOCKER_USERNAME=jhuerta20
set IMAGE_NAME=portfolio-api
set VERSION=%1
if "%VERSION%"=="" set VERSION=latest

echo 🐳 Construyendo imagen Docker...
echo 📦 Usuario: %DOCKER_USERNAME%
echo 🏷️  Imagen: %IMAGE_NAME%
echo 📋 Versión: %VERSION%

REM Construir la imagen
echo 🔨 Construyendo imagen...
docker build -t %DOCKER_USERNAME%/%IMAGE_NAME%:%VERSION% .

REM Etiquetar como latest si no es la versión actual
if not "%VERSION%"=="latest" (
    echo 🏷️  Etiquetando como latest...
    docker tag %DOCKER_USERNAME%/%IMAGE_NAME%:%VERSION% %DOCKER_USERNAME%/%IMAGE_NAME%:latest
)

REM Subir a Docker Hub
echo 📤 Subiendo a Docker Hub...
docker push %DOCKER_USERNAME%/%IMAGE_NAME%:%VERSION%

if not "%VERSION%"=="latest" (
    echo 📤 Subiendo versión latest...
    docker push %DOCKER_USERNAME%/%IMAGE_NAME%:latest
)

echo ✅ ¡Imagen subida exitosamente!
echo 🌐 URL: https://hub.docker.com/r/%DOCKER_USERNAME%/%IMAGE_NAME%
echo 📋 Comando para usar: docker pull %DOCKER_USERNAME%/%IMAGE_NAME%:%VERSION%

pause
