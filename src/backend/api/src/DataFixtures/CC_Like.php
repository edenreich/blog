<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CC_Like extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $articles = $manager->getRepository(\App\Entity\Article::class)->findAll();
        $sessions = $manager->getRepository(\App\Entity\Session::class)->findAll();
        $reactions = ['like', 'love', 'dislike'];

        foreach ($articles as $article) {
            $like = new \App\Entity\Like(['reactionType' => $reactions[mt_rand(0,2)], 'article' => $article, 'session' => $sessions[mt_rand(0,9)]]);
    
            $manager->persist($like);
        }

        $manager->flush();
    }
}
