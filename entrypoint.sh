#!/bin/sh
set -e

until pg_isready -h "${DB_HOST}" -p "${DB_PORT}" -U "${DB_USERNAME}"; do
  echo "En attente de la base de données..."
  sleep 2
done

cd /var/www/html

echo "Préparation du cache Symfony..."
php bin/console cache:clear --env=prod --no-debug
php bin/console cache:warmup --env=prod --no-debug

echo "Création de la base de données et lancement des migrations..."
php bin/console doctrine:database:drop --force || true
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

exec "$@"
