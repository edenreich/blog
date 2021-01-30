<?php

namespace App\Tests;

use App\Entity\Session;
use GuzzleHttp\Exception\ClientException;

class SessionTest extends AbstractTestCase
{
    public function testCanCreateAClientSession(): void
    {
        /** @var Session */
        $session = $this->entityManager->getRepository(Session::class)->findAll()[0];

        $response = $this->client->post('sessions?ip_address='.$session->getIpAddress());
        $sessionResponse = json_decode($response->getBody());

        $this->assertEquals($session->getId(), $sessionResponse->id);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCanRetrieveTheCurrentSession(): void
    {
        /** @var Session */
        $session = $this->entityManager->getRepository(Session::class)->findAll()[0];

        $response = $this->client->get('sessions?ip_address='.$session->getIpAddress());
        $sessionResponse = json_decode($response->getBody());

        $this->assertEquals($session->getId(), $sessionResponse->id);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetting404IfSessionDoesNotExist(): void
    {
        try {
            $response = $this->client->get('sessions?ip_address=400.00.00.1111');
            $this->assertNotEquals(200, $response->getStatusCode());
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
            $this->assertEquals(404, $response->getStatusCode());
            $this->assertEquals('Could not find session for ip 400.00.00.1111', json_decode($response->getBody())->message);
        }
    }
}
