# 🐳 Docker Hub - Portfolio API

## 📦 Repositorio Docker Hub

**URL:** https://hub.docker.com/r/jhuerta20/api-portafolio

## 🚀 Uso rápido

### Pull de la imagen:

```bash
docker pull jhuerta20/api-portafolio:latest
```

### Ejecutar con Docker:

```bash
docker run -d \
  --name portfolio-api \
  -p 8000:80 \
  -e APP_ENV=production \
  -e DB_CONNECTION=mysql \
  -e DB_HOST=your-db-host \
  -e DB_DATABASE=portfolio \
  -e DB_USERNAME=your-username \
  -e DB_PASSWORD=your-password \
  jhuerta20/api-portafolio:latest
```

### Con Docker Compose:

```bash
# Usar el docker-compose.yml incluido
docker-compose up -d
```

## 🔨 Construir y subir nueva versión

### Windows:

```bash
# Construir y subir versión latest
./build-and-push.bat

# Construir y subir versión específica
./build-and-push.bat v1.0.0
```

### Linux/Mac:

```bash
# Dar permisos
chmod +x build-and-push.sh

# Construir y subir versión latest
./build-and-push.sh

# Construir y subir versión específica
./build-and-push.sh v1.0.0
```

## 📋 Variables de entorno

### Requeridas:

-   `APP_KEY` - Clave de aplicación Laravel
-   `DB_CONNECTION` - Tipo de base de datos (mysql, sqlite, etc.)
-   `DB_HOST` - Host de la base de datos
-   `DB_DATABASE` - Nombre de la base de datos
-   `DB_USERNAME` - Usuario de la base de datos
-   `DB_PASSWORD` - Contraseña de la base de datos

### Opcionales:

-   `APP_ENV` - Entorno (local, production)
-   `APP_DEBUG` - Modo debug (true, false)
-   `CORS_ALLOWED_ORIGINS` - Orígenes permitidos para CORS

## 🌐 Endpoints disponibles

-   **Health Check:** `GET /api/health`
-   **Proyectos:** `GET /api/projects`
-   **Experiencia:** `GET /api/works`
-   **Admin Login:** `POST /api/admin/login`
-   **Upload:** `POST /api/admin/upload`

## 🔧 Configuración para Railway

Railway puede usar esta imagen directamente:

```yaml
# railway.toml
[build]
builder = "dockerfile"
dockerfilePath = "Dockerfile"

# O usar la imagen directamente
[deploy]
image = "jhuerta20/api-portafolio:latest"
```

## 📊 Versiones disponibles

-   `latest` - Última versión estable
-   `v1.0.0` - Versión específica (cuando esté disponible)

## 🛠️ Desarrollo local

Para desarrollo local con la imagen:

```bash
# Usar docker-compose con la imagen
docker-compose up -d

# O construir localmente
docker build -t portfolio-api .
docker run -p 8000:80 portfolio-api
```

## 🔍 Troubleshooting

### Problemas comunes:

1. **Puerto ocupado:**

    ```bash
         # Cambiar puerto
     docker run -p 8001:80 jhuerta20/api-portafolio:latest
    ```

2. **Base de datos no conecta:**

    ```bash
    # Verificar variables de entorno
    docker run -e DB_HOST=your-host jhuerta20/portfolio-api:latest
    ```

3. **Permisos de storage:**
    ```bash
    # Montar volumen para storage
    docker run -v ./storage:/var/www/html/storage jhuerta20/portfolio-api:latest
    ```

## 📝 Notas

-   ✅ **Imagen optimizada** para producción
-   ✅ **Apache configurado** para Laravel
-   ✅ **CORS habilitado** para frontend
-   ✅ **Extensiones PHP** necesarias incluidas
-   ✅ **Composer** pre-instalado
-   ✅ **Scripts de inicialización** automáticos
