<?php

namespace App\Repository;

use App\Entity\InvoiceRandom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InvoiceRandom|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvoiceRandom|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvoiceRandom[]    findAll()
 * @method InvoiceRandom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRandomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvoiceRandom::class);
    }

    public function findDataRandomInvoice($id) : array
    {
        $em = $this->getEntityManager();

        $contract = 'SELECT i.id as invoiceid, i.*, u.*
                     FROM invoice_random i
                     INNER JOIN user u ON i.user_id = u.id
                     WHERE i.id = :id
                    ';
        $stmt = $em->getConnection()->prepare($contract);
        $param = ['id' => $id];
        $stmt->execute($param);

        return $stmt->fetchAll();
    }

    public function updateStatus($status, $id, $filepath = null, $amountForUnits = null)
    {
        if($amountForUnits === null){

            $query = $this->getEntityManager()
                ->createQuery(
                    'update 
                              App\Entity\InvoiceRandom t 
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

        } else {

            $query = $this->getEntityManager()
                ->createQuery(
                    'update 
                              App\Entity\InvoiceRandom t 
                          set t.status = :status, 
                              t.path= :path,
                              t.amount = :amount,
                              t.updatedAt = :updatdate
                          where 
                              t.id = :id'
                );

            $query->setParameters([
                'status' =>$status,
                'id' => $id,
                'path' => $filepath,
                'amount' => $amountForUnits,
                'updatdate' => new \DateTime('now')

            ]);

        }

        $query->execute();
    }

    public function updateStatusAfterValidation($status, $id, $paymentstatus)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'update 
                          App\Entity\InvoiceRandom t 
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

    public function selectRandomInvoiceAndUserData() : array
    {
        $query = $this->getEntityManager()
                        ->createQuery(
                            'select 
                                        i.id, 
                                        i.status,
                                        i.createdAt,
                                        i.description, 
                                        i.amount, 
                                        i.paymentstatus, 
                                        u.firstname, 
                                        u.lastname
                                    from 
                                        App\Entity\InvoiceRandom i
                                    inner join 
                                        App\Entity\User u
                                    where
                                    u.id = i.user'
                            );

        return $query->getResult();
    }

    public function updateStatusAfterValidationRandomInvoice($status, $id, $paymentstatus)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'update 
                          App\Entity\InvoiceRandom t 
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

}
