<?php

namespace App\Tests;

use App\Entity\Notification;
use App\Entity\Session;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
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

        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('notifications', true));

        $this->client = new Client(['base_uri' => self::BASE_URI]);
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

    public function testCanRegisterForNotifications(): void
    {
        /** @var Session */
        $session = $this->entityManager->getRepository(Session::class)->findAll()[mt_rand(0, 9)];
        $emails = ['test@gmail.com', 'test2@gmail.com', 'test3@gmail.com'];
        $email = $emails[mt_rand(0, 2)];

        $this->client->post('/notifications', [
            RequestOptions::JSON => [
                'is_enabled' => true,
                'session' => $session->getId(),
                'email' => $email,
            ],
        ]);

        /** @var Notification[] */
        $notifications = $this->entityManager
            ->getRepository(Notification::class)
            ->findAll();

        $this->assertCount(1, $notifications);
        $this->assertEquals(true, $notifications[0]->getIsEnabled());
    }

    public function testCanDisableNotificationForTheCurrentSession(): void
    {
        /** @var Session */
        $session = $this->entityManager->getRepository(Session::class)->findAll()[mt_rand(0, 9)];

        $session->getNotification();

        $this->entityManager->flush();
    }
}
