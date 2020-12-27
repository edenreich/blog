<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class Reaction extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $articles = $manager->getRepository(\App\Entity\Article::class)->findAll();
        $sessions = $manager->getRepository(\App\Entity\Session::class)->findAll();
        $reactions = ['like', 'love', 'dislike'];

        foreach ($articles as $article) {
            $reaction = new \App\Entity\Reaction(['type' => $reactions[mt_rand(0, 2)], 'article' => $article, 'session' => $sessions[mt_rand(0, 9)]]);

            $manager->persist($reaction);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 3;
    }
}
