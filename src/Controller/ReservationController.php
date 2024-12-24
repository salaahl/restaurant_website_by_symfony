<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Service\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\ReservationRepository;

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
                        $date = new \DateTime($request->request->get('date'));
                        $seats = $request->request->get('seats');
                        
                        $response = $this->reservationService->createReservation($date, $seats);
                        break;

                    case 'check_reservation':
                        $email = $request->request->get('email');
                        $surname = $request->request->get('surname');
                        
                        $response = $this->reservationService->checkReservationDetails($email, $surname);
                        break;

                    case 'complete_reservation':
                        $reservation_id = $this->reservationService->completeReservation($request);

                        return $this->redirectToRoute('app_confirmation', [
                            'reservation_id' => $reservation_id
                        ]);
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

    #[Route('/confirmation/{reservation_id}', name: 'app_confirmation')]
    public function confirmation(ReservationRepository $reservationRepository, ?int $reservation_id=null): Response
    {
        if (!$reservation_id) {
            return $this->redirectToRoute('app_reservation');
        }
        
        $reservation = $reservationRepository->find($reservation_id);

        return $this->render('pages/confirmation.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}
