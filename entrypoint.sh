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

echo "Migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
php bin/console doctrine:fixtures:load --env=prod --no-interaction

exec "$@"
