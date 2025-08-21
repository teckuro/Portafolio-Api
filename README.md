# 🎯 Portfolio API - Laravel Backend

API REST para portfolio personal desarrollada con Laravel 12. Proporciona endpoints para gestionar proyectos, experiencia laboral y autenticación de administradores.

## 🌟 **Características**

- ✅ **API REST completa** con Laravel 12
- ✅ **Autenticación** con Laravel Sanctum
- ✅ **Gestión de proyectos** (CRUD completo)
- ✅ **Experiencia laboral** (CRUD completo)
- ✅ **Upload de imágenes** para proyectos
- ✅ **Base de datos PostgreSQL** optimizada
- ✅ **CORS configurado** para frontend
- ✅ **Documentación completa** de endpoints
- ✅ **Seeders** con datos de ejemplo
- ✅ **Listo para producción** con Railway

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

### **Desarrollo Local**
- **API:** http://127.0.0.1:8000
- **Test:** http://127.0.0.1:8000/api/test
- **Documentación:** http://127.0.0.1:8000/docs

### **Producción**
- **API:** https://tu-api-railway.railway.app
- **Test:** https://tu-api-railway.railway.app/api/test

## 🔑 **Credenciales por Defecto**

### **Administrador**
- **Email:** `admin@portfolio.com`
- **Password:** `password123`

## 📚 **Endpoints Principales**

### **Públicos (Frontend)**
- `GET /api/test` - Verificar estado de la API
- `GET /api/portfolio/projects` - Listar todos los proyectos
- `GET /api/portfolio/projects/featured` - Proyectos destacados
- `GET /api/portfolio/projects/{id}` - Proyecto específico
- `GET /api/portfolio/works` - Listar experiencia laboral
- `GET /api/portfolio/works/current` - Experiencia actual

### **Administración**
- `POST /api/admin/login` - Iniciar sesión
- `POST /api/admin/register` - Registrar nuevo admin
- `POST /api/admin/logout` - Cerrar sesión
- `GET /api/admin/profile` - Perfil del admin

### **Gestión de Proyectos (Admin)**
- `GET /api/admin/projects` - Listar proyectos (admin)
- `POST /api/admin/projects` - Crear proyecto
- `PUT /api/admin/projects/{id}` - Actualizar proyecto
- `DELETE /api/admin/projects/{id}` - Eliminar proyecto
- `POST /api/admin/projects/{id}/toggle-featured` - Toggle destacado

### **Gestión de Experiencia (Admin)**
- `GET /api/admin/works` - Listar experiencia (admin)
- `POST /api/admin/works` - Crear experiencia
- `PUT /api/admin/works/{id}` - Actualizar experiencia
- `DELETE /api/admin/works/{id}` - Eliminar experiencia
- `POST /api/admin/works/{id}/toggle-current` - Toggle trabajo actual

### **Upload de Archivos**
- `POST /api/admin/upload` - Subir imagen
- `GET /api/admin/upload` - Listar archivos
- `DELETE /api/admin/upload/{filename}` - Eliminar archivo

## 🗄️ **Base de Datos**

### **Tablas Principales**
- `admin_users` - Usuarios administradores
- `projects` - Proyectos del portfolio
- `works` - Experiencia laboral
- `personal_access_tokens` - Tokens de autenticación

### **Relaciones**
- Proyectos pueden tener imágenes
- Experiencia laboral puede ser actual o pasada
- Proyectos pueden ser destacados

## 🔧 **Requisitos del Sistema**

### **Desarrollo**
- **PHP 8.2+**
- **Composer**
- **MySQL/PostgreSQL**
- **XAMPP** (recomendado)

### **Producción**
- **PHP 8.2+**
- **PostgreSQL** (Railway)
- **Composer**
- **Laravel 12**

## 🚀 **Despliegue en Producción**

### **Railway (Recomendado)**

1. **Conectar repositorio**
   - Ve a [railway.app](https://railway.app)
   - "Start a New Project" → "Deploy from GitHub repo"
   - Selecciona `teckuro/Portafolio-Api`

2. **Configurar base de datos**
   - "New" → "Database" → "PostgreSQL"
   - Railway configura automáticamente las variables

3. **Variables de entorno**
   ```env
   APP_NAME="Portfolio API"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://tu-proyecto-railway.railway.app
   DB_CONNECTION=pgsql
   DB_HOST=${PGHOST}
   DB_PORT=${PGPORT}
   DB_DATABASE=${PGDATABASE}
   DB_USERNAME=${PGUSER}
   DB_PASSWORD=${PGPASSWORD}
   FILESYSTEM_DISK=public
   ```

4. **Ejecutar comandos**
   ```bash
   php artisan key:generate
   php artisan migrate --force
   php artisan db:seed --force
   php artisan storage:link
   ```

## 📁 **Estructura del Proyecto**

```
api-portafolio/
├── app/
│   ├── Http/Controllers/Api/
│   │   ├── Admin/          # Controllers de administración
│   │   ├── ProjectController.php
│   │   └── WorkController.php
│   └── Models/             # Modelos Eloquent
├── config/
│   ├── cors.php           # Configuración CORS
│   └── production.php     # Configuración producción
├── database/
│   ├── migrations/        # Migraciones
│   └── seeders/          # Seeders con datos
├── docs/                 # Documentación
├── routes/
│   └── api.php          # Rutas de la API
├── scripts/             # Scripts de automatización
├── railway.json         # Configuración Railway
└── railway-deploy.sh    # Script de despliegue
```

## 🧪 **Testing**

### **Probar API Localmente**
```bash
# Verificar que la API funciona
curl http://127.0.0.1:8000/api/test

# Obtener proyectos
curl http://127.0.0.1:8000/api/portfolio/projects

# Obtener experiencia laboral
curl http://127.0.0.1:8000/api/portfolio/works
```

### **Script de Integración**
```bash
# Probar integración completa
test-integration.bat
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

#### **CORS errors**
- Verificar `config/cors.php`
- Reiniciar servidor

#### **Storage no funciona**
```bash
php artisan storage:link
```

## 📖 **Documentación Completa**

- **[API Documentation](docs/API_DOCUMENTATION.md)** - Documentación detallada de endpoints
- **[Estado de Implementación](docs/ESTADO_IMPLEMENTACION_IMAGENES.md)** - Estado actual del proyecto
- **[Mejoras Project Card](docs/MEJORAS_PROJECT_CARD.md)** - Mejoras implementadas
- **[Resumen Implementación](docs/RESUMEN_IMPLEMENTACION.md)** - Resumen del desarrollo

## 🤝 **Contribuir**

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 **Licencia**

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👨‍💻 **Autor**

**teckuro** - [GitHub](https://github.com/teckuro)

---

**¡Listo para usar!** 🚀

Si tienes alguna pregunta o problema, no dudes en abrir un issue en el repositorio.
