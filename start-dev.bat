@echo off
echo 🚀 Iniciando entorno de desarrollo...

REM Verificar si Docker está instalado
docker --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Docker no está instalado
    pause
    exit /b 1
)

REM Verificar si docker-compose está instalado
docker-compose --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Docker Compose no está instalado
    pause
    exit /b 1
)

REM Crear directorio de storage si no existe
if not exist "storage\app\public\assets\uploads\projects" mkdir "storage\app\public\assets\uploads\projects"
if not exist "storage\app\public\assets\uploads\works" mkdir "storage\app\public\assets\uploads\works"
if not exist "storage\app\public\assets\uploads\temp" mkdir "storage\app\public\assets\uploads\temp"

REM Iniciar servicios
echo 📦 Construyendo contenedores...
docker-compose build

echo 🔄 Iniciando servicios...
docker-compose up -d

REM Esperar a que los servicios estén listos
echo ⏳ Esperando a que los servicios estén listos...
timeout /t 10 /nobreak >nul

REM Verificar estado de los servicios
echo 📊 Estado de los servicios:
docker-compose ps

echo ✅ Entorno de desarrollo listo!
echo.
echo 🌐 API disponible en: http://localhost:8000
echo 🗄️  phpMyAdmin disponible en: http://localhost:8080
echo.
echo Para detener: docker-compose down
echo Para ver logs: docker-compose logs -f
pause

