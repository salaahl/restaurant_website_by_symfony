<?php

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;
use Facebook\WebDriver\WebDriverBy;
use Doctrine\ORM\EntityManagerInterface;

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

        // Trouver le bouton par son sélecteur CSS et cliquer dessus
        $client->findElement(WebDriverBy::cssSelector('#new-reservation-button'))->click();

        $client->waitFor('#new-reservation');
        $client->submitForm('Voir les disponibilités', [
            'seats' => $seats,
            'date' => $date->format('d/m/Y')
        ]);

        $client->waitFor('#new-reservation .hour');
        $hour = $client->findElement(WebDriverBy::cssSelector('#new-reservation .hour:first-child'))->getAttribute('value');
        $client->findElement(WebDriverBy::cssSelector('#new-reservation .hour:first-child'))->click(); //13h

        $client->submitForm('Valider ma réservation', [
            'surname' => 'Doe',
            'name' => 'John',
            'phone_number' => '0123456789',
            'email' => 'john.doe@example.com',
        ]);

        // Vérifier que la redirection se fait vers la page de confirmation
        $this->assertMatchesRegularExpression('/\/confirmation\/\d+/', parse_url($client->getCurrentURL())['path'], 'Erreur lors de la redirection vers la page de confirmation');

        // Vérifier que la page de confirmation contient le bon contenu
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
        $user = $reservationRepository->findOneBy([]);
        $this->assertNotNull($user, 'Aucune réservation n\'est associée à cet ID');

        // Effectuer une requête GET sur la page de réservation
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/reservation');

        // Trouver le bouton par son sélecteur CSS et cliquer dessus
        $client->waitFor('#check-reservation-button');
        $client->findElement(WebDriverBy::cssSelector('#check-reservation-button'))->click();

        // Remplir le formulaire et le soumettre
        $client->waitFor('#check_reservation-form');
        $client->submitForm('Rechercher', [
            'email' => $user->getEmail(),
            'surname' => $user->getSurname()
        ]);

        // Vérifier que la page contient le texte attendu
        $client->waitFor('.check-reservation-response');
        $this->assertSelectorTextContains('.check-reservation-response', $user->getName() . ' ' . $user->getSurname(), 'Aucune réservation à ce nom');
    }
}
