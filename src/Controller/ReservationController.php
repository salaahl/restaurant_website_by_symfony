<?php

namespace App\Controller;

use App\Service\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    private $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    #[Route('/reservation', name: 'app_reservation')]
    public function reservation(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            try {
                $action = $request->request->get('action');

                switch ($action) {
                    case 'new_reservation':
                        $response = $this->reservationService->createReservationDate($request);
                        break;

                    case 'check_reservation':
                        $response = $this->reservationService->checkReservationDetails($request);
                        break;

                    case 'complete_reservation':
                        $response = $this->reservationService->completeReservation($request);
                        break;

                    default:
                        throw new \InvalidArgumentException('Action inconnue ou invalide');
                }

                return new JsonResponse($response);
            } catch (\Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], 400);
            }
        }

        // Rendu de la page par défaut (GET request)
        return $this->render('pages/reservation.html.twig');
    }

    #[Route('/confirmation', name: 'app_confirmation')]
    public function confirmation($reservation): Response
    {
        if (!$reservation) {
            return new RedirectResponse('/');
        }

        return $this->render('pages/confirmation.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}
