<?php

namespace App\Tests;

use App\Entity\Notification;
use App\Entity\Session;
use GuzzleHttp\RequestOptions;

class NotificationTest extends AbstractTestCase
{
    public function testCanRegisterForNotifications(): void
    {
        /** @var Session */
        $session = $this->entityManager->getRepository(Session::class)->findAll()[mt_rand(0, 9)];
        $email = 'test@gmail.com';

        $response = $this->client->post('notifications', [
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
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCanDisableNotificationForTheCurrentSession(): void
    {
        /** @var Session */
        $session = $this->entityManager->getRepository(Session::class)->findAll()[mt_rand(0, 9)];
        $notification = new Notification();
        $notification->setEmail('test@gmail.com');
        $notification->setIsEnabled(true);
        $session->setNotification($notification);
        $this->entityManager->flush();

        $response = $this->client->put('notifications/'.$notification->getId(), [
            RequestOptions::JSON => [
                'is_enabled' => false,
                'session' => $session->getId(),
                'email' => $notification->getEmail(),
            ],
        ]);

        /** @var Notification */
        $notificationAfterResponse = $session->getNotification();
        $this->entityManager->refresh($notificationAfterResponse);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(false, $notificationAfterResponse->getIsEnabled());
    }
}
