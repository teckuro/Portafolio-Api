# 🎯 Portfolio API - Laravel Backend

API REST para portfolio personal desarrollada con Laravel 12.

## 🌟 **Características**

-   ✅ **API REST completa** con Laravel 12
-   ✅ **Autenticación** con Laravel Sanctum
-   ✅ **Gestión de proyectos** (CRUD completo)
-   ✅ **Experiencia laboral** (CRUD completo)
-   ✅ **Upload de imágenes** para proyectos
-   ✅ **Base de datos PostgreSQL** optimizada
-   ✅ **CORS configurado** para frontend
-   ✅ **Seeders** con datos de ejemplo

## 🚀 **Inicio Rápido**

### **Desarrollo Local**

```bash
# Clonar repositorio
git clone https://github.com/teckuro/Portafolio-Api.git
cd Portafolio-Api

# Instalar dependencias
composer install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos
php artisan migrate --seed

# Iniciar servidor
php artisan serve
```

### **Scripts Automatizados**

```bash
# Iniciar todo automáticamente
scripts/start-api.bat

# Configurar base de datos
scripts/setup-database.bat

# Configurar storage
scripts/setup-storage.bat
```

## 📋 **URLs de Acceso**

-   **API:** http://127.0.0.1:8000
-   **Test:** http://127.0.0.1:8000/api/test
-   **Health:** http://127.0.0.1:8000/health.php

## 🔑 **Credenciales por Defecto**

-   **Email:** `admin@portfolio.com`
-   **Password:** `password123`

## 📚 **Endpoints Principales**

### **Públicos (Frontend)**

-   `GET /api/test` - Verificar estado de la API
-   `GET /api/portfolio/projects` - Listar todos los proyectos
-   `GET /api/portfolio/projects/featured` - Proyectos destacados
-   `GET /api/portfolio/works` - Listar experiencia laboral

### **Administración**

-   `POST /api/admin/login` - Iniciar sesión
-   `GET /api/admin/projects` - Gestionar proyectos
-   `GET /api/admin/works` - Gestionar experiencia

## 🗄️ **Base de Datos**

### **Tablas Principales**

-   `admin_users` - Usuarios administradores
-   `projects` - Proyectos del portfolio
-   `works` - Experiencia laboral

## 🔧 **Requisitos**

-   **PHP 8.2+**
-   **Composer**
-   **MySQL/PostgreSQL**
-   **XAMPP** (recomendado)

## 🚀 **Despliegue en Railway**

1. **Conectar repositorio** en [railway.app](https://railway.app)
2. **Agregar base de datos** PostgreSQL
3. **Configurar variables de entorno**:
    ```env
    APP_NAME="Portfolio API"
    APP_ENV=production
    APP_DEBUG=false
    DB_CONNECTION=pgsql
    DB_HOST=${PGHOST}
    DB_PORT=${PGPORT}
    DB_DATABASE=${PGDATABASE}
    DB_USERNAME=${PGUSER}
    DB_PASSWORD=${PGPASSWORD}
    ```

## 🧪 **Testing**

```bash
# Verificar que la API funciona
curl http://127.0.0.1:8000/api/test

# Obtener proyectos
curl http://127.0.0.1:8000/api/portfolio/projects

# Obtener experiencia laboral
curl http://127.0.0.1:8000/api/portfolio/works
```

## 🚨 **Solución de Problemas**

### **Errores Comunes**

#### **Puerto ocupado**

```bash
netstat -ano | findstr :8000
taskkill /f /pid [PID]
```

#### **Base de datos**

```bash
php artisan migrate:fresh --seed
```

#### **Storage no funciona**

```bash
php artisan storage:link
```

## 👨‍💻 **Autor**

**teckuro** - [GitHub](https://github.com/teckuro)

---

**¡Listo para usar!** 🚀
