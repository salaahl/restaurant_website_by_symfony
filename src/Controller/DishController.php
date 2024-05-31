<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Dish;
use App\Form\DishType;
use App\Repository\DishRepository;
use App\Repository\MenuRepository;

class DishController extends AbstractController
{
    #[Route('/dish/create', name: 'dish.create')]
    public function create(
        MenuRepository $menuRepository,
        Request $request,
        DishRepository $dishRepository
    ): Response {

        $dish = new Dish();
        $menus = $menuRepository->findAll();
        
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Voir si je ne peux pas plutôt passer par un construct pour éviter d'avoir à appeler à chaque fois le DishRepository dans la méthode
            $dishRepository->save($dish, true);
            return new Response(true);
        }

        return $this->render('dish/create.html.twig', [
            'form' => $form,
            'menus' => $menus,
        ]);
    }
  
    #[Route('/dish/update', name: 'dish.update')]
    public function update(
        Request $request,
    ): Response {
        //
    }

    #[Route('/dish/edit', name: 'dish.edit')]
    public function edit(
        Request $request,
    ): Response {
        //
    }

    #[Route('/dish/delete', name: 'dish.delete')]
    public function delete(
        Request $request,
    ): Response {
        //
    }
}
