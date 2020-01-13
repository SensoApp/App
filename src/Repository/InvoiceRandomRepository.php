<?php

namespace App\Repository;

use App\Entity\InvoiceRandom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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

    public function updateStatus($status, $id, $filepath = null)
    {
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

        $query->execute();
    }

}
