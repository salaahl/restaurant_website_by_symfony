<?php

namespace App\Tests\Integration\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\ReservationService;
use Symfony\Component\HttpFoundation\Request;

class ReservationServiceIntegrationTest extends KernelTestCase
{
    public function testReservationServiceCreate()
    {
        self::bootKernel();
        $container = static::getContainer();

        $service = $container->get(ReservationService::class);

        $result = $service->createReservation(new \DateTime(), 1);
        $this->assertIsArray($result);
    }
}
