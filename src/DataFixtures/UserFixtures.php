<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        // create freelancer account
        $freelancer = new User();
        $freelancer->setFirstname('Jon')
                   ->setLastname('Snow')
                   ->setUsername('user@senso.lu')
                   ->setPassword($this->encoder->encodePassword($freelancer, 'senso1234'));

        // create admin account
        $admin = new User();
        $admin->setFirstname('Senso')
              ->setLastname('Admin')
              ->setUsername('admin@senso.lu')
              ->setPassword($this->encoder->encodePassword($admin, 'senso1234'))
              ->setRoles(['ROLE_ADMIN']);

        $manager->persist($freelancer);
        $manager->persist($admin);
        $manager->flush();
    }
}
