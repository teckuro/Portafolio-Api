@echo off
echo ========================================
echo    PRUEBA DE INTEGRACION API + FRONTEND
echo ========================================
echo.

REM Verificar backend
echo Probando Backend Laravel...
curl -s http://127.0.0.1:8000/api/test >nul 2>&1
if errorlevel 1 (
    echo ERROR: Backend no responde
    echo Asegúrate de ejecutar: cd api-portafolio && scripts\start.bat
    pause
    exit /b 1
)
echo Backend funcionando ✓

REM Verificar frontend
echo Probando Frontend Angular...
curl -s http://127.0.0.1:4200 >nul 2>&1
if errorlevel 1 (
    echo ERROR: Frontend no responde
    echo Asegúrate de ejecutar: ng serve --port 4200 --host 127.0.0.1
    pause
    exit /b 1
)
echo Frontend funcionando ✓

REM Probar API de proyectos
echo.
echo Probando API de proyectos...
curl -s http://127.0.0.1:8000/api/portfolio/projects >nul 2>&1
if errorlevel 1 (
    echo ERROR: API de proyectos no responde
    pause
    exit /b 1
)
echo API de proyectos funcionando ✓

REM Probar API de experiencia laboral
echo Probando API de experiencia laboral...
curl -s http://127.0.0.1:8000/api/portfolio/works >nul 2>&1
if errorlevel 1 (
    echo ERROR: API de experiencia laboral no responde
    pause
    exit /b 1
)
echo API de experiencia laboral funcionando ✓

echo.
echo ========================================
echo    INTEGRACION EXITOSA
echo ========================================
echo.
echo Backend API: http://127.0.0.1:8000/api
echo Frontend: http://127.0.0.1:4200
echo.
echo Ambos servicios están funcionando correctamente.
echo El frontend puede comunicarse con el backend.
echo.
pause
