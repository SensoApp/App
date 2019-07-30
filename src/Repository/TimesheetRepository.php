<?php

namespace App\Repository;

use App\Entity\Timesheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Timesheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Timesheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Timesheet[]    findAll()
 * @method Timesheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimesheetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Timesheet::class);
    }


    public function getTimesheetData($user, $month)
    {

       $query =  $this->getEntityManager()
             ->createQuery(
               'SELECT t
                    FROM App\Entity\Timesheet t
                    WHERE t.user= :user 
                    AND t.month = :month
                    AND t.status = :status'

             )->setParameters([
                                'month'=>$month,
                                'user'=>$user,
                                'status' => 'Created'
                              ]);

        return $query->execute();
    }

    // /**
    //  * @return Timesheet[] Returns an array of Timesheet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Timesheet
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
