<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Repository\MenuRepository;
use App\Repository\DishRepository;
use App\Controller\MainController;

// Les tests unitaires vérifient la sortie d'une méthode
class MainControllerTest extends TestCase
{
    public function testHomeMethod()
    {
        // Création des mocks (doublures de mes Repository)
        $menuRepoMock = $this->createMock(MenuRepository::class);
        $dishRepoMock = $this->createMock(DishRepository::class);

        // Utilisation de la méthode findBy de mes Repository. Je définis ensuite arbitrairement des données de retour
        $menuRepoMock->method('findBy')->willReturn(['menu1', 'menu2']);
        $dishRepoMock->method('findBy')->willReturn(['dish1', 'dish2']);

        // Instanciation du contrôleur et appel de la méthode à tester
        $controller = new MainController();
        $response = $controller->home($menuRepoMock, $dishRepoMock);

        // Assertions
        $this->assertNotNull($response);
        $this->assertStringContainsString('home.html.twig', $response->getContent());
        $this->assertStringContainsString('menu1', $response->getContent());
        $this->assertStringContainsString('dish1', $response->getContent());
    }

    public function testAboutMethod()
    {
        $controller = new MainController();
        $response = $controller->about();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('about.html.twig', $response->getContent());
    }
}
