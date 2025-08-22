# Portfolio API

API REST limpia y optimizada para el portafolio personal, construida con Laravel y Docker.

## 🚀 Características

-   ✅ **API REST completa** para proyectos y experiencia laboral
-   ✅ **Sistema de autenticación** con Laravel Sanctum
-   ✅ **Upload de archivos** con optimización automática
-   ✅ **Docker optimizado** para desarrollo y producción
-   ✅ **CORS configurado** para frontend Angular
-   ✅ **Rutas limpias y organizadas**
-   ✅ **Base de datos con seeders**

## 🛠️ Tecnologías

-   **Laravel 11** - Framework PHP
-   **MySQL 8.0** - Base de datos
-   **Docker & Docker Compose** - Containerización
-   **Apache** - Servidor web
-   **PHP 8.2** - Lenguaje backend

## 📦 Estructura del proyecto

```
api-portafolio/
├── app/
│   ├── Console/Commands/     # Comandos Artisan
│   ├── Http/Controllers/     # Controladores
│   ├── Models/              # Modelos Eloquent
│   └── Providers/           # Service Providers
├── database/
│   ├── migrations/          # Migraciones
│   └── seeders/            # Seeders
├── routes/
│   └── api.php             # Rutas API limpias
├── storage/
│   └── app/public/assets/  # Archivos subidos
├── Dockerfile              # Imagen Docker optimizada
├── docker-compose.yml      # Entorno de desarrollo
└── railway.toml           # Configuración Railway
```

## 🚀 Inicio rápido

### Opción 1: Docker (Recomendado)

#### Windows:

```bash
# Clonar e ir al directorio
cd api-portafolio

# Iniciar entorno de desarrollo
./start-dev.bat
```

#### Linux/Mac:

```bash
# Clonar e ir al directorio
cd api-portafolio

# Dar permisos y ejecutar
chmod +x start-dev.sh
./start-dev.sh
```

#### Manual con Docker:

```bash
# Construir e iniciar
docker-compose up -d --build

# Ver logs
docker-compose logs -f

# Detener
docker-compose down
```

### Opción 2: Instalación tradicional

```bash
# Instalar dependencias
composer install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=portfolio
DB_USERNAME=root
DB_PASSWORD=

# Ejecutar migraciones y seeders
php artisan migrate --seed

# Generar placeholders
php artisan images:generate-placeholders

# Iniciar servidor
php artisan serve --host=0.0.0.0 --port=8000
```

## 🌐 Endpoints principales

### Públicos

-   `GET /api/health` - Estado de la API
-   `GET /api/projects` - Lista de proyectos
-   `GET /api/projects/featured` - Proyectos destacados
-   `GET /api/works` - Experiencia laboral
-   `GET /api/works/current` - Trabajo actual

### Archivos

-   `GET /api/files/{path}` - Servir archivos
-   `GET /api/placeholder/{category}/{number}` - Placeholders

### Administración

-   `POST /api/admin/login` - Iniciar sesión
-   `POST /api/admin/upload` - Subir archivos
-   `GET /api/admin/projects/stats` - Estadísticas

### Protegidos (requieren autenticación)

-   `POST /api/admin/projects` - Crear proyecto
-   `PUT /api/admin/projects/{id}` - Actualizar proyecto
-   `DELETE /api/admin/projects/{id}` - Eliminar proyecto

## 📊 URLs de desarrollo

-   **API**: http://localhost:8000
-   **Health Check**: http://localhost:8000/api/health
-   **phpMyAdmin**: http://localhost:8080
-   **Documentación**: http://localhost:8000/api

## 🗄️ Base de datos

### Estructura principal:

-   `admin_users` - Usuarios administradores
-   `projects` - Proyectos del portafolio
-   `works` - Experiencia laboral

### Datos de prueba:

```sql
-- Usuario admin por defecto
email: admin@portfolio.com
password: admin123

-- Datos de ejemplo incluidos via seeders
```

## 🔧 Configuración

### Variables de entorno importantes:

```env
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
CORS_ALLOWED_ORIGINS=http://localhost:4200
UPLOAD_MAX_SIZE=5120
```

### CORS:

Configurado para permitir requests desde:

-   `http://localhost:4200` (Angular)
-   `http://localhost:3000` (React/Next.js)

## 🚀 Deployment en Railway

### Con Docker:

1. Conectar repositorio a Railway
2. Railway detectará automáticamente el `Dockerfile`
3. Configurar variables de entorno en Railway
4. Deploy automático

### Variables de entorno en Railway:

```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DATABASE_URL=${{MySQL.DATABASE_URL}}
```

## 🧹 Comandos útiles

### Docker:

```bash
# Ver logs en tiempo real
docker-compose logs -f app

# Ejecutar comandos Artisan
docker-compose exec app php artisan migrate

# Acceder al contenedor
docker-compose exec app bash

# Limpiar todo
docker-compose down -v --rmi all
```

### Laravel:

```bash
# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Generar placeholders
php artisan images:generate-placeholders

# Setup storage
php artisan storage:setup
```

## 📝 Notas importantes

-   ✅ Las rutas han sido **completamente limpiadas**
-   ✅ Se eliminaron **comandos innecesarios**
-   ✅ **Docker optimizado** para desarrollo y producción
-   ✅ **CORS configurado** correctamente
-   ✅ **Upload de archivos** funcionando
-   ✅ **Placeholders** implementados como fallback

## 🔍 Troubleshooting

### Problemas comunes:

1. **Puerto 8000 ocupado:**

    ```bash
    # Cambiar puerto en docker-compose.yml
    ports: - "8001:80"
    ```

2. **Permisos de storage:**

    ```bash
    docker-compose exec app chown -R www-data:www-data storage
    ```

3. **Base de datos no conecta:**
    ```bash
    # Verificar que el servicio db esté corriendo
    docker-compose ps
    ```

## 📄 Licencia

Este proyecto es privado y está destinado únicamente para uso personal del portafolio.
