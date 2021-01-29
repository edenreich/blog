<?php

namespace App\Tests;

use App\Entity\Session;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SessionTest extends KernelTestCase
{
    private const BASE_URI = 'http://127.0.0.1:8080/api/';

    /**
     * Store the guzzle http client.
     */
    private Client $client;

    /**
     * Store the entity manager.
     */
    private EntityManager $entityManager;

    /**
     * Setup a client and entity manager.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = static::bootKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        $client = new Client(['base_uri' => self::BASE_URI]);
        try {
            $jwt = json_decode($client->post('authorize', [
                RequestOptions::JSON => [
                    'username' => 'admin@gmail.com',
                    'password' => 'admin',
                ],
            ])->getBody(), true)['token'];
            $this->client = new Client([
                'base_uri' => self::BASE_URI.'v1/',
                RequestOptions::HEADERS => [
                    'User-Agent' => 'Test',
                    'Authorization' => sprintf('Bearer %s', $jwt),
                    'Content-Type' => 'application/json',
                ],
            ]);
        } catch (ClientExceptionInterface $exception) {
            dd('Could not fetch access token: '.$exception->getMessage());
        }
    }

    /**
     * Unset the client and the entity manager.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->client);
        unset($this->entityManager);
    }

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
