<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;

class MenuController extends AbstractController
{
    #[Route('/menu/create', name: 'menu.create')]
    public function create(
        Request $request,
        MenuRepository $menuRepository
    ): Response {

        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menuRepository->save($menu, true);
            return new Response(true);
        }
        
        return $this->render('menu/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/menu/update', name: 'menu.update')]
    public function update(
        Request $request,
        MenuRepository $menuRepository
    ): Response {
        //
    }

    #[Route('/menu/edit', name: 'menu.edit')]
    public function edit(
        Request $request,
        MenuRepository $menuRepository
    ): Response {
        //
    }

    #[Route('/menu/delete', name: 'menu.delete')]
    public function delete(
        Request $request,
        MenuRepository $menuRepository
    ): Response {
        //
    }
}
