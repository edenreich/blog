<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class Article extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        foreach (range(1, 10) as $index => $value) {
            $article = new \App\Entity\Article();
            $article->setTitle($faker->text(20));
            $article->setContent($faker->randomHtml());
            $article->setSlug($faker->slug(3));
            $article->setCreatedAt($faker->dateTimeThisMonth());
            $article->setMetaKeywords(sprintf('%s, %s, %s', $faker->word(), $faker->word(), $faker->word()));
            $article->setMetaDescription($faker->text(50));

            if (0 === $index % 2) {
                $article->setPublishedAt(new \DateTime());
            }

            $manager->persist($article);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 2;
    }
}
