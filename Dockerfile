# Dockerfile simplificado para Laravel en Railway
FROM php:8.2-fpm

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Variables de entorno
ENV TZ=UTC

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copiar archivos de configuración de Composer primero
COPY composer.json composer.lock ./

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copiar el resto de la aplicación
COPY . .

# Establecer permisos correctos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Crear directorios de storage
RUN mkdir -p storage/logs \
    && mkdir -p storage/framework/cache \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/app/public/assets/uploads/projects \
    && mkdir -p storage/app/public/assets/uploads/works \
    && mkdir -p storage/app/public/assets/uploads/temp

# Configurar Nginx
RUN echo 'server {\n\
    listen 80;\n\
    server_name localhost;\n\
    root /var/www/html/public;\n\
    index index.php index.html;\n\
    \n\
    location / {\n\
        try_files $uri $uri/ /index.php?$query_string;\n\
    }\n\
    \n\
    location ~ \.php$ {\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_index index.php;\n\
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;\n\
        include fastcgi_params;\n\
    }\n\
    \n\
    # Headers para CORS\n\
    add_header Access-Control-Allow-Origin "*" always;\n\
    add_header Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS" always;\n\
    add_header Access-Control-Allow-Headers "Content-Type, Authorization, X-Requested-With" always;\n\
    \n\
    # Configuración para archivos estáticos\n\
    location ~* \.(jpg|jpeg|png|gif|svg|webp|css|js|ico)$ {\n\
        expires 1M;\n\
        add_header Cache-Control "public, max-age=2592000";\n\
    }\n\
}' > /etc/nginx/sites-available/default

# Script de inicialización
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
# Generar key si no existe\n\
if [ ! -f .env ]; then\n\
    cp .env.example .env\n\
fi\n\
\n\
# Verificar si APP_KEY está vacía\n\
if ! grep -q "APP_KEY=base64:" .env; then\n\
    php artisan key:generate --no-interaction\n\
fi\n\
\n\
# Ejecutar migraciones\n\
php artisan migrate --force --no-interaction\n\
\n\
# Limpiar y optimizar\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
# Crear storage link\n\
php artisan storage:link\n\
\n\
# Generar placeholders si no existen\n\
php artisan images:generate-placeholders\n\
\n\
# Seedear datos iniciales\n\
php artisan db:seed --force --no-interaction\n\
\n\
# Establecer permisos finales\n\
chown -R www-data:www-data /var/www/html/storage\n\
chmod -R 775 /var/www/html/storage\n\
\n\
# Iniciar servicios\n\
service nginx start\n\
php-fpm' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Exponer puerto
EXPOSE 80

# Comando de inicio
CMD ["/usr/local/bin/start.sh"]
