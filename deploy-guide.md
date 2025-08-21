# 🚀 Guía Completa de Despliegue - Portfolio API

## 📋 **Requisitos Previos**

1. **Cuenta en GitHub** (gratuita)
2. **Cuenta en Railway** (gratuita) - [railway.app](https://railway.app)
3. **Cuenta en Vercel** (gratuita) - [vercel.com](https://vercel.com)
4. **Git instalado** en tu computadora

## 🔧 **Paso 1: Preparar el Repositorio**

### 1.1 Subir código a GitHub

```bash
# En tu carpeta del proyecto
git init
git add .
git commit -m "Initial commit - Portfolio API ready for deployment"
git branch -M main
git remote add origin https://github.com/TU_USUARIO/TU_REPO.git
git push -u origin main
```

### 1.2 Verificar archivos necesarios

Asegúrate de tener estos archivos en tu repositorio:
- ✅ `railway.json` (configuración Railway)
- ✅ `vercel.json` (configuración Vercel)
- ✅ `Procfile` (para Heroku/Railway)
- ✅ `composer.json` (dependencias PHP)
- ✅ `package.json` (dependencias Angular)

## 🚂 **Paso 2: Desplegar Backend en Railway**

### 2.1 Crear proyecto en Railway

1. Ve a [railway.app](https://railway.app)
2. Haz clic en "Start a New Project"
3. Selecciona "Deploy from GitHub repo"
4. Conecta tu cuenta de GitHub
5. Selecciona tu repositorio
6. Haz clic en "Deploy Now"

### 2.2 Configurar Base de Datos

1. En tu proyecto Railway, haz clic en "New"
2. Selecciona "Database" → "PostgreSQL"
3. Railway creará automáticamente las variables de entorno

### 2.3 Configurar Variables de Entorno

En tu proyecto Railway, ve a "Variables" y agrega:

```env
APP_NAME="Portfolio API"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-proyecto-railway.railway.app

# Base de datos (Railway las genera automáticamente)
DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}

# Archivos
FILESYSTEM_DISK=public

# CORS (agregar después de desplegar el frontend)
CORS_ALLOWED_ORIGINS=https://tu-frontend-vercel.vercel.app
```

### 2.4 Generar APP_KEY

1. En Railway, ve a "Deployments"
2. Haz clic en el último deployment
3. Ve a "Logs"
4. Ejecuta este comando en la terminal:

```bash
php artisan key:generate
```

### 2.5 Ejecutar Migraciones

En la terminal de Railway:

```bash
php artisan migrate --force
php artisan db:seed --force
```

### 2.6 Verificar API

Tu API estará disponible en:
`https://tu-proyecto-railway.railway.app/api/test`

## ⚡ **Paso 3: Desplegar Frontend en Vercel**

### 3.1 Crear proyecto en Vercel

1. Ve a [vercel.com](https://vercel.com)
2. Haz clic en "New Project"
3. Conecta tu cuenta de GitHub
4. Selecciona tu repositorio
5. Configura:
   - **Framework Preset**: Other
   - **Build Command**: `ng build --configuration=production`
   - **Output Directory**: `dist/angular-portafolio`
   - **Install Command**: `npm install`

### 3.2 Configurar Variables de Entorno

En Vercel, ve a "Settings" → "Environment Variables":

```env
API_URL=https://tu-proyecto-railway.railway.app/api
```

### 3.3 Configurar Angular para Producción

Crea el archivo `src/environments/environment.prod.ts`:

```typescript
export const environment = {
  production: true,
  apiUrl: 'https://tu-proyecto-railway.railway.app/api'
};
```

### 3.4 Actualizar angular.json

Asegúrate de que tu `angular.json` tenga:

```json
{
  "projects": {
    "angular-portafolio": {
      "architect": {
        "build": {
          "configurations": {
            "production": {
              "fileReplacements": [
                {
                  "replace": "src/environments/environment.ts",
                  "with": "src/environments/environment.prod.ts"
                }
              ]
            }
          }
        }
      }
    }
  }
}
```

### 3.5 Desplegar

1. Haz commit y push de los cambios
2. Vercel detectará automáticamente los cambios
3. Desplegará automáticamente

## 🔗 **Paso 4: Conectar Frontend y Backend**

### 4.1 Actualizar CORS en Railway

Una vez que tengas la URL de Vercel, actualiza las variables de entorno en Railway:

```env
CORS_ALLOWED_ORIGINS=https://tu-frontend-vercel.vercel.app
```

### 4.2 Verificar Conexión

1. Ve a tu frontend en Vercel
2. Abre las herramientas de desarrollador (F12)
3. Ve a la pestaña "Network"
4. Recarga la página
5. Verifica que las llamadas a la API funcionen

## 🧪 **Paso 5: Pruebas Finales**

### 5.1 Probar Endpoints

```bash
# Test API
curl https://tu-proyecto-railway.railway.app/api/test

# Proyectos
curl https://tu-proyecto-railway.railway.app/api/portfolio/projects

# Experiencia laboral
curl https://tu-proyecto-railway.railway.app/api/portfolio/works
```

### 5.2 Probar Frontend

1. Ve a tu URL de Vercel
2. Verifica que se carguen los proyectos
3. Verifica que se carguen las imágenes
4. Prueba la navegación

## 🚨 **Solución de Problemas**

### Error de CORS
- Verifica que las URLs estén correctas en `config/cors.php`
- Reinicia el deployment en Railway

### Error de Base de Datos
- Verifica las variables de entorno en Railway
- Ejecuta `php artisan migrate:fresh --seed` en Railway

### Error de Build en Vercel
- Verifica que `package.json` tenga las dependencias correctas
- Verifica que `angular.json` esté configurado correctamente

### Imágenes no se cargan
- Verifica que `storage:link` esté ejecutado en Railway
- Verifica las URLs de las imágenes en la base de datos

## 📞 **Soporte**

Si tienes problemas:

1. Revisa los logs en Railway y Vercel
2. Verifica las variables de entorno
3. Asegúrate de que todos los archivos estén en el repositorio

## 🎉 **¡Listo!**

Tu portfolio estará disponible en:
- **Frontend**: `https://tu-frontend-vercel.vercel.app`
- **Backend**: `https://tu-proyecto-railway.railway.app/api`

¡Tu portfolio está ahora en producción! 🚀
