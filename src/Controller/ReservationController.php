<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\ReservationDate;
use App\Entity\Seat;

use App\Repository\SeatRepository;
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
        SeatRepository $seatRepository,
        ReservationDateRepository $reservationDateRepository,
        Request $request,
        ManagerRegistry $doctrine
    ): Response {

        if ($request->isMethod('POST')) {

            $headers = getallheaders();
            $_POST;

            if (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] === 'XMLHttpRequest') {
                $json = file_get_contents('php://input');
                $_POST = array_map("trim", json_decode($json, true));
            } else {
                $_POST = array_map("trim", $_POST);
            }

            $db = $doctrine->getManager()->getConnection();
            $response = [];

            if (isset($_POST['new_reservation'])) {
                $seats = $_POST['seats'];
                $date = strtotime($_POST['date']);

                $check_date = $reservationDateRepository->find($date);

                if (!$check_date) {
                    $add_date = new ReservationDate();
                    $add_date->setReservationDate($date);

                    $entityManager = $doctrine->getManager();
                    $entityManager->persist($add_date);
                    $entityManager->flush();

                    $hours = [
                        "13:00:00",
                        "14:00:00",
                        "15:00:00",
                        "16:00:00",
                        "17:00:00",
                        "18:00:00",
                        "19:00:00",
                        "20:00:00",
                        "21:00:00",
                        "22:00:00"
                    ];

                    foreach ($hours as $hour) {
                        $add_hour = new Seat();

                        $add_hour->setHour(\DateTimeImmutable::createFromFormat('H:i:s', $hour));
                        $add_hour->setSeat($seats);
                        $add_hour->setDate($reservationDateRepository->find($date));

                        $entityManager = $doctrine->getManager();
                        $entityManager->persist($add_hour);
                        $entityManager->flush();
                    }
                }

                // Vérifie s'il reste des places pour l'horaire choisi
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
            } else if (isset($_POST['check_reservation'])) {
                $mail = $_POST['mail'];
                $surname = $_POST['surname'];

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
            } else if (isset($_POST['complete_reservation'])) {
                $date = strtotime($_POST['date']);
                $hour = \DateTimeImmutable::createFromFormat('H:i:s', $_POST['hour']);
                $seats_reserved = $_POST['seats'];
                $name = $_POST['name'];
                $surname = $_POST['surname'];
                $phone_number = $_POST['phone_number'];
                $sanitize_mail = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL);
                $mail = filter_var($sanitize_mail, FILTER_VALIDATE_EMAIL);

                $newReservation = new Reservation();
                $reservationDate = $reservationDateRepository->find($date);

                $newReservation->setReservationDate($reservationDate);
                $newReservation->setHour($hour);
                $newReservation->setSeatReserved($seats_reserved);
                $newReservation->setName($name);
                $newReservation->setSurname($surname);
                $newReservation->setPhoneNumber($phone_number);
                $newReservation->setMail($mail);

                $seats = $seatRepository->findOneBy([
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

        return $this->render('reservation/manage.html.twig');
    }
}
