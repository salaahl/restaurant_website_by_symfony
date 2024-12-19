<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Repository\ReservationDateRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class ReservationService
{
    private $doctrine;
    private $reservationDateService;
    private $seatService;

    public function __construct(
        ManagerRegistry $doctrine,
        ReservationDateService $reservationDateService,
        SeatService $seatService
    ) {
        $this->doctrine = $doctrine;
        $this->reservationDateService = $reservationDateService;
        $this->seatService = $seatService;
    }

    public function createReservationDate(Request $request): array
    {
        $date = strtotime($request->request->get('date'));
        $seats = $request->request->get('seats');

        $reservationDate = $this->reservationDateService->createReservationDate($date);
        $availableSeats = $this->seatService->checkAvailability($seats, $date);

        return $availableSeats;
    }

    public function checkReservationDetails(Request $request): array
    {
        $mail = $request->request->get('mail');
        $surname = $request->request->get('surname');

        $repository = $this->doctrine->getRepository(Reservation::class);
        $reservations = $repository->findBy(['mail' => $mail, 'surname' => $surname]);

        $response = [];
        foreach ($reservations as $reservation) {
            $response[] = [
                'surname' => $reservation->getSurname(),
                'name' => $reservation->getName(),
                'date' => date('d/m/Y', $reservation->getReservationDate()->getDate()),
                'hour' => $reservation->getHour()->format('H:i'),
                'seat_reserved' => $reservation->getSeatReserved()
            ];
        }

        return $response;
    }

    public function completeReservation(Request $request): array
    {
        $date = strtotime($request->request->get('date'));
        $hour = \DateTimeImmutable::createFromFormat('H:i:s', $request->request->get('hour'));
        $seatsReserved = $request->request->get('seats');
        $name = $request->request->get('name');
        $surname = $request->request->get('surname');
        $phoneNumber = $request->request->get('phone_number');
        $email = filter_var($request->request->get('mail'), FILTER_VALIDATE_EMAIL);

        if (!$email) {
            throw new \Exception('Email invalide');
        }

        $this->seatService->updateSeatAvailability($seatsReserved, $date, $hour);

        $reservation = new Reservation();
        $reservationDate = $this->doctrine->getRepository(ReservationDate::class)->findOneBy(['date' => $date]);

        $reservation->setReservationDate($reservationDate);
        $reservation->setHour($hour);
        $reservation->setSeatReserved($seatsReserved);
        $reservation->setName($name);
        $reservation->setSurname($surname);
        $reservation->setPhoneNumber($phoneNumber);
        $reservation->setMail($email);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->redirectToRoute('app_confirmation', [
            'reservation' => $reservation
        ]);
    }
}
