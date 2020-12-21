<?php

namespace App\Tests;

use App\Entity\Session;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SessionTest extends KernelTestCase
{
    private const BASE_URI = 'http://127.0.0.1';

    /**
     * Store the guzzle http client.
     *
     * @var Client
     */
    private $client;

    /**
     * Setup a client.
     */
    protected function setUp(): void
    {
        parent::tearDown();
        $this->client = new Client(['base_uri' => self::BASE_URI]);
    }

    /**
     * Unset the client.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->client);
    }

    public function testCanCreateAClientSession(): void
    {
        /** @var EntityManager */
        $em = self::bootKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        /** @var Session */
        $session = $em->getRepository(Session::class)->findAll()[0];

        $response = $this->client->post('/sessions?ip_address='.$session->getIpAddress());
        $sessionResponse = json_decode($response->getBody());

        $this->assertEquals($session->getId(), $sessionResponse->id);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCanRetrieveTheCurrentSession(): void
    {
        /** @var EntityManager */
        $em = self::bootKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        /** @var Session */
        $session = $em->getRepository(Session::class)->findAll()[0];

        $response = $this->client->get('/sessions?ip_address='.$session->getIpAddress());
        $sessionResponse = json_decode($response->getBody());

        $this->assertEquals($session->getId(), $sessionResponse->id);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetting404IfSessionDoesNotExist(): void
    {
        try {
            $response = $this->client->get('/sessions?ip_address=400.00.00.1111');
            $this->assertNotEquals(200, $response->getStatusCode());
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
            $this->assertEquals(404, $response->getStatusCode());
            $this->assertEquals('Could not find session for ip 400.00.00.1111', json_decode($response->getBody())->message);
        }
    }
}