<?php

namespace App\Repository;

use App\Entity\SimulationFees;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SimulationFees|null find($id, $lockMode = null, $lockVersion = null)
 * @method SimulationFees|null findOneBy(array $criteria, array $orderBy = null)
 * @method SimulationFees[]    findAll()
 * @method SimulationFees[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SimulationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SimulationFees::class);
    }

    // /**
    //  * @return SimulationFees[] Returns an array of SimulationFees objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SimulationFees
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
