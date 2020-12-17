<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Article extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $article = new \App\Entity\Article();
        $article->setTitle("test title");
        $article->setContent("test content");
        $article->setSlug("test slug");
        $article->setMetaKeywords("test meta keywords");
        $article->setMetaDescription("test meta description");
        
        $manager->persist($article);
        $manager->flush();
    }
}
