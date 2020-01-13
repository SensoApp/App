<?php

namespace App\Repository;

use App\Entity\InvoiceCreationData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method InvoiceCreationData|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvoiceCreationData|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvoiceCreationData[]    findAll()
 * @method InvoiceCreationData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceCreationDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvoiceCreationData::class);
    }

    //Pass user id to retrieve its contract details (rate etc...) and return the result to calculate the invoice
    public function findDataManualInvoice($id) : array
    {
        $em = $this->getEntityManager();

        $contract = 'SELECT u.*, c.id as clientcontractid, c.*, i.*
                     FROM client_contract c
                     INNER JOIN invoice_creation_data i ON i.user_id = c.user_id
                     INNER JOIN user u ON c.user_id = u.id
                     WHERE i.id = :id
                    ';

        $stmt = $em->getConnection()->prepare($contract);
        $param = ['id' => $id];
        $stmt->execute($param);

        return $stmt->fetchAll();
    }
}
