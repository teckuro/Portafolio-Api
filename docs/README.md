# 📚 Documentación - Portfolio API

## 🚀 **Inicio Rápido**

### **1. Iniciar el Proyecto:**

```bash
scripts/start.bat
```

### **2. URLs de Acceso:**

-   **Backend API:** http://127.0.0.1:8000
-   **API Test:** http://127.0.0.1:8000/api/test
-   **Frontend:** http://localhost:4200

### **3. Credenciales por Defecto:**

-   **Email:** `admin@portfolio.com`
-   **Password:** `password123`

### **4. Detener Servidores:**

```bash
scripts/stop.bat
```

## 📋 **Endpoints Principales**

### **Públicos (Frontend):**

-   `GET /api/test` - Verificar API
-   `GET /api/portfolio/projects` - Listar proyectos
-   `GET /api/portfolio/works` - Listar experiencia laboral

### **Administración:**

-   `POST /api/admin/login` - Iniciar sesión
-   `GET /api/admin/projects` - Gestionar proyectos
-   `GET /api/admin/works` - Gestionar experiencia

## 🔧 **Requisitos**

-   **PHP 8.2+** (XAMPP recomendado)
-   **Node.js** (para Angular)
-   **Composer** (incluido)
-   **Angular CLI** (se instala automáticamente)

## 🚨 **Solución de Problemas**

### **Servidores no inician:**

```bash
scripts/clean.bat
scripts/start.bat
```

### **Errores de CORS:**

-   Verifica que el frontend esté en `http://localhost:4200`
-   Revisa `config/cors.php`

### **Errores de base de datos:**

```bash
C:\xampp\php\php.exe artisan migrate:fresh --seed
```

## 📞 **Soporte**

Si encuentras problemas:

1. Verifica que XAMPP esté instalado en `C:\xampp\`
2. Ejecuta `C:\xampp\php\php.exe artisan config:clear`
3. Revisa los logs en `storage/logs/laravel.log`

---

**¡Listo para desarrollar!** 🚀
