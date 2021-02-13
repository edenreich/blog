<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Navigation AS NavigationEntity;

class Navigation extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $navigations = [
            [
                'name' => 'Dashboard',
                'url' => '/dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'childrens' => []
            ],
            [
                'name' => 'Content',
                'url' => '/content',
                'icon' => 'fas fas fa-edit',
                'childrens' => [
                    [
                        'name' => 'list',
                        'url' => '/content/list',
                        'icon' => 'fas fa-circle nav-icon',
                    ],
                    [
                        'name' => 'create',
                        'url' => '/content/create',
                        'icon' => 'fas fa-circle nav-icon',
                    ],
                ]
            ],
        ];

        foreach ($navigations as $navigationData) {
            $navigation = new NavigationEntity();
            $navigation->setName($navigationData['name']);
            $navigation->setUrl($navigationData['url']);
            $navigation->setIcon($navigationData['icon']);

            $manager->persist($navigation);

            foreach ($navigationData['childrens'] as $subnavigationData) {
                $subnavigation = new NavigationEntity();
                $subnavigation->setName($subnavigationData['name']);
                $subnavigation->setUrl($subnavigationData['url']);
                $subnavigation->setIcon($subnavigationData['icon']);
                $subnavigation->setParent($navigation);
                
                $manager->persist($subnavigation);
            }
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 2;
    }
}