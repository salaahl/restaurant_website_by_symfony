<?php

namespace App\Tests;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\HttpFoundation\Response;
use Facebook\WebDriver\WebDriverBy;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reservation;

// E2E ou les tests de "bout en bout" simulent des scénarios réels d'utilisation, en interagissant avec l'application comme un utilisateur final
class ReservationTest extends PantherTestCase
{
    public function testGetReservation(): void
    {
        self::bootKernel();
        $container = self::getContainer();  // Utilisez getContainer() au lieu de self::$container
        $manager = $container->get(EntityManagerInterface::class);

        // Repositories
        $reservationRepository = $manager->getRepository(Reservation::class);
        $user = $reservationRepository->find(4);
        $this->assertNotNull($user);

        // Effectuer une requête GET sur la page de réservation
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/reservation');

        // Trouver le bouton par son sélecteur CSS et cliquer dessus
        $button = $client->findElement(WebDriverBy::cssSelector('button#check-reservation-button'));
        $button->click();

        // Remplir le formulaire et le soumettre
        $client->findElement(WebDriverBy::cssSelector('input[name="mail"]'))->sendKeys($user->getMail());
        $client->findElement(WebDriverBy::cssSelector('input[name="surname"]'))->sendKeys($user->getSurname());
        $client->findElement(WebDriverBy::cssSelector('#check_reservation-form button[type="submit"]'))->click();

        // Vérifier que la page contient le texte "Hello World"
        $client->waitFor('.check-reservation-response');
        $this->assertSelectorTextContains('.check-reservation-response', $user->getName() . ' ' . $user->getSurname());
    }
}
