<?php

namespace App\DataFixtures;

use App\Entity\User as EntityUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class User extends Fixture implements OrderedFixtureInterface
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new EntityUser();
        $admin->setEmail('admin@gmail.com');
        $admin->setPassword($this->encoder->encodePassword($admin, 'admin'));
        $admin->setRoles(['ROLE_USER']);
        $manager->persist($admin);

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 1;
    }
}
