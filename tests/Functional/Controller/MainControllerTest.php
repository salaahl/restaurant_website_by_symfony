<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// Les tests fonctionnels vérifient la sortie d'une action de contrôleur, comme l'affichage d'une page web
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
        $client = static::createClient();
        $client->request('GET', '/about');

        $this->assertResponseIsSuccessful('Erreur lors du chargement de la page');
        $this->assertSelectorTextContains('.text-container h2', 'Un voyage culinaire unique');
    }
}
