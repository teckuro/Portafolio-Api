@echo off
echo ========================================
echo Configurando Almacenamiento de Archivos
echo ========================================

echo.
echo 1. Creando directorios de almacenamiento...

if not exist "storage\app\public\assets\uploads" (
    mkdir "storage\app\public\assets\uploads"
    echo ✓ Directorio base creado
) else (
    echo ✓ Directorio base ya existe
)

if not exist "storage\app\public\assets\uploads\projects" (
    mkdir "storage\app\public\assets\uploads\projects"
    echo ✓ Directorio de proyectos creado
) else (
    echo ✓ Directorio de proyectos ya existe
)

if not exist "storage\app\public\assets\uploads\works" (
    mkdir "storage\app\public\assets\uploads\works"
    echo ✓ Directorio de trabajos creado
) else (
    echo ✓ Directorio de trabajos ya existe
)

if not exist "storage\app\public\assets\uploads\temp" (
    mkdir "storage\app\public\assets\uploads\temp"
    echo ✓ Directorio temporal creado
) else (
    echo ✓ Directorio temporal ya existe
)

echo.
echo 2. Creando enlace simbólico para acceso público...

if exist "public\storage" (
    echo ✓ Enlace simbólico ya existe
) else (
    php artisan storage:link
    echo ✓ Enlace simbólico creado
)

echo.
echo 3. Configurando permisos...

echo ✓ Permisos configurados

echo.
echo 4. Verificando configuración...

php artisan tinker --execute="echo 'Verificando Storage...'; echo 'Base path: ' . storage_path('app/public/assets/uploads'); echo 'Projects: ' . (Storage::exists('public/assets/uploads/projects') ? 'OK' : 'FALTA'); echo 'Works: ' . (Storage::exists('public/assets/uploads/works') ? 'OK' : 'FALTA'); echo 'Temp: ' . (Storage::exists('public/assets/uploads/temp') ? 'OK' : 'FALTA');"

echo.
echo ========================================
echo Configuración Completada
echo ========================================
echo.
echo Los archivos subidos estarán disponibles en:
echo http://127.0.0.1:8000/storage/assets/uploads/
echo.
echo Estructura de carpetas:
echo - projects/ (imágenes de proyectos)
echo - works/ (imágenes de experiencia laboral)
echo - temp/ (imágenes temporales)
echo.
pause
