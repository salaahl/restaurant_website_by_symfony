# Étape 1 : Node pour le build frontend
FROM node:20-alpine AS node_build
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Étape 2 : PHP + Nginx + Supervisor
FROM php:8.3-fpm-alpine

# Installer dépendances
RUN apk add --no-cache \
    nginx \
    supervisor \
    libzip \
    libpng \
    libjpeg-turbo \
    freetype \
    zip \
    unzip \
    git \
    postgresql-libs \
    postgresql-client \
    && apk add --no-cache --virtual .build-deps \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        postgresql-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql pgsql exif \
    && apk del .build-deps

# Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 1. D'ABORD copier les fichiers de configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

# 2. ENSUITE copier le projet et le build
COPY . .
COPY --from=node_build /app/public/build /var/www/html/public/build

# Installer Symfony
RUN composer install --optimize-autoloader --no-dev --no-scripts --ignore-platform-req=ext-intl

# Permissions
RUN mkdir -p var && chown -R www-data:www-data var && chmod -R 775 var

# Exposer le port
EXPOSE 80

# Entrypoint
CMD ["supervisord", "-n", "-c", "/etc/supervisord.conf"]