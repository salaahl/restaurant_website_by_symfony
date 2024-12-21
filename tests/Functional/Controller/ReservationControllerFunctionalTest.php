<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationControllerFunctionalTest extends WebTestCase
{
    public function testReservationPageLoads()
    {
        $client = static::createClient();
        $client->request('GET', '/reservation');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form'); // Exemple : vérifier qu'un formulaire est affiché
    }

    public function testReservationNewReservationPost()
    {
        $client = static::createClient();
        $client->request('POST', '/reservation', ['action' => 'new_reservation']);

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testConfirmationPageRedirectsWhenReservationMissing()
    {
        $client = static::createClient();
        $client->request('GET', '/confirmation');

        $this->assertResponseRedirects('/');
    }
}
