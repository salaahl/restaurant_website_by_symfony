<?php

namespace App\Service;

use App\Entity\ReservationDate;
use App\Entity\Seat;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

class ReservationDateService
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function createReservationDate(DateTime $date): ReservationDate
    {
        $checkDate = $this->doctrine->getRepository(ReservationDate::class)->findOneBy(['date' => $date]);

        if ($checkDate) {
            return $checkDate;
        }

        $reservationDate = new ReservationDate();
        $reservationDate->setDate($date);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($reservationDate);
        $entityManager->flush();

        $this->initializeSeatsForDate($reservationDate);

        return $reservationDate;
    }

    private function initializeSeatsForDate(ReservationDate $reservationDate): void
    {
        $entityManager = $this->doctrine->getManager();
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
            $seat = new Seat();
            $seat->setHour($hour);
            $seat->setSeatsAvailable(20);
            $seat->setDate($reservationDate);

            $entityManager->persist($seat);
        }

        $entityManager->flush();
    }
}
