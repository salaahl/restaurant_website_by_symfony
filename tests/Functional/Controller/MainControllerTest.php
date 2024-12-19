<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

// Les tests fonctionnels vérifient la sortie d'une action de contrôleur, comme une page Web
class MainControllerTest extends WebTestCase
{
    public function testHomePage()
    {
        // Effectuer une requête GET sur la page d'accueil
        $client = static::createClient();
        $client->request('GET', '/');

        // Vérifier que la réponse est une page valide (code 200)
        $this->assertResponseIsSuccessful('Erreur lors du chargement de la page');

        // Vérifier que le contenu contient des éléments spécifiques (ex. : "menus")
        $this->assertSelectorTextContains('#menu-title-container > .title > h2', 'Menu');
    }

    public function testAboutPage()
    {
        // Effectuer une requête GET sur la page "About"
        $client = static::createClient();
        $client->request('GET', '/about');

        // Vérifier que la réponse est une page valide (code 200)
        $this->assertResponseIsSuccessful('Erreur lors du chargement de la page');
        $this->assertSelectorTextContains('h2', 'A propos de nous');
    }
}
