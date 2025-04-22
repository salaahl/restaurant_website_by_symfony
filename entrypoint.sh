#!/bin/sh
set -e

# Attente de la base de données
until pg_isready -h "${DB_HOST}" -p "${DB_PORT}" -U "${DB_USERNAME}"; do
  echo "En attente de la base de données..."
  sleep 2
done

# Naviguer vers le bon dossier
cd /var/www/html

# Préparation Symfony (cache et migrations)
echo "Préparation des caches Symfony..."
php bin/console cache:clear --env=prod --no-debug
php bin/console cache:warmup --env=prod --no-debug

php bin/console doctrine:migrations:migrate --no-interaction

# Démarrage de PHP-FPM
echo "Démarrage de PHP-FPM..."
exec php-fpm
