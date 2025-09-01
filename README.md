[![Symfony & Docker CI](https://github.com/salaahl/restaurant_website_by_symfony/actions/workflows/docker-image.yml/badge.svg)](https://github.com/salaahl/restaurant_website_by_symfony/actions/workflows/docker-image.yml)

# Restaurant Le Vingtième

Site de réservation pour le restaurant Le Vingtième, construit avec Symfony.

## Fonctionnalités

- Réservation en ligne
- Consultation du menu
- Back-office pour l’administrateur

## Architecture technique

- **Backend** : PHP 8.4, Symfony 6.2
- **Frontend** : Twig, CSS, JavaScript
- **Base de données** : PostgreSQL (SQLite pour les tests)
- **Tests** : PHPUnit, Symfony Panther
- **Containerisation** : Docker

## Installation & Lancement

1. Cloner le dépôt
2. Composer install
3. Configurer `.env` / `.env.test` (ajouter `DATABASE_URL, APP_NAME et SEATS_PER_HOUR`)
4. `php bin/console doctrine:database:create && doctrine:migrations:migrate`
5. `symfony serve` ou `docker-compose up --build`
6. Accéder à l’application sur `http://localhost:8000` (ou autre port configuré)

## Lancement des tests

- `php bin/phpunit`