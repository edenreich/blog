<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class Notification extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $sessions = $manager->getRepository(\App\Entity\Session::class)->findAll();

        foreach (range(0, 10) as $key) {
            /** @var \App\Entity\Session */
            $session = $sessions[mt_rand(0, 10)];
            $manager->refresh($session);
            if (null !== $session->getNotification()) {
                continue;
            }

            $notification = new \App\Entity\Notification();
            $notification->setIsEnabled(true);
            $notification->setEmail($faker->email);
            $notification->setSession($session);
            $manager->persist($notification);
            $manager->flush();
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}
