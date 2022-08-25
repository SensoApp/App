<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllNoneAdminUsers()
    {
        $q = $this->createQueryBuilder('u')
            ->where("JSON_SEARCH(u.roles, 'all', :role) IS NULL")
            ->setParameter('role', 'ROLE_ADMIN')
            ->getQuery();

        return $q->getArrayResult();
    }

    public function findByFullName(string $firstName, string $lastName)
    {
        $q = $this->createQueryBuilder('u')
            ->where("u.firstname = :firstname AND u.lastname = :lastname")
            ->setParameter('firstname', $firstName)
            ->setParameter('lastname', $lastName)
            ->setMaxResults(1)
            ->getQuery();
        return $q->getOneOrNullResult();
    }

    public function findByFirstName(string $firstName)
    {
        $q = $this->createQueryBuilder('u')
            ->where("u.firstname = :firstname")
            ->setParameter('firstname', $firstName)
            ->setMaxResults(1)
            ->getQuery();

        return $q->getOneOrNullResult();
    }

    public function findByLastName(string $lastName)
    {
        $q = $this->createQueryBuilder('u')
            ->where("u.lastname = :lastname")
            ->setParameter('lastname', $lastName)
            ->setMaxResults(1)
            ->getQuery();

        return $q->getOneOrNullResult();
    }
}
