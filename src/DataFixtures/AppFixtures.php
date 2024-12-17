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
        $reservationDate = new ReservationDate();
        $reservationDate->setDate(strtotime('2023-06-01'));
        $manager->persist($reservationDate);

        // ---- Créer 20 sièges par heure ----
        $hours = [
            "13:00",
            "14:00",
            "15:00",
            "16:00",
            "17:00",
            "18:00",
            "19:00",
            "20:00",
            "21:00",
            "22:00"
        ];

        foreach ($hours as $hour) {
            $seatsPerHour = new Seat();
            $seatsPerHour->setHour(\DateTimeImmutable::createFromFormat('H:i', $hour));
            $seatsPerHour->setSeat(20);
            $seatsPerHour->setReservationDate($reservationDate);
            $manager->persist($seatsPerHour);
        }

        $manager->flush();

        // ---- Créer une réservation ----
        $reservation = new Reservation();
        $reservation->setMail('sokhona.salaha@gmail.com');
        $reservation->setName('Salaha');
        $reservation->setSurname('Sokhona');
        $reservation->setPhoneNumber('123456789');
        $reservation->setSeatReserved(2);
        $reservation->setHour(new \DateTime('2023-06-01 10:00:00'));
        $reservation->setReservationDate($reservationDate);
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
