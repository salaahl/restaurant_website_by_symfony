<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\ReservationDate;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class ReservationService
{
    private $doctrine;
    private $reservationRepository;
    private $reservationDateService;
    private $seatService;

    public function __construct(
        ManagerRegistry $doctrine,
        ReservationRepository $reservationRepository,
        ReservationDateService $reservationDateService,
        SeatService $seatService
    ) {
        $this->doctrine = $doctrine;
        $this->reservationRepository = $reservationRepository;
        $this->reservationDateService = $reservationDateService;
        $this->seatService = $seatService;
    }

    public function createReservation(\DateTime $date, int $seats): array
    {
        // Normaliser la date pour ignorer l'heure (seulement la date est prise en compte)
        $normalizedDate = $date->setTime(0, 0, 0);

        // Recherche ou création de la date de réservation
        $reservationDateRepository = $this->doctrine->getRepository(ReservationDate::class);
        $reservationDate = $reservationDateRepository->findOneBy(['date' => $normalizedDate]);

        if (!$reservationDate) {
            $reservationDate = $this->reservationDateService->createReservationDate($normalizedDate);
        }

        // Vérification de la disponibilité des sièges
        $availableSeats = $this->seatService->checkAvailability($normalizedDate, $seats);

        return $availableSeats;
    }


    public function checkReservationDetails(string $email, string $surname): array
    {
        $reservations = $this->reservationRepository->createQueryBuilder('s')
            ->select('s.surname', 's.name', 'r.date', 's.hour', 's.seats')
            ->leftJoin('s.date', 'r')
            ->where('s.email = :email')
            ->andWhere('s.surname = :surname')
            ->setParameter('email', $email)
            ->setParameter('surname', $surname)
            ->orderBy('r.date', 'ASC')
            ->getQuery()
            ->getResult();

        if (empty($reservations)) {
            throw new \Exception('Aucune reservation trouvée');
        }

        return $reservations;
    }

    public function completeReservation(Request $request): int
    {
        // Récupération et validation des données
        $date = new \DateTime($request->request->get('date'));
        $hour = floatval($request->request->get('hour'));
        $seats = $request->request->get('seats');
        $name = $request->request->get('name');
        $surname = $request->request->get('surname');
        $phoneNumber = $request->request->get('phone_number') ?? null;
        $email = filter_var($request->request->get('email'), FILTER_VALIDATE_EMAIL);

        if (!$email) {
            throw new \Exception('Email invalide');
        }

        if (empty($name) || empty($surname)) {
            throw new \Exception('Le nom et le prénom sont obligatoires');
        }

        if (
            $this->doctrine->getRepository(Reservation::class)->findOneBy([
                'email' => $email,
                'date' => $this->doctrine->getRepository(ReservationDate::class)->findOneBy(['date' => $date]),
            ])
        ) {
            throw new \Exception('Vous avez déjà une réservation pour cette date');
        }

        $today = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $today->setTime(0, 0, 0);

        $reservations = $this->reservationRepository->createQueryBuilder('s')
            ->select('s.id')
            ->innerJoin('s.date', 'r')
            ->where('s.email = :email')
            ->andWhere('r.date >= :date')
            ->setParameter('email', $email)
            ->setParameter('date', $today)
            ->getQuery()
            ->getResult();

        if (count($reservations) > 2) {
            throw new \Exception('Vous avez atteint le nombre maximal de réservations à venir');
        }

        $reservationDate = $this->doctrine->getRepository(ReservationDate::class)->findOneBy(['date' => $date]);

        if (!$reservationDate) {
            throw new \Exception('La date de réservation n\'est pas valide');
        }

        $reservation = new Reservation();
        $reservation->setDate($reservationDate);
        $reservation->setHour($hour);
        $reservation->setSeats($seats);
        $reservation->setName($name);
        $reservation->setSurname($surname);
        $reservation->setPhoneNumber($phoneNumber);
        $reservation->setEmail($email);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($reservation);
        $entityManager->flush();

        $this->seatService->updateSeatAvailability($seats, $date, $hour);

        return $reservation->getId();
    }
}
