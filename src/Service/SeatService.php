<?php

namespace App\Service;

use App\Repository\SeatRepository;
use App\Repository\ReservationDateRepository;

class SeatService
{
    private $seatRepository;
    private $reservationDateRepository;

    public function __construct(SeatRepository $seatRepository, ReservationDateRepository $reservationDateRepository)
    {
        $this->seatRepository = $seatRepository;
        $this->reservationDateRepository = $reservationDateRepository;
    }

    public function checkAvailability(int $seats, int $dateTimestamp): array
    {
        $reservationDate = $this->reservationDateRepository->findOneBy(['date' => $dateTimestamp]);
        if (!$reservationDate) {
            throw new \Exception('Date non trouvée');
        }

        $availableSeats = $this->seatRepository->findAvailableSeats($reservationDate, $seats);

        return $availableSeats;
    }

    public function updateSeatAvailability(int $seatsReserved, int $dateTimestamp, \DateTimeImmutable $hour): void
    {
        $reservationDate = $this->reservationDateRepository->findOneBy(['date' => $dateTimestamp]);
        $seat = $this->seatRepository->findOneBy([
            'reservationDate' => $reservationDate,
            'hour' => $hour
        ]);

        if (!$seat || $seat->getSeat() < $seatsReserved) {
            throw new \Exception('Pas assez de places disponibles');
        }

        $seat->setSeat($seat->getSeat() - $seatsReserved);

        $entityManager = $this->seatRepository->getEntityManager();
        $entityManager->flush();
    }
}
