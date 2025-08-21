@echo off
echo ========================================
echo    INICIANDO API LARAVEL
echo ========================================
echo.

REM Navegar al directorio del API
cd /d "%~dp0.."

echo Verificando dependencias...
composer install

echo.
echo Iniciando servidor API...
php artisan serve --host=127.0.0.1 --port=8000

echo.
echo ========================================
echo    API INICIADO
echo ========================================
echo API URL: http://127.0.0.1:8000
echo API Endpoints: http://127.0.0.1:8000/api
echo ========================================
