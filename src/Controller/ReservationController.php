<?php

namespace App\Controller;

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

                // Table réservation (actuelle), table "heure" (avec tranches horaires ?), table "places"
                // Chaque table "places" serait reliée à une tranche horaire par clé étrangère
                // Hour : hour (id), date (fk)
                // Seats : seats (défaut : 20), hour (fk)
                if($check_hour) {
                    foreach($check_hour as $hour) {
                        $response[] = $hour;
                    }
                }
            }
            else if (isset($_POST['surname']) && isset($_POST['mail']))
            {
                $mail = $_POST['mail'];
                $surname = $_POST['surname'];

                $check_reservation = $db
                ->prepare(
                    "SELECT mail, hour
                    FROM seats
                    WHERE mail = ?
                    AND
                    (SELECT surname FROM Reservation WHERE surname = ?) = ?"
                )
                ->executeQuery([$mail, $surname, $surname])
                ->fetchAllAssociative();

                if($check_reservation) {
                    foreach($check_reservation as $reservation) {
                        $response['mail'] = $reservation['mail'];
                        $response['hour'] = $reservation['hour'];
                    }
                }
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
