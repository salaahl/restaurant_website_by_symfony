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

/**
 * @Route("/dish/")
 */
class DishController extends AbstractController
{
    protected MenuRepository $menuRepository;
    protected DishRepository $dishRepository;
    
    #[Route('create', name: 'dish.create')]
    public function create(
        Request $request,
    ): Response {

        $dish = new Dish();
        $menus = $this->menuRepository->findAll();
        
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dishRepository->save($dish, true);
            return new Response(true);
        }

        return $this->render('dish/create.html.twig', [
            'form' => $form,
            'menus' => $menus,
        ]);
    }
  
    #[Route('update', name: 'dish.update')]
    public function update(
        Request $request,
    ): Response {
        //
    }

    #[Route('edit', name: 'dish.edit')]
    public function edit(
        Request $request,
    ): Response {
        //
    }

    #[Route('delete', name: 'dish.delete')]
    public function delete(
        Request $request,
    ): Response {
        //
    }
}
