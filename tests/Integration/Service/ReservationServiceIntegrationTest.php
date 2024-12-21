<?php

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\ReservationService;

class ReservationServiceIntegrationTest extends KernelTestCase
{
    public function testReservationServiceCreate()
    {
        self::bootKernel();
        $container = static::getContainer();

        $service = $container->get(ReservationService::class);

        $result = $service->createReservationDate(new Request([], ['date' => '2024-12-25']));
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('success', $result['status']); // Exemple
    }
}
