<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\DishRepository;
use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping\OrderBy;

class GlobalController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(
        MenuRepository $menuRepository,
        DishRepository $dishRepository
    ): Response {

        $menus = $menuRepository->findAll();
        $dishs = $dishRepository->findAll();

        return $this->render('global/home.html.twig', [
            'menus' => $menus,
            'dishs' => $dishs
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('global/about.html.twig');
    }
}
