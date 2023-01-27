<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Seats;

use App\Repository\SeatsRepository;

use Symfony\Component\Validator\Constraints\DateTime;

use Doctrine\Persistence\ManagerRegistry;

class CRUDController extends AbstractController
{
    #[Route('/c/r/u/d', name: 'app_c_r_u_d')]
    public function index(
        ManagerRegistry $doctrine
    ): Response
    {
        $db = $doctrine->getManager()->getConnection();
        $date = strtotime('25-01-2023');

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
                    "INSERT INTO reservation_date (reservation_date)
                    VALUES (?)"
                )
                ->executeQuery([$date]);
            }

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

        return new Response(var_dump($hours));
    }
}