<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Dish;
use App\Entity\Menu;

use App\Form\DishType;
use App\Form\MenuType;

use App\Repository\DishRepository;
use App\Repository\MenuRepository;

class MenuController extends AbstractController
{
    #[Route('/add_menu', name: 'app_add_menu')]
    public function addMenu(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {

        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $doctrine->getManager();
            $entityManager->persist($menu);
            $entityManager->flush();

            return new Response(true);
        }
        return $this->render('menu/add_menu.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/add_dish', name: 'app_add_dish')]
    public function addDish(
        MenuRepository $menuRepository,
        Request $request,
        ManagerRegistry $doctrine
    ): Response {

        $dish = new Dish();
        $menus = $menuRepository->findAll();
        
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() /*&& $form->isValid()*/) {

            $entityManager = $doctrine->getManager();
            $entityManager->persist($dish);
            $entityManager->flush();

            return new Response(true);
        }

        return $this->render('menu/add_dish.html.twig', [
            'form' => $form,
            'menus' => $menus,
        ]);
    }
}
