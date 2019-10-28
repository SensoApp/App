<?php

namespace App\Repository;

use App\Entity\StatementFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @method StatementFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatementFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatementFile[]    findAll()
 * @method StatementFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatementFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatementFile::class);
    }

    protected function selectUserPerIban()
    {
        $query = $this->getEntityManager()
                      ->createQuery(

                      'SELECT u.id, b.ibanstatement
                            FROM App\Entity\StatementFile s
                            INNER join App\Entity\BankDetails b
                            INNER join App\Entity\Contact co
                            INNER join App\Entity\User u
                            WHERE b.ibanstatement = s.account
                            AND b.contact = co.id
                            AND co.id = u.contact'
                      );

        return $query->getResult();
    }

    public function updateEntityStatement()
    {
        $values = $this->selectUserPerIban();

        foreach ($values as $value) {
            try{

                $upd = $this->getEntityManager()
                    ->createQuery(

                        'UPDATE App\Entity\StatementFile s
                              SET s.user= :userid, s.updatedAt= :updatedate
                              WHERE s.account= :relatediban
                              AND s.user IS NULL'
                    );

                $upd->setParameters([

                    'userid' => $value['id'],
                    'updatedate' => new \DateTime('now'),
                    'relatediban' => $value['ibanstatement']

                ]);

                $upd->execute();

            } catch (ORMException $e){

                echo $e->getMessage(); die;
            }

        }

    }

    public function removeDuplicates(array $data)
    {
        try{

            foreach ($data as $datatocheck){

                $query = $this->getEntityManager()
                    ->createQuery('select distinct s.referencemovement from App\Entity\StatementFile s');

                foreach ($query->getResult() as $res){

                    if($res['referencemovement'] === $datatocheck->getReferencemovement()){

                        $this->getEntityManager()->remove($datatocheck);
                    }
                };
            }
        } catch (ORMException $ORMException){

            return $ORMException->getMessage();
        }
    }

    public function lastUploadedPerUserAndAccount()
    {
        $em = $this->getEntityManager();

        $query ='SELECT  u.firstname, u.lastname, s.account, max(s.updated_at) as last_updated
                  FROM statement_file s
                  INNER JOIN user u on s.user_id = u.id
                  WHERE s.user_id IS NOT NULL
                  group by u.firstname, u.lastname, s.account';

        $stmt = $em->getConnection()->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function selectSumPerUserStaement($userid)
    {
        $em = $this->getEntityManager();

        $query ='SELECT  u.firstname, u.lastname, s.account,
                       sum(s.amount) as Current_Balance,
                        case
                            when (
                                      select count(paymentstatus)
                                      from invoice
                                      where paymentstatus = :unpaid
                                      and i.user = (select mail from user where id = s.user_id)
                                 )  > 1 then (
                                               select sum(invoice.totalamount) from invoice
                                               where invoice.user = (select mail from user where id = s.user_id)
                                               and paymentstatus = :unpaid
                                               and status = :validated
                                            )
                            else i.totalamount
                            end as Estimated, 
                            i.totalamount
                    FROM statement_file s
                    INNER JOIN user u on s.user_id = u.id
                    INNER JOIN invoice i on i.user = u.mail
                    WHERE s.user_id = :userid
                    AND i.paymentstatus = :unpaid
                    AND i.status = :validated
                    GROUP BY u.firstname, u.lastname, s.account, i.totalamount';

        $stmt = $em->getConnection()->prepare($query);
        $param =['userid' => $userid, 'unpaid' => 'Unpaid', 'validated' => 'Validated - sent to client'];
        $stmt->execute($param);

        return $stmt->fetchAll();
    }
}
