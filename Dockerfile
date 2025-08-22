# Dockerfile optimizado para Laravel en Railway y Docker Hub
FROM php:8.2-apache

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Variables de entorno
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
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
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configurar Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite headers

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

# Configurar Apache para Laravel
RUN echo '<VirtualHost *:80>\n\
    ServerName localhost\n\
    DocumentRoot /var/www/html/public\n\
    \n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    \n\
    # Headers para CORS\n\
    Header always set Access-Control-Allow-Origin "*"\n\
    Header always set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"\n\
    Header always set Access-Control-Allow-Headers "Content-Type, Authorization, X-Requested-With"\n\
    \n\
    # Configuración para archivos estáticos\n\
    <LocationMatch "\\.(jpg|jpeg|png|gif|svg|webp|css|js|ico)$">\n\
        ExpiresActive On\n\
        ExpiresDefault "access plus 1 month"\n\
        Header set Cache-Control "public, max-age=2592000"\n\
    </LocationMatch>\n\
    \n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

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
# Iniciar Apache\n\
exec apache2-foreground' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Exponer puerto
EXPOSE 80

# Comando de inicio
CMD ["/usr/local/bin/start.sh"]
