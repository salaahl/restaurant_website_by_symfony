<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Dish;
use App\Entity\Menu;

use App\Repository\MenuRepository;
use App\Repository\DishRepository;
use App\Repository\SeatsRepository;

class CRUDController extends AbstractController
{
    #[Route('/add_something', name: 'app_add_something')]
    public function addSomething(
        SeatsRepository $seatsRepository,
        MenuRepository $menuRepository,
        DishRepository $dishRepository,
        ManagerRegistry $doctrine,
        Request $request
    ): Response {

        $check_type = $menuRepository->findOneBy([
            'type' => 'halal'
        ]);

        if ($check_type == NULL) {
            $menu = new Menu();
            $menu->setType('Halal');

            $entityManager = $doctrine->getManager();
            $entityManager->persist($menu);
            $entityManager->flush();
        }

        $dishHalal = $dishRepository->findBy(
            ['type' => 'halal'],
            ['type' => 'ASC']
        );

        $response = [];
        $seats = '20';
        $date = '1677625hi200';

        $check_hour = $seatsRepository->findBy(
            [
                'seat' => $seats,
                'date' => $date
            ],
            ['hour' => 'ASC']
        );

        foreach ($check_hour as $hour) {
            $response[] = $hour->getSeat();
        }

        return new Response(var_dump($response));
    }
}
