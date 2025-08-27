<?php

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;
use Facebook\WebDriver\WebDriverBy;
use Doctrine\ORM\EntityManagerInterface;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;
use App\Tests\Traits\DatabaseTestTrait;
use App\Entity\Reservation;

// E2E ou les tests de "bout en bout" simulent des scénarios réels d'utilisation, en interagissant avec l'application comme un utilisateur final
class ReservationE2ETest extends PantherTestCase
{
    use DatabaseTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        // Réinitialiser la base avant chaque test
        $this->loadDatabaseFixtures();
    }

    public function testMakeANewReservation(): void
    {
        $client = static::createPantherClient([
            'browser' => static::FIREFOX,
            'external_base_uri' => 'http://127.0.0.1:8000', // avec APP_ENV=test symfony serve
        ]);
        $client->request('GET', '/reservation');
        $date = new \DateTime('-2 hour', new \DateTimeZone('Europe/Paris')); // Retrait de l'heure d'été
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
            'date' => $date->format('Y-m-d')
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

        // Récupérer les champs
        $surnameField = $client->findElement(WebDriverBy::cssSelector('#complete-reservation-form input[name="surname"]'));
        $name = $client->findElement(WebDriverBy::cssSelector('#complete-reservation-form input[name="name"]'));
        $phoneNumber = $client->findElement(WebDriverBy::cssSelector('#complete-reservation-form input[name="phone_number"]'));
        $emailField = $client->findElement(WebDriverBy::cssSelector('#complete-reservation-form input[name="email"]'));

        $surnameField->sendKeys('Doe');
        $name->sendKeys('John');
        $phoneNumber->sendKeys('0123456789');
        $emailField->sendKeys('john.doe@example.com');

        $client->findElement(WebDriverBy::cssSelector('#complete-reservation-form'))->submit();

        // Vérifier que la redirection se fait vers la page de confirmation et qu'elle contient le bon contenu
        $wait->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('.reservation-confirmed'))
        );
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
        $client = static::createPantherClient([
            'browser' => static::FIREFOX,
            'external_base_uri' => 'http://127.0.0.1:8000', // serveur Symfony lancé en APP_ENV=test
        ]);

        $crawler = $client->request('GET', '/reservation');

        // Attendre que le loader disparaisse
        $wait = new WebDriverWait($client->getWebDriver(), 10);
        $wait->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('loader'))
        );

        // Attendre que le bouton soit visible
        $wait->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('#check-reservation-button'))
        );

        // Cliquer sur le bouton
        $client->findElement(WebDriverBy::cssSelector('#check-reservation-button'))->click();

        // Attendre que le formulaire apparaisse
        $wait->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('#check-reservation-form'))
        );

        // Attendre que le champ soit visible et scrollable
        $wait = new WebDriverWait($client->getWebDriver(), 10);
        $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('surname')));

        // Récupérer les champs
        $surnameField = $client->findElement(WebDriverBy::id('surname'));
        $emailField   = $client->findElement(WebDriverBy::id('email'));

        // Remplir les champs
        $surnameField->sendKeys($reservation->getSurname());
        $emailField->sendKeys($reservation->getEmail());

        // Soumettre le formulaire
        $client->findElement(WebDriverBy::cssSelector('#check-reservation-form'))->submit();


        // Vérifier la réponse
        $wait->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('.check-reservation-response'))
        );
        $this->assertSelectorTextContains(
            '.check-reservation-response',
            $reservation->getName() . ' ' . $reservation->getSurname()
        );
    }
}
