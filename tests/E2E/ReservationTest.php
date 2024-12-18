<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;
use Facebook\WebDriver\WebDriverBy;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reservation;

// E2E ou les tests de "bout en bout" simulent des scénarios réels d'utilisation, en interagissant avec l'application comme un utilisateur final
class ReservationTest extends PantherTestCase
{
    public function testNewReservation(): void
    {
        $client = static::createPantherClient();
        $client->request('GET', '/reservation');

        // Trouver le bouton par son sélecteur CSS et cliquer dessus
        $client->findElement(WebDriverBy::cssSelector('#new-reservation-button'))->click();

        $client->waitFor('#new-reservation');
        $client->submitForm('Voir les disponibilités', [
            'seats' => 2,
            'date' => date('d/m/Y')
        ]);

        $client->waitFor('#new-reservation .hour');
        $hour = $client->findElement(WebDriverBy::cssSelector('#new-reservation .hour:first-child'))->getText();
        $client->findElement(WebDriverBy::cssSelector('#new-reservation .hour:first-child'))->click(); //13h

        $client->submitForm('Valider ma réservation', [
            'surname' => 'Doe',
            'name' => 'John',
            'phone_number' => '0123456789',
            'mail' => 'john.doe@example.com',
        ]);

        // Vérifier que la redirection se fait vers la page de confirmation
        $this->assertEquals('/reservation', parse_url($client->getCurrentURL())['path']);

        $client->waitFor('.reservation-confirmed');

        // Vérifier que la page de confirmation contient le bon contenu
        $this->assertSelectorTextContains('p', 'Votre réservation du ' . date('Y-m-d') . ' à ' . $hour . ' a bien été prise en compte.');
    }

    public function testGetReservation(): void
    {
        self::bootKernel();
        $container = self::getContainer();
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

        // Vérifier que la page contient le texte attendu
        $client->waitFor('.check-reservation-response');
        $this->assertSelectorTextContains('.check-reservation-response', $user->getName() . ' ' . $user->getSurname());
    }
}
