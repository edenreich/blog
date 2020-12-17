<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BB_Session extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        foreach (range(0, 10) as $key) {
            $session = new \App\Entity\Session();
            $session->setIpAddress($faker->ipv4());
    
            $manager->persist($session);
        }

        $manager->flush();
    }
}
