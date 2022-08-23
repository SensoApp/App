<?php

namespace App\Repository;

use App\Entity\TaxeClasses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

        $query  = 'SELECT '.$taxeclass.' as Taxe_rate
                   FROM taxe_classes
                   WHERE fromsalarytranche < :salary
                   ORDER BY fromsalarytranche DESC 
                   LIMIT 0,1';

        $stmt = $this->em->getConnection()->prepare($query);

        $param = ['salary' => $salary];

        $stmt->execute($param);


       return $stmt->fetchAll();

    }

}
