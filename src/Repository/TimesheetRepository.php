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

    const TIMESHEET_CREATED = 'Created';
    const TIMESHEET_SENT = 'Sent';
    const TIMESHEET_VALIDATED = 'Validated';


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
                                'status' => self::TIMESHEET_CREATED
                              ]);

        return $query->execute();
    }

    public function updateStatus($status, $id)
    {
        $query = $this->getEntityManager()
                      ->createQuery(
                        'update 
                              App\Entity\Timesheet t 
                              set t.status = :status
                              where t.id = :id'
                      );

        $query->setParameters([
                                'status' =>$status,
                                'id' => $id

                                ]);
        $query->execute();
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
