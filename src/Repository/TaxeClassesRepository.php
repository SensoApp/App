<?php

namespace App\Repository;

use App\Entity\TaxeClasses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TaxeClasses|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaxeClasses|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaxeClasses[]    findAll()
 * @method TaxeClasses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxeClassesRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaxeClasses::class);

        $this->em = $this->getEntityManager();
    }

    /**
     * @param $salary
     * @param $taxeclass
     */
    public function getTaxeAmount($salary, $taxeclass)
    {
        $salary = (int)$salary;

        $em = $this->getEntityManager();

        if($salary >= 9035){

            $query = 'SELECT round('.$taxeclass.'/fromsalarytranche, 2) as Taxe_rate,' .$taxeclass.' as Taxe_amount 
                      FROM taxe_classes
                      WHERE fromsalarytranche  = 9035';

            $stmt = $em->getConnection()->prepare($query);

            $stmt->execute();

        } else {

            $query  = 'SELECT '.$taxeclass.' as Taxe_rate
                       FROM taxe_classes
                       WHERE fromsalarytranche < :salary
                       ORDER BY fromsalarytranche DESC 
                       LIMIT 0,1';

            $stmt = $this->em->getConnection()->prepare($query);

            $param = ['salary' => $salary];

            $stmt->execute($param);
        }

       return $stmt->fetchAll();

    }

    // /**
    //  * @return TaxeClasses[] Returns an array of TaxeClasses objects
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
    public function findOneBySomeField($value): ?TaxeClasses
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
