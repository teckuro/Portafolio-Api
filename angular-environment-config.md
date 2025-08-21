# Configuración de Angular para Producción

## 1. Crear archivo environment.prod.ts

```typescript
// src/environments/environment.prod.ts
export const environment = {
    production: true,
    apiUrl: "https://tu-api-railway.railway.app/api", // URL de tu API en Railway
    // apiUrl: 'https://tu-api-render.onrender.com/api', // URL de tu API en Render
};
```

## 2. Crear archivo environment.ts (desarrollo)

```typescript
// src/environments/environment.ts
export const environment = {
    production: false,
    apiUrl: "http://127.0.0.1:8000/api", // URL local
};
```

## 3. Configurar angular.json

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

## 4. Usar en servicios

```typescript
// En tus servicios Angular
import { environment } from "../environments/environment";

@Injectable()
export class ApiService {
    private apiUrl = environment.apiUrl;

    getProjects() {
        return this.http.get(`${this.apiUrl}/portfolio/projects`);
    }
}
```

## 5. Build para producción

```bash
ng build --configuration=production
```
