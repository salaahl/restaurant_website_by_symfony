# Étape 1 : Node pour le build du frontend
FROM node:20-alpine AS node_build

WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Étape 2 : PHP pour Symfony
FROM php:8.3-fpm-alpine

# Installer les dépendances nécessaires pour Symfony et PostgreSQL
RUN apk add --no-cache \
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

# Installer Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier tous les fichiers du projet Symfony
COPY . .

# Copier le build des assets généré par Webpack Encore
COPY --from=node_build /app/public/build /var/www/html/public/build

# Installer les dépendances Symfony (sans les dépendances de dev et optimisation pour prod)
RUN composer install --optimize-autoloader --no-scripts --ignore-platform-req=ext-intl
RUN composer require doctrine/doctrine-fixtures-bundle --dev

# Créer le dossier var et définir les permissions
RUN mkdir -p /var/www/html/var && \
    chown -R www-data:www-data /var/www/html/var && \
    chmod -R 775 /var/www/html/var

# Copier le script entrypoint.sh et lui donner les droits d'exécution
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Définir l'entrypoint et la commande par défaut
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]
