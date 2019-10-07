<?php

namespace App\Repository;

use App\Entity\Invoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    /**
     * @param $id
     */
    public function findTimesheetAndContractForInvoice($id) : array
    {
        $email = $this->getEntityManager()
                       ->createQuery('select u.nbreDaysWorked,
                                                  u.nbrOfBankHolidays,
                                                  u.nbreOfSaturdays,
                                                  u.nbreOfSundays,
                                                  u.user,
                                                  u.month,
                                                  u.id as timesheetid,
                                                  c.rate,
                                                  c.extrapercentsatyrday,
                                                  c.extrapercentsunday,
                                                  c.extrapercentbankholidays,
                                                  c.id as contractid
                                            from App\Entity\Timesheet u 
                                            inner join App\Entity\ClientContract c
                                            where u.id='.$id);

       return $email->getResult();

    }

    public function retrieveLastInvoiceId()
    {
        $id = $this->getEntityManager()
                   ->createQuery('select i.invoicenumber
                                       from App\Entity\Invoice i
                                       order by i.createdAt desc'
                                );

        $id->setMaxResults(1);

        $idretrieved = $id->getResult();

        if(!empty($idretrieved)){

            $invoicenumber = $idretrieved[0]['invoicenumber'] + 1;

        } else {

            $invoicenumber = 1;
        }

        return $invoicenumber;

    }

    public function updateStatus($status, $id, $filepath = null)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'update 
                          App\Entity\Invoice t 
                      set t.status = :status, 
                          t.path= :path,
                          t.updatedAt = :updatdate
                      where 
                          t.id = :id'
            );

        $query->setParameters([
            'status' =>$status,
            'id' => $id,
            'path' => $filepath,
            'updatdate' => new \DateTime('now')

        ]);

        $query->execute();
    }

    public function updateStatusAfterValidation($status, $id, $paymentstatus)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'update 
                          App\Entity\Invoice t 
                      set t.status = :status, 
                          t.paymentstatus = :paymentstatus,
                          t.updatedAt = :updatdate
                      where 
                          t.id = :id'
            );

        $query->setParameters([
            'status' =>$status,
            'id' => $id,
            'paymentstatus' => $paymentstatus,
            'updatdate' => new \DateTime('now')

        ]);

        $query->execute();
    }

    // /**
    //  * @return Invoice[] Returns an array of Invoice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Invoice
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
