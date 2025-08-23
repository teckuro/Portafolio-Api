@echo off
echo ========================================
echo Redeploy en Railway
echo ========================================

echo.
echo 1. Haciendo push de cambios...
git add .
git commit -m "auto: redeploy $(date /t)"
git push

echo.
echo 2. Abriendo Railway Dashboard...
start https://railway.app/dashboard

echo.
echo ✅ Cambios enviados a GitHub
echo.
echo Ahora ve a Railway Dashboard y haz clic en "Deploy"
echo O configura redeploy automático en Settings > GitHub Integration
