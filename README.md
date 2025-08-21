# 🎯 Portfolio API - Laravel Backend

API REST para portfolio personal desarrollada con Laravel 12.

## 🚀 **Inicio Rápido**

```bash
scripts/start.bat
```

## 📋 **URLs**

-   **API:** http://127.0.0.1:8000
-   **Test:** http://127.0.0.1:8000/api/test

## 🔑 **Credenciales**

-   **Email:** `admin@portfolio.com`
-   **Password:** `password123`

## 📚 **Endpoints**

### Públicos

-   `GET /api/test` - Verificar API
-   `GET /api/portfolio/projects` - Listar proyectos
-   `GET /api/portfolio/works` - Listar experiencia laboral

### Administración

-   `POST /api/admin/login` - Iniciar sesión
-   `GET /api/admin/projects` - Gestionar proyectos
-   `GET /api/admin/works` - Gestionar experiencia

## 🔧 **Requisitos**

-   **PHP 8.2+** (XAMPP)
-   **Composer** (incluido)

## 🚨 **Solución de Problemas**

### Puerto ocupado:

```bash
netstat -ano | findstr :8000
taskkill /f /pid [PID]
```

### Base de datos:

```bash
C:\xampp\php\php.exe artisan migrate:fresh --seed
```

---

**¡Listo para usar!** 🚀
