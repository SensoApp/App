<?php

namespace App\Repository;

use App\Entity\ContactEndClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ContactEndClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactEndClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactEndClient[]    findAll()
 * @method ContactEndClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactEndClientRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContactEndClient::class);
    }


    public function listOfAllClients(): array
    {
        $qr = $this->createQueryBuilder('u')
            ->select('u.id','u.clientname', 'u.type', 'u.contactperson')
            ->leftJoin('u.mail', 'm')
            ->addSelect( 'm.mail')
            ->leftJoin('u.phone', 'p')
            ->addSelect('p.phonenumber');


        return $qr->getQuery()
                  ->execute();
    }


    public function viewOneClient($id): array
    {
        $qr = $this->createQueryBuilder('u')
            ->select('u.id','u.clientname', 'u.type', 'u.contactperson')
            ->leftJoin('u.mail', 'm')
            ->addSelect( 'm.mail')
            ->leftJoin('u.phone', 'p')
            ->addSelect('p.phonenumber')
            ->where('u.id = :id');

        $qr->setParameter('id', $id);


        return $qr->getQuery()
            ->execute();
    }

    // /**
    //  * @return ContactEndClient[] Returns an array of ContactEndClient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContactEndClient
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
