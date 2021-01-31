<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractTestCase extends KernelTestCase
{
    private const BASE_URI = 'http://127.0.0.1:8080/api/';

    /**
     * Store the guzzle http client.
     */
    protected Client $client;

    /**
     * Store the entity manager.
     */
    protected EntityManager $entityManager;

    /**
     * Setup a client and entity manager.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = self::bootKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        $loader = new Loader();
        foreach ($this->getFixtures() as $fixture) {
            $loader->addFixture($fixture);
        }
        $purger = new ORMPurger();
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->execute($loader->getFixtures());

        $client = new Client([
            'base_uri' => self::BASE_URI,
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json',
                'User-Agent' => 'Test',
            ]
        ]);
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
                    'Authorization' => sprintf('Bearer %s', $jwt),
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Test',
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

    /**
     * Get the list of fixtures.
     */
    private function getFixtures(): iterable
    {
        return [
            new \App\DataFixtures\User(self::$container->get('security.password_encoder')),
            new \App\DataFixtures\Article(),
            new \App\DataFixtures\Reaction(),
            new \App\DataFixtures\Session(),
        ];
    }
}
