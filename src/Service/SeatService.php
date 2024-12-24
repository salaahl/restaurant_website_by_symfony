<?php

namespace App\Service;

use App\Repository\SeatRepository;
use App\Repository\ReservationDateRepository;
use Doctrine\Persistence\ManagerRegistry;

class SeatService
{
    private $doctrine;
    private $seatRepository;
    private $reservationDateRepository;

    public function __construct(ManagerRegistry $doctrine, SeatRepository $seatRepository, ReservationDateRepository $reservationDateRepository)
    {
        $this->doctrine = $doctrine;
        $this->seatRepository = $seatRepository;
        $this->reservationDateRepository = $reservationDateRepository;
    }

    public function checkAvailability(\DateTimeInterface $date, int $seats): array
    {
        $availableSeats = $this->seatRepository->createQueryBuilder('s')
            ->select('s.hour, s.seats_available')
            ->where('s.date = :date')
            ->andWhere('s.seats_available >= :seats')
            ->setParameter('date', $this->reservationDateRepository->findOneBy(['date' => $date]))
            ->setParameter('seats', $seats)
            ->orderBy('s.hour', 'ASC')
            ->getQuery()
            ->getResult();

        if (empty($availableSeats)) {
            throw new \Exception('Plus assez de places pour cette date');
        }

        return $availableSeats;
    }

    public function updateSeatAvailability(int $seats, \DateTime $date, float $hour): void
    {
        $seat = $this->seatRepository->findOneBy([
            'date' => $this->reservationDateRepository->findOneBy(['date' => $date]),
            'hour' => $hour
        ]);

        if (!$seat || $seat->getSeatsAvailable() < $seats) {
            throw new \Exception('Pas assez de places disponibles');
        }

        $seat->setSeatsAvailable($seat->getSeatsAvailable() - $seats);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($seat);
        $entityManager->flush();
    }
}
