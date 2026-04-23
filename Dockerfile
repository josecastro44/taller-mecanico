FROM php:8.2-cli

# 1. Instalar herramientas, extensiones de PHP y Node (para tus estilos)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql zip

# 2. Instalar el mecánico (Composer)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Movernos a la carpeta del proyecto
WORKDIR /app
COPY . .

# 4. Descargar el motor de Laravel y compilar el diseño
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# 5. Encender el servidor en el puerto que Render nos dé
CMD php -S 0.0.0.0:$PORT -t public