<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;

class MenuController extends AbstractController
{
    protected MenuRepository $menuRepository;
    
    #[Route('/menu/create', name: 'menu.create')]
    public function create(
        Request $request,
    ): Response {

        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->menuRepository->save($menu, true);
            return new Response(true);
        }
        
        return $this->render('menu/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/menu/update', name: 'menu.update')]
    public function update(
        Request $request,
    ): Response {
        //
    }

    #[Route('/menu/edit', name: 'menu.edit')]
    public function edit(
        Request $request,
    ): Response {
        //
    }

    #[Route('/menu/delete', name: 'menu.delete')]
    public function delete(
        Request $request,
    ): Response {
        //
    }
}
