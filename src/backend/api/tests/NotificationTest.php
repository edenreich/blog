<?php

namespace App\Tests;

use GuzzleHttp\Client;
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

    public function testCanRegisterForNotifications(): void
    {
        throw new \Exception('pending implemention');
    }

    public function testCanDisableNotificationForTheCurrentSession(): void
    {
        throw new \Exception('pending implemention');
    }
}
