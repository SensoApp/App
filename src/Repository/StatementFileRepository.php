<?php

namespace App\Repository;

use App\Entity\StatementFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\ORMException;

/**
 * @method StatementFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatementFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatementFile[]    findAll()
 * @method StatementFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatementFileRepository extends ServiceEntityRepository
{
    private $config;

    public function __construct(ManagerRegistry $registry)
    {
        $this->config = new Configuration();
        $this->config->setResultCacheImpl(new ApcuCache());

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

                    if($res['referencemovement'] === $datatocheck->getReferencemovement() ){

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

        $query ='SELECT * 
                 FROM (
                       SELECT  u.firstname, u.lastname,
                       sum(s.amount) as Current_Balance,
                       (  
                           select sum(invoice.totalamount) from invoice
                           where invoice.user = (select mail from user where id = s.user_id)
                           and paymentstatus = :unpaid
                           and status = :validated
                        )
                         Estimated
                    FROM statement_file s
                    INNER JOIN user u on s.user_id = u.id
                    WHERE s.user_id = :userid
                    GROUP BY u.firstname, u.lastname) firstQuery,
                    (
                        SELECT  s.account
                        FROM statement_file s
                        INNER JOIN user u on s.user_id = u.id
                        WHERE s.user_id = :userid
                        GROUP BY account
                    ) secondQuery
                    LIMIT 0,1';

        $stmt = $em->getConnection()->prepare($query);
        $param =['userid' => $userid, 'unpaid' => 'Unpaid', 'validated' => 'Validated - sent to client'];
        $stmt->execute($param);

        return $stmt->fetchAll();
    }

    public function selectAllForPagination($userid)
    {
        $em = $this->getEntityManager()->createQueryBuilder()->select( 's')->from('App:StatementFile', 's');

        $em->andWhere('s.user=:userid');

        $em->orderBy('s.operationdate', 'DESC');

        $em->setParameters([

            'userid' => $userid
        ]);

       return $em->getQuery()
                ->getResult();
    }

    public function searchByCriterion($datatosearch, $userid)
    {
        $em = $this->getEntityManager()->createQueryBuilder()->select( 's')->from('App:StatementFile', 's');

        $req = $datatosearch->request;

            if( !empty($req->get('Min-date')) && !empty($req->get('Max-date'))){


                $em->andWhere('s.operationdate between :mindate and :maxdate');
                $em->andWhere('s.user= :userid');

                $em->setParameters([

                    'mindate' => $req->get('Min-date'),
                    'maxdate' => $req->get('Max-date'),
                    'userid'  => $userid
                ]);

            } elseif(!empty($req->get('Min-amount')) && !empty($req->get('Max-amount'))){

                $em->andWhere('s.amount between :minamount and :maxamount');
                $em->andWhere('s.user= :userid');

                $em->setParameters([

                    'minamount' => $req->get('Min-amount'),
                    'maxamount' => $req->get('Max-amount'),
                    'userid'    => $userid

                ]);
            }

             return   $em->getQuery()
                     ->getResult();

    }

    public function selectPerOpertionsRef($refs)
    {
        $em = $this->getEntityManager();

        foreach ($refs as $ref){

            $new[] = $ref;
        }

        $con = $em->createQuery('select s 
                                        from App\Entity\StatementFile s 
                                        where s.referencemovement in (:refs)');

        $con->setParameters(['refs' => $new]);

        return $con->getResult();

           /* $quer = 'select * from statement_file s where s.referencemovement in (?)';

            $st = $em->getConnection()->executeQuery($quer,[$new], [\Doctrine\DBAL\Connection::PARAM_STR_ARRAY]);

        return $st->fetchAll();*/
    }

    public function searchBalancePerConsultant()
    {
        $em = $this->getEntityManager();

        $sql = 'select round(sum(s.amount), 2) balance, s.user_id consultants, u.firstname, u.lastname
                from statement_file s 
                inner join user u on u.id = s.user_id
                group by user_id, u.firstname, u.lastname';

        $query = $em->getConnection()->prepare($sql);

        $query->execute();

        return $query->fetchAll();
    }

    public function searchAllMovements()
    {

        $em = $this->getEntityManager();

        $sql = 'SELECT  u.firstname, u.lastname, s.*
                FROM statement_file s 
                INNER JOIN user u ON u.id = s.user_id
                ORDER BY s.operationdate desc';

        $query = $em->getConnection()->prepare($sql);

        $query->execute();

        return $query->fetchAll();

    }

    public function searchByCriterionAdmin($datatosearch)
    {
        $em = $this->getEntityManager();

        $req = $datatosearch->request;

        if( !empty($req->get('Min-date')) && !empty($req->get('Max-date'))){

            $sql = 'SELECT  u.firstname, u.lastname, s.*
                FROM statement_file s 
                INNER JOIN user u ON u.id = s.user_id
                WHERE s.operationdate between :mindate and :maxdate
                ORDER BY s.operationdate desc';

            $param =['mindate' => $req->get('Min-date'), 'maxdate' => $req->get('Max-date')];


        } elseif(!empty($req->get('Min-amount')) && !empty($req->get('Max-amount'))){

            $sql = 'SELECT  u.firstname, u.lastname, s.*
                FROM statement_file s 
                INNER JOIN user u ON u.id = s.user_id
                WHERE s.amount between :minamount and :maxamount
                ORDER BY s.operationdate desc';

            $param =['minamount' => $req->get('Min-amount'), 'maxamount' => $req->get('Max-amount')];
        }

        $query = $em->getConnection()->prepare($sql);

        $query->execute($param);

        return $query->fetchAll();

    }

    public function searchByIbanStatement($iban){

        $em = $this->getEntityManager();

        $sql = 'select u.mail, u.firstname, u.lastname 
                from bank_details b
                inner join contact c on b.contact_id = c.id
                inner join user u on u.contact_id = c.id 
                where b.ibanstatement = :iban';

        $query = $em->getConnection()->prepare($sql);

        $param = ['iban' => $iban];

        $query->execute($param);

        return $query->fetchAll();
    }
}
