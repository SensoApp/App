<?php

namespace App\DataFixtures;

use App\Controller\TransactionController;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Faker;

class UserFixtures extends Fixture
{

    private $encoder;
    private $faker;
    private $transactionController;

    public function __construct(UserPasswordEncoderInterface $encoder, TransactionController $transactionController)
    {
        $this->encoder = $encoder;
        $this->transactionController = $transactionController;
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // create admin user
        $admin = new User();
        $admin->setFirstname('Senso')
              ->setLastname('Admin')
              ->setUsername('admin@senso.lu')
              ->setPassword($this->encoder->encodePassword($admin, 'senso1234'))
              ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // create freelancer users and assigned there revolut accounts
        $revolutAccounts = $this->transactionController->getRevolutAccounts();

        foreach ($revolutAccounts as $revolutAccount) {
            if($revolutAccount->name) {
                $fullName = explode(" ", $revolutAccount->name);
                $freelancer = new User();
                $freelancer->setFirstname($fullName[0])
                    ->setLastname($fullName[1])
                    ->setUsername($this->faker->email)
                    ->setPassword($this->encoder->encodePassword($freelancer, 'senso1234'))
                    ->setRevolutAccountId($revolutAccount->id);
                $manager->persist($freelancer);
            }
        }

        $manager->flush();
    }
}
