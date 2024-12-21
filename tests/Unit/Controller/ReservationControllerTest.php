<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\ReservationController;
use App\Service\ReservationService;

class ReservationControllerTest extends TestCase
{
    public function testReservationNewReservation()
    {
        $serviceMock = $this->createMock(ReservationService::class);
        $serviceMock->method('createReservationDate')->willReturn(['status' => 'success']);

        $controller = new ReservationController($serviceMock);

        $request = new Request([], ['action' => 'new_reservation'], [], [], [], ['REQUEST_METHOD' => 'POST']);
        $response = $controller->reservation($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => 'success']),
            $response->getContent()
        );
    }

    public function testReservationInvalidAction()
    {
        $serviceMock = $this->createMock(ReservationService::class);
        $controller = new ReservationController($serviceMock);

        $request = new Request([], ['action' => 'unknown_action'], [], [], [], ['REQUEST_METHOD' => 'POST']);
        $response = $controller->reservation($request);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Action inconnue ou invalide']),
            $response->getContent()
        );
    }

    public function testConfirmationRedirection()
    {
        $serviceMock = $this->createMock(ReservationService::class);
        $controller = new ReservationController($serviceMock);

        $response = $controller->confirmation(null);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/', $response->headers->get('Location'));
    }
}
