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
                       ->createQuery('select u
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


   public function multipleSelectionInvoiceClientTimesheet($invoiceid)
    {
        $query = $this->getEntityManager()
                      ->createQuery(
                          'select i 
                                from App\Entity\Invoice i
                                inner join App\Entity\ClientContract c
                                inner join App\Entity\Timesheet t 
                                where i.id='.$invoiceid
                      );


        return $query->getResult();

    }

    public function selectInvoiceAndUserData() : array
    {
        $query = $this->getEntityManager()
                      ->createQuery(
                          'select 
                                    i.id, 
                                    i.status,
                                    i.date, 
                                    i.totalamount, 
                                    i.paymentstatus, 
                                    i.user, 
                                    u.firstname, 
                                    u.lastname
                                from 
                                    App\Entity\Invoice i
                                inner join 
                                    App\Entity\User u
                                where 
                                    u.email = i.user'
                            );

        return $query->getResult();
    }
}
