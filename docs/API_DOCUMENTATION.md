# API Documentation - Portfolio Laravel

## Base URL

```
http://127.0.0.1:8000/api
```

## Authentication

La API utiliza Laravel Sanctum para autenticación. Los tokens se envían en el header:

```
Authorization: Bearer {token}
```

## Endpoints

### 1. Autenticación de Administrador

#### Login

```
POST /admin/login
```

**Body:**

```json
{
    "email": "admin@portfolio.com",
    "password": "password123"
}
```

**Response:**

```json
{
    "user": {
        "id": 1,
        "name": "Admin",
        "email": "admin@portfolio.com",
        "role": "admin"
    },
    "token": "1|abc123...",
    "expires_at": "2024-02-14T12:00:00.000000Z"
}
```

#### Register

```
POST /admin/register
```

**Body:**

```json
{
    "name": "Nuevo Admin",
    "email": "nuevo@admin.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Logout

```
POST /admin/logout
```

_Requiere autenticación_

#### Profile

```
GET /admin/profile
```

_Requiere autenticación_

### 2. Proyectos (Públicos)

#### Obtener todos los proyectos

```
GET /portfolio/projects
```

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Portfolio Personal",
            "description": "Portfolio personal desarrollado con Angular y Laravel...",
            "date": "2024",
            "tech": ["Angular", "Laravel", "PHP", "TypeScript"],
            "image": "projects/portfolio.jpg",
            "demo_link": "https://portfolio.example.com",
            "code_link": "https://github.com/username/portfolio",
            "featured": true,
            "status": "active",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

#### Obtener proyectos destacados

```
GET /portfolio/projects/featured
```

#### Obtener proyecto específico

```
GET /portfolio/projects/{id}
```

### 3. Experiencia Laboral (Públicos)

#### Obtener toda la experiencia laboral

```
GET /portfolio/works
```

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "company": "Tech Solutions Inc.",
            "position": "Senior Full Stack Developer",
            "description": "Desarrollo de aplicaciones web complejas...",
            "start_date": "2023-01",
            "end_date": null,
            "location": "Madrid, España",
            "tech": ["Angular", "Laravel", "PHP", "TypeScript"],
            "achievements": [
                "Reduje el tiempo de carga de la aplicación en un 40%",
                "Implementé CI/CD con GitHub Actions"
            ],
            "is_current": true,
            "company_url": "https://techsolutions.com",
            "status": "active",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

#### Obtener trabajo actual

```
GET /portfolio/works/current
```

#### Obtener experiencia específica

```
GET /portfolio/works/{id}
```

### 4. Gestión de Proyectos (Admin - Requiere autenticación)

#### Obtener todos los proyectos (admin)

```
GET /admin/projects
```

#### Crear proyecto

```
POST /admin/projects
```

**Body (multipart/form-data):**

```json
{
    "title": "Nuevo Proyecto",
    "description": "Descripción del proyecto",
    "date": "2024",
    "tech": ["Angular", "Laravel"],
    "image": [file],
    "demo_link": "https://demo.com",
    "code_link": "https://github.com/project",
    "featured": true,
    "status": "active"
}
```

#### Actualizar proyecto

```
PUT /admin/projects/{id}
```

#### Eliminar proyecto

```
DELETE /admin/projects/{id}
```

#### Toggle destacado

```
POST /admin/projects/{id}/toggle-featured
```

### 5. Gestión de Experiencia Laboral (Admin - Requiere autenticación)

#### Obtener toda la experiencia (admin)

```
GET /admin/works
```

#### Crear experiencia laboral

```
POST /admin/works
```

**Body:**

```json
{
    "company": "Nueva Empresa",
    "position": "Desarrollador Senior",
    "description": "Descripción del trabajo",
    "start_date": "2024-01",
    "end_date": null,
    "location": "Madrid, España",
    "tech": ["Angular", "Laravel"],
    "achievements": ["Logro 1", "Logro 2"],
    "is_current": true,
    "company_url": "https://empresa.com",
    "status": "active"
}
```

#### Actualizar experiencia laboral

```
PUT /admin/works/{id}
```

#### Eliminar experiencia laboral

```
DELETE /admin/works/{id}
```

#### Toggle trabajo actual

```
POST /admin/works/{id}/toggle-current
```

### 6. Test Endpoint

#### Verificar que la API funciona

```
GET /test
```

**Response:**

```json
{
    "message": "API del Portafolio funcionando correctamente",
    "status": "success",
    "timestamp": "2024-01-01T12:00:00.000000Z"
}
```

## Códigos de Estado HTTP

-   `200` - OK
-   `201` - Created
-   `400` - Bad Request
-   `401` - Unauthorized
-   `404` - Not Found
-   `422` - Validation Error
-   `500` - Internal Server Error

## Configuración CORS

La API está configurada para aceptar peticiones desde:

-   `http://localhost:4200`
-   `http://localhost:4201`
-   `http://127.0.0.1:4200`
-   `http://127.0.0.1:4201`

## Archivos

Los archivos subidos se almacenan en `storage/app/public/projects/` y son accesibles a través de `/storage/projects/{filename}`.

## Credenciales por defecto

**Admin:**

-   Email: `admin@portfolio.com`
-   Password: `password123`
