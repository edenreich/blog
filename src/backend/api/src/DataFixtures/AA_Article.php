<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AA_Article extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        foreach (range(0, 10) as $key) {
            $article = new \App\Entity\Article();
            $article->setTitle($faker->text(20));
            $article->setContent($faker->randomHtml());
            $article->setSlug($faker->slug(3));
            $article->setMetaKeywords(sprintf("%s, %s, %s", $faker->word(), $faker->word(), $faker->word()));
            $article->setMetaDescription($faker->text(50));
    
            $manager->persist($article);
        }

        $manager->flush();
    }
}
