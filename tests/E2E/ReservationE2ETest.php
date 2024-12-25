<?php

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;
use Facebook\WebDriver\WebDriverBy;
use Doctrine\ORM\EntityManagerInterface;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;

use App\Entity\Reservation;

// E2E ou les tests de "bout en bout" simulent des scénarios réels d'utilisation, en interagissant avec l'application comme un utilisateur final
class ReservationE2ETest extends PantherTestCase
{
    public function testMakeANewReservation(): void
    {
        $client = static::createPantherClient();
        $client->request('GET', '/reservation');
        $date = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $seats = 2;
        $wait = new WebDriverWait($client, 10);

        $wait->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('loader'))
        );

        // Trouver le bouton par son sélecteur CSS et cliquer dessus
        $client->waitFor('#new-reservation-button');
        $client->findElement(WebDriverBy::cssSelector('#new-reservation-button'))->click();

        $client->waitFor('#new-reservation');
        $client->submitForm('Voir les disponibilités', [
            'seats' => $seats,
            'date' => $date->format('d/m/Y')
        ]);

        $wait->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('lds-hourglass'))
        );

        $client->waitFor('#new-reservation .hour');
        $hour = $client->findElement(WebDriverBy::cssSelector('#new-reservation .hour-container:first-of-type .hour'))->getAttribute('value');
        $client->findElement(WebDriverBy::cssSelector('#new-reservation .hour-container:first-of-type label'))->click();

        $wait->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('complete-reservation-form'))
        );

        $client->submitForm('Valider ma réservation', [
            'surname' => 'Doe',
            'name' => 'John',
            'phone_number' => '0123456789',
            'email' => 'john.doe@example.com',
        ]);

        // Vérifier que la redirection se fait vers la page de confirmation et qu'elle contient le bon contenu
        $client->waitFor('.reservation-confirmed');
        $this->assertSelectorTextContains('p', sprintf('Votre réservation du %s à %sh pour %s personne(s) a bien été prise en compte.', $date->format('d/m/Y'), $hour, $seats), 'Erreur lors de l\'affichage de la page de confirmation');
    }

    public function testGetUserReservation(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $manager = $container->get(EntityManagerInterface::class);

        // Repositories
        $reservationRepository = $manager->getRepository(Reservation::class);
        $reservation = $reservationRepository->findOneBy([]);
        $this->assertNotNull($reservation, 'Aucune réservation n\'est associée à cet ID');

        // Effectuer une requête GET sur la page de réservation
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/reservation');
        $wait = new WebDriverWait($client, 10);

        $wait->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('loader'))
        );

        // Trouver le bouton par son sélecteur CSS et cliquer dessus
        $client->waitFor('#check-reservation-button');
        $client->findElement(WebDriverBy::cssSelector('#check-reservation-button'))->click();

        // Remplir le formulaire et le soumettre
        $client->waitFor('#check-reservation-form');
        $client->submitForm('Rechercher', [
            'email' => $reservation->getEmail(),
            'surname' => $reservation->getSurname()
        ]);

        // Vérifier que la page contient le texte attendu
        $client->waitFor('.check-reservation-response');
        $this->assertSelectorTextContains('.check-reservation-response', $reservation->getName() . ' ' . $reservation->getSurname(), 'Aucune réservation à ce nom');
    }
}
