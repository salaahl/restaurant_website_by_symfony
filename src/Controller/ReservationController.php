<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\ReservationDate;
use App\Entity\Seats;

use App\Repository\SeatsRepository;
use App\Repository\ReservationDateRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function reservation(
        SeatsRepository $seatsRepository,
        ReservationDateRepository $reservationDateRepository,
        Request $request,
        ManagerRegistry $doctrine
    ): Response {

        if ($request->isMethod('POST')) {

            $_POST = array_map("trim", $_POST);

            $db = $doctrine->getManager()->getConnection();
            $response = [];

            if (isset($_POST['check-seats']) && isset($_POST['check-date'])) {
                $seats = $_POST['check-seats'];
                $date = strtotime($_POST['check-date']);

                $check_date = $reservationDateRepository->find($date);

                if (!$check_date) {

                    $add_date = new ReservationDate();
                    $add_date->setReservationDate($date);

                    $hours = [
                        "13:00:00", "14:00:00", "15:00:00", "16:00:00",
                        "17:00:00", "18:00:00", "19:00:00", "20:00:00", "21:00:00", "22:00:00"
                    ];

                    foreach ($hours as $hour) {
                        $add_hour = new Seats();

                        $add_hour->setHour(\DateTimeImmutable::createFromFormat('H:i:s', $hour));
                        $add_hour->setSeat($seats);
                        $add_hour->setDate($reservationDateRepository->getReservationDate($date));

                        $entityManager = $doctrine->getManager();
                        $entityManager->persist($add_hour);
                        $entityManager->flush();
                    }
                }

                $check_hour =
                    $db->prepare(
                        "SELECT hour
                        FROM seats
                        WHERE seat >= ?
                        AND
                        ReservationDate = ?
                        ORDER BY hour ASC"
                    )
                    ->executeQuery([$seats, $date])
                    ->fetchAllAssociative();

                foreach ($check_hour as $hour) {
                    $response[] = $hour;
                }
            } else if (isset($_POST['check-reservation-surname']) && isset($_POST['check-reservation-mail'])) {
                $mail = $_POST['check-reservation-mail'];
                $surname = $_POST['check-reservation-surname'];

                $check_reservation = $db
                    ->prepare(
                        "SELECT surname, name, ReservationDate, hour
                    FROM reservation
                    WHERE mail = ?
                    AND
                    surname = ?"
                    )
                    ->executeQuery([$mail, $surname])
                    ->fetchAllAssociative();

                if ($check_reservation) {
                    $surname = null;
                    $name = null;
                    $date = [];
                    $hour = [];

                    foreach ($check_reservation as $reservation) {
                        $surname = $reservation['surname'];
                        $name = $reservation['name'];
                        $date[] = date('d-m-Y', $reservation['ReservationDate']);
                        $hour[] = $reservation['hour'];
                    }

                    $response['surname'] = $surname;
                    $response['name'] = $name;
                    $response['date'] = $date;
                    $response['hour'] = $hour;
                }
            } else if (isset($_POST['new_phone_number']) && isset($_POST['new_mail'])) {
                $date = strtotime($_POST['new_date']);
                $hour = \DateTimeImmutable::createFromFormat('H:i:s', $_POST['new_hour']);
                $seats_reserved = $_POST['new_seats'];
                $name = $_POST['new_name'];
                $surname = $_POST['new_surname'];
                $phone_number = $_POST['new_phone_number'];
                $mail = function () {
                    $sanitize = filter_var($_POST['new_mail'], FILTER_SANITIZE_EMAIL);
                    return filter_var($sanitize, FILTER_VALIDATE_EMAIL);
                };

                $newReservation = new Reservation();
                $reservationDate = $reservationDateRepository->find($date);

                // OU mettre directement le contenu de la variable ici
                $newReservation->setReservationDate($reservationDate->getReservationDate());
                $newReservation->setHour($hour);
                $newReservation->setSeatReserved($seats_reserved);
                $newReservation->setName($name);
                $newReservation->setSurname($surname);
                $newReservation->setPhoneNumber($phone_number);
                $newReservation->setMail($mail);

                $seats = $seatsRepository->findOneBy([
                    'date' => $date,
                    'hour' => $hour
                ]);
                $seats_remaining = $seats->getSeat() - $seats_reserved;
                $seats->setSeat($seats_remaining);

                $entityManager = $doctrine->getManager();
                $entityManager->persist($newReservation, $seats);
                $entityManager->flush();

                return $this->render('reservation/reservation_confirmed.html.twig', [
                    'date' => $_POST['date'],
                    'hour' => $_POST['hour'],
                ]);
            }

            return new JsonResponse($response);
        }

        return $this->render('reservation/reservation.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    #[Route('/reservation_confirmed', name: 'app_reservation_confirmed')]
    public function reservationConfirmed(): Response
    {
        return $this->render('reservation/reservation_confirmed.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
}
