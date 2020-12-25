<?php

namespace App\Tests;

use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NotificationTest extends KernelTestCase
{
    private const BASE_URI = 'http://127.0.0.1';

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
        $this->client = new Client(['base_uri' => self::BASE_URI]);
    }

    /**
     * Unset the client.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->client);
        unset($this->entityManager);
    }

    public function testCanRegisterForNotifications(): void
    {
        throw new \Exception('pending implemention');
    }

    public function testCanDisableNotificationForTheCurrentSession(): void
    {
        throw new \Exception('pending implemention');
    }
}
