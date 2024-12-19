<?php

namespace App\Service;

use App\Entity\ReservationDate;
use App\Entity\Seat;
use App\Repository\ReservationDateRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationDateService
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function createReservationDate(int $timestamp): ReservationDate
    {
        $entityManager = $this->doctrine->getManager();
        $reservationDate = new ReservationDate();
        $reservationDate->setDate($timestamp);

        $entityManager->persist($reservationDate);
        $entityManager->flush();

        $this->initializeSeatsForDate($reservationDate);

        return $reservationDate;
    }

    private function initializeSeatsForDate(ReservationDate $reservationDate): void
    {
        $entityManager = $this->doctrine->getManager();
        $hours = [
            "13:00", "14:00", "15:00", "16:00",
            "17:00", "18:00", "19:00", "20:00",
            "21:00", "22:00"
        ];

        foreach ($hours as $hour) {
            $seat = new Seat();
            $seat->setHour(\DateTimeImmutable::createFromFormat('H:i', $hour));
            $seat->setSeat(20); // Default number of seats
            $seat->setReservationDate($reservationDate);

            $entityManager->persist($seat);
        }

        $entityManager->flush();
    }
}
