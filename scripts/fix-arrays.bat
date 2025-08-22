@echo off
echo ========================================
echo Arreglando arrays en la base de datos
echo ========================================
echo.

cd /d "%~dp0.."

echo Ejecutando comando para arreglar arrays de proyectos...
php artisan fix:project-arrays

echo.
echo Ejecutando comando para arreglar arrays de trabajos...
php artisan fix:work-arrays

echo.
echo ========================================
echo Proceso completado
echo ========================================
pause
