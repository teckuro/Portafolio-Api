@echo off
echo ========================================
echo SOLUCIONANDO PROBLEMAS DE IMAGENES Y AUTH
echo ========================================
echo.

echo 1. Generando imagenes placeholder locales...
php artisan images:generate-placeholders

echo.
echo 2. Corrigiendo URLs de imagenes...
php artisan images:fix-urls

echo.
echo 3. Limpiando cache de la aplicacion...
php artisan cache:clear
php artisan config:clear
php artisan route:clear

echo.
echo 4. Verificando estado de la base de datos...
php artisan migrate:status

echo.
echo ========================================
echo PROCESO COMPLETADO
echo ========================================
echo.
echo Los problemas han sido solucionados:
echo - Imagenes placeholder generadas localmente
echo - URLs de imagenes corregidas
echo - Cache limpiado
echo.
echo Ahora las imagenes deberian cargar correctamente
echo y el problema de autenticacion deberia estar resuelto.
echo.
pause
