<?php

namespace App\DataFixtures;

use App\Entity\ReservationDate;
use App\Entity\Reservation;
use App\Entity\Seat;
use App\Entity\Menu;
use App\Entity\Dish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // ---- Créer une date de réservation ----
        $date = new \DateTime('now');
        $normalisedDate = $date->setTime(0, 0, 0);

        $reservationDate = new ReservationDate();
        $reservationDate->setDate($normalisedDate);
        $manager->persist($reservationDate);

        // ---- Créer XX sièges par heure ----
        $hours = [
            13.00,
            14.00,
            15.00,
            16.00,
            17.00,
            18.00,
            19.00,
            20.00,
            21.00,
            22.00
        ];

        foreach ($hours as $hour) {
            $seatsPerHour = new Seat();
            $seatsPerHour->setHour($hour);
            $seatsPerHour->setSeatsAvailable(20);
            $seatsPerHour->setDate($reservationDate);
            $manager->persist($seatsPerHour);
        }

        $manager->flush();

        // ---- Créer une réservation ----
        $reservation = new Reservation();
        $reservation->setEmail('sokhona.salaha@gmail.com');
        $reservation->setName('Salaha');
        $reservation->setSurname('Sokhona');
        $reservation->setPhoneNumber('123456789');
        $reservation->setSeats(2);
        $reservation->setHour(13.00);
        $reservation->setDate($reservationDate);
        $manager->persist($reservation);

        // ---- Créer des menus avec des plats ----
        $menusData = [
            'Entrées' => [
                ['Salade de chèvre chaud', 'Salade verte, croûtons, chèvre chaud', 7.50],
                ['Soupe du jour', 'Soupe maison selon arrivage', 5.00],
                ['Œufs mimosa', 'Œufs mayonnaise à l’ancienne', 4.50],
                ['Terrine maison', 'Porc, veau et condiments', 6.00],
                ['Salade de lentilles', 'Avec échalotes et vinaigrette', 5.50],
                ['Quiche lorraine', 'Servie tiède avec salade', 6.50],
                ['Velouté de potiron', 'Avec une touche de crème', 5.00],
            ],
            'Plats' => [
                ['Bœuf bourguignon', 'Bœuf mijoté au vin rouge, légumes', 14.00],
                ['Poulet rôti', 'Pommes de terre et légumes', 12.50],
                ['Steak frites', 'Bavette, sauce au poivre', 13.00],
                ['Gratin dauphinois', 'Crème et fromage', 11.00],
                ['Poisson du jour', 'Selon arrivage', 15.00],
                ['Escalope de veau', 'À la crème, champignons', 14.50],
                ['Burger maison', 'Steak, fromage, oignons', 13.50],
                ['Spaghetti bolognaise', 'Sauce tomate et viande hachée', 11.50],
            ],
            'Plats végétariens' => [
                ['Curry de légumes', 'Riz basmati, lait de coco', 11.00],
                ['Lasagnes végétariennes', 'Aubergines, tomates, mozzarella', 12.00],
                ['Tofu grillé', 'Légumes de saison', 10.50],
                ['Burger veggie', 'Steak végétal, crudités', 11.50],
                ['Soupe thaï', 'Légumes, lait de coco', 9.50],
                ['Riz cantonais végétarien', 'Tofu, carottes, petits pois', 10.00],
                ['Salade composée', 'Lentilles, avocat, œuf poché', 10.50],
            ],
            'Menu enfant' => [
                ['Mini burger', 'Steak haché, frites', 8.00],
                ['Nuggets de poulet', 'Avec purée maison', 7.50],
                ['Jambon-purée', 'Classique enfant', 7.00],
                ['Pâtes à la sauce tomate', 'Avec parmesan', 6.50],
                ['Croque-monsieur', 'Avec salade', 7.50],
                ['Poisson pané', 'Avec riz', 6.50],
            ],
            'Desserts' => [
                ['Tarte tatin', 'Servie tiède, glace vanille', 6.00],
                ['Fondant chocolat', 'Cœur coulant, chantilly', 6.50],
                ['Crème brûlée', 'À la vanille', 5.50],
                ['Tiramisu', 'Recette traditionnelle', 5.50],
                ['Mousse au chocolat', 'Avec éclats de noisettes', 5.00],
                ['Clafoutis', 'Aux cerises', 5.50],
                ['Panna cotta', 'Coulis de fruits rouges', 5.00],
            ],
            'Boissons' => [
                ['Coca-Cola', 'Canette 33cl', 2.50],
                ['Eau pétillante', 'Bouteille 50cl', 2.00],
                ['Jus d’orange', '100% pur jus', 2.80],
                ['Thé glacé', 'Pêche ou citron', 2.50],
                ['Bière blonde', 'Pression 25cl', 3.00],
                ['Verre de vin', 'Rouge, blanc ou rosé', 3.50],
                ['Café', 'Expresso ou allongé', 1.80],
                ['Chocolat chaud', 'Lait entier, cacao', 2.50],
            ],
        ];

        foreach ($menusData as $menuName => $dishes) {
            $menu = new Menu();
            $menu->setName($menuName);
            $manager->persist($menu);

            foreach ($dishes as [$name, $description, $price]) {
                $dish = new Dish();
                $dish->setName($name);
                $dish->setDescription($description);
                $dish->setPrice($price);
                $dish->setMenu($menu);
                $manager->persist($dish);
            }
        }

        // ---- Appliquer toutes les modifications ----
        $manager->flush();
    }
}
