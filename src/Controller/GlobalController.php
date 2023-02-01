<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\DishRepository;
use Doctrine\ORM\Mapping\OrderBy;

class GlobalController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(
        DishRepository $dishRepository
    ): Response {
        $dishHalal = $dishRepository->findBy(
            ['type' => 'halal'],
            ['type' => 'ASC']
        );

        return $this->render('global/home.html.twig', [
            'controller_name' => 'GlobalController',
            'dishHalal' => $dishHalal,
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('global/about.html.twig', [
            'controller_name' => 'GlobalController',
        ]);
    }
}
