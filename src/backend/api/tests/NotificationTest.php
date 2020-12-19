<?php

namespace App\Tests;

use App\Entity\Notification;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NotificationTest extends KernelTestCase
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
     * 
     * @return void
     */
    protected function setUp(): void
    {
        parent::tearDown();
        $this->client = new Client(['base_uri' => self::BASE_URI]);
    }

    /**
     * Unset the client.
     * 
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->client);
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
