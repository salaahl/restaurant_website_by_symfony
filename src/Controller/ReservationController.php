<?php

namespace App\Controller;

use App\Entity\Reservation;
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
    ): Response
    {
        $db = $doctrine->getManager()->getConnection();

        if ($request->isMethod('POST'))
        {
            $response = [];

            if (isset($_POST['check-seats']) && isset($_POST['check-date']))
            {
                $seats = $_POST['check-seats'];
                // Timestamp pour avoir une valeur plus fiable
                $date = strtotime($_POST['check-date']);
                // A utiliser pour savoir si la date demandée est antérieure à celle d'aujourd'hui ?
                $today = date('Y/m/d', strtotime("now"));
                
                // Vérifie si la date existe :
                $check_date = $db
                ->prepare(
                    "SELECT reservation_date
                    FROM reservation_date
                    WHERE reservation_date = ?"
                )
                ->executeQuery([$date])
                ->fetchAllAssociative();

                if(!$check_date) {
                    $add_date = $db
                    ->prepare(
                        "INSERT INTO reservation_date
                        VALUES (?)"
                    )
                    ->executeQuery([$date]);
                    
                    $hours = ["13:00:00", "14:00:00", "15:00:00", "16:00:00", 
                    "17:00:00", "18:00:00", "19:00:00", "20:00:00", "21:00:00", "22:00:00"];
                    
                    foreach($hours as $hour) {
                        $add_hour = $db
                        ->prepare(
                            "INSERT INTO seats (hour, seat, ReservationDate)
                            VALUES (?, 20, ?)"
                        )
                        ->executeQuery([$hour, $date]);
                    }
                }
                
                // Selection des heures dispo si le nombre de sièges restant est suffisant :
                $check_hour = $db
                ->prepare(
                    "SELECT hour
                    FROM seats
                    WHERE seat >= ?
                    AND
                    ReservationDate = ?
                    ORDER BY hour ASC"
                )
                ->executeQuery([$seats, $date])
                ->fetchAllAssociative();

                if($check_hour) {
                    foreach($check_hour as $hour) {
                        $response[] = $hour;
                    }
                }
            }
            else if (isset($_POST['check-reservation-surname']) && isset($_POST['check-reservation-mail']))
            {
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

                if($check_reservation) {

                    $surname = [];
                    $name = [];
                    $date = [];
                    $hour = [];

                    foreach($check_reservation as $reservation) {
                        $surname[] = $reservation['surname'];
                        $name[] = $reservation['name'];
                        $date[] = $reservation['ReservationDate'];
                        $hour[] = $reservation['hour'];
                    }

                    $response['surname'] = $surname;
                    $response['name'] = $name;
                    $response['date'] = $date;
                    $response['hour'] = $hour;
                }
            }
            else if (isset($_POST['phone_number']) && isset($_POST['mail']))
            {
                $date = strtotime($_POST['date']);
                $hour = \DateTimeImmutable::createFromFormat('H:i:s', $_POST['hour']);
                $seats_number = $_POST['seats'];
                $name = $_POST['name'];
                $surname = $_POST['surname'];
                $phone_number = $_POST['phone_number'];
                $mail = $_POST['mail'];

                $reservationEntity = new Reservation();
                $reservationDate = $reservationDateRepository->find($date);

                $reservationEntity->setReservationDate($reservationDate);
                $reservationEntity->setHour($hour);
                $reservationEntity->setSeatReserved($seats_number);
                $reservationEntity->setName($name);
                $reservationEntity->setSurname($surname);
                $reservationEntity->setPhoneNumber($phone_number);
                $reservationEntity->setMail($mail);

                $seats = $seatsRepository->findOneBy([
                    'date' => $date,
                    'hour' => $hour
                ]);
                $seats_actualised = $seats->getSeat() - $seats_number;
                $seats->setSeat($seats_actualised);

                $entityManager = $doctrine->getManager();
                $entityManager->persist($reservationEntity, $seats);
                $entityManager->flush();

                return new Response('réservation enregistrée !');
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
