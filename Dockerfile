# -------------------------------
# Étape 1 : Build du frontend avec Node
# -------------------------------
FROM node:20-alpine AS node_build

WORKDIR /app

# Copier package.json / package-lock.json et installer les dépendances
COPY package*.json ./
RUN npm ci

# Copier le reste des sources et builder les assets
COPY . .
RUN npm run build

# -------------------------------
# Étape 2 : PHP-FPM pour Symfony
# -------------------------------
FROM php:8.3-fpm-alpine

# Installer les dépendances nécessaires pour Symfony et PostgreSQL
RUN apk add --no-cache \
    libzip libpng libjpeg-turbo freetype zip unzip git postgresql-libs postgresql-client \
    && apk add --no-cache --virtual .build-deps \
        libpng-dev libjpeg-turbo-dev freetype-dev postgresql-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql pgsql exif \
    && apk del .build-deps

# Installer Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier le code Symfony
COPY . .

# Copier le build frontend depuis l'étape Node
COPY --from=node_build /app/public/build /var/www/html/public/build

# Installer les dépendances Symfony (prod)
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-req=ext-intl

# Permissions sur var et public/build
RUN mkdir -p var/cache var/log var/sessions \
    && chown -R www-data:www-data var public/build \
    && chmod -R 775 var public/build

# -------------------------------
# Étape 3 : Nginx pour servir Symfony + cache assets
# -------------------------------
FROM nginx:alpine AS production

# Copier la configuration Nginx
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copier PHP-FPM et Symfony
COPY --from=php:8.3-fpm-alpine /var/www/html /var/www/html

# Exposer le port
EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
