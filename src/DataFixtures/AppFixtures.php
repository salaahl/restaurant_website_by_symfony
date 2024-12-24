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

        // ---- Créer un menu avec un plat ----
        $menu = new Menu();
        $menu->setName('Menu 1');
        $manager->persist($menu);

        $dish = new Dish();
        $dish->setName('Dish 1');
        $dish->setDescription('Description 1');
        $dish->setPrice(10);
        $dish->setMenu($menu);
        $manager->persist($dish);

        // ---- Appliquer toutes les modifications ----
        $manager->flush();
    }
}
