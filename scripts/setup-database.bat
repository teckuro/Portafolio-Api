@echo off
echo ========================================
echo    CONFIGURANDO BASE DE DATOS
echo ========================================
echo.

REM Navegar al directorio del API
cd /d "%~dp0.."

echo Limpiando base de datos anterior...
php artisan migrate:fresh

echo.
echo Ejecutando migraciones...
php artisan migrate

echo.
echo Ejecutando seeders...
php artisan db:seed

echo.
echo ========================================
echo    BASE DE DATOS CONFIGURADA
echo ========================================
echo.
echo Los siguientes datos han sido creados:
echo - 4 proyectos de ejemplo
echo - 3 experiencias laborales de ejemplo
echo - Usuario administrador
echo.
echo API URL: http://127.0.0.1:8000/api
echo ========================================
