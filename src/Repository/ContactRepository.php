<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-05-13
 * Time: 20:29
 */

namespace App\Repository;


use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ContactRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function listOfAllContacts():array
    {
        $qr = $this->createQueryBuilder('u')
                    ->select('u.id','u.firstname', 'u.lastname', 'u.contacttype')
                    ->leftJoin('u.mail', 'm')
                    ->addSelect( 'm.mail')
                    ->leftJoin('u.phone', 'p')
                    ->addSelect('p.phonenumber');


        return $qr->getQuery()
                  ->execute();
    }

    public function zoomInContact($id)
    {

        $qr = $this->createQueryBuilder('u')
                    ->select(
                             'u.id',
                                    'u.firstname',
                                    'u.lastname',
                                    'u.contacttype'
                            )
                    ->leftJoin('u.phone', 'p')
                    ->addSelect('p.phonenumber')
                     ->leftJoin('u.mail', 'm')
                    ->addSelect('m.mail')
                    ->leftJoin('u.address', 'a')
                    ->addSelect(
                                'a.street',
                                       'a.postcode',
                                       'a.city'
                                )
                    ->leftJoin('u.bankdetails', 'b')
                    ->addSelect(
                                'b.iban',
                                       'b.biccode'
                                )
                    ->leftJoin('u.citizenshipdetails', 'c')
                    ->addSelect(
                                 'c.documentType',
                                        'c.documentId',
                                        'c.expireDate'
                                )
                    ->leftJoin('u.contract', 'co')
                    ->addSelect(
                                'co.contractType',
                                        'co.startDate',
                                        'co.probationPeriodEndDate',
                                        'co.endDate'
                                )
                    ->where('u.id ='.$id);

        return $qr->getQuery()
                  ->getArrayResult();

    }
}