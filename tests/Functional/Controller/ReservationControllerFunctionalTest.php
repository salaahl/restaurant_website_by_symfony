<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationControllerFunctionalTest extends WebTestCase
{
    public function testReservationPageLoads()
    {
        $client = static::createClient();
        $client->request('GET', '/reservation');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testReservationNewReservationPost()
    {
        $client = static::createClient();
        $client->request('POST', '/reservation', ['action' => 'new_reservation', 'date' => '2024-12-25', 'seats' => 2]);

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testConfirmationPageRedirectsWhenReservationMissing()
    {
        $client = static::createClient();
        $client->request('GET', '/confirmation');

        $this->assertResponseRedirects('/reservation');
    }
}
