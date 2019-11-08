<?php

namespace App\Repository;

use App\Entity\Timesheet;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Timesheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Timesheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Timesheet[]    findAll()
 * @method Timesheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimesheetRepository extends ServiceEntityRepository
{

    const TIMESHEET_CREATED = 'Created';
    const TIMESHEET_SENT = 'Sent';
    const TIMESHEET_VALIDATED = 'Validated';


    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Timesheet::class);
    }


    public function getTimesheetData($user, $month, $edit = null)
    {

       $query =  $this->getEntityManager()
             ->createQuery(
               'SELECT t
                    FROM App\Entity\Timesheet t
                    WHERE t.user = :user 
                    AND t.month = :month
                    AND t.status = :status'
             );

       if($edit !== null){

           $query->setParameters([
               'month'=>$month,
               'user'=>$user,
               'status' => self::TIMESHEET_SENT
           ]);

       } else {

           $query->setParameters([
               'month'=>$month,
               'user'=>$user,
               'status' => self::TIMESHEET_CREATED
           ]);

       }

        return $query->execute();
    }

    public function updateStatus($status, $id, $filepath)
    {
        $query = $this->getEntityManager()
                      ->createQuery(
                        'update 
                                  App\Entity\Timesheet t 
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

    public function selectPerMonth($user, $month)
    {
        $query =  $this->getEntityManager()

            ->createQuery(
                'SELECT t
                    FROM App\Entity\Timesheet t
                    WHERE t.user= :user 
                    AND t.month = :month'

            )->setParameters([

                'month'=>$month,
                'user'=>$user
            ]);

         foreach ($query->getResult() as $req){

             return $req->getId();
         }

    }

    public function updateTimesheet($request, $id)
    {

        $arr = [
           'nbreDaysWorked' => $nbreofdays = $request->request->get('nbrOfDays') > 0 ? $request->request->get('nbrOfDays') : 0 ,
           'nbreOfSaturdays' => $nbreOfSaturdays = $request->request->get('nbreOfSaturdays') > 0 ? $request->request->get('nbreOfSaturdays') : 0 ,
           'nbreOfSundays' =>  $nbreOfSundays = $request->request->get('nbreOfSundays') > 0 ? $request->request->get('nbreOfSundays') : 0,
           'nbrOfBankHolidays' =>$nbrOfBankHolidays = $request->request->get('nbrOfBankHolidays') > 0 ? $request->request->get('nbrOfBankHolidays') : 0,
            'updatedAt' => new DateTime(),
            'id' => $id
        ];

        try {

            $query = $this->getEntityManager()
            ->createQuery(
                         'update
                                  App\Entity\Timesheet t
                              set t.nbreDaysWorked = :nbreDaysWorked,
                                  t.nbreOfSaturdays= :nbreOfSaturdays,
                                  t.nbreOfSundays = :nbreOfSundays,
                                  t.nbrOfBankHolidays = :nbrOfBankHolidays,
                                  t.updatedAt = :updatedAt
                              where
                                  t.id = :id'

            )->setParameters($arr);


        } catch (\Exception $exception){

            echo $exception->getMessage();
        }

        return $query->execute();
    }



        //dd($request->request->get('nbrOfDays'), $request->request->get('currentMonth') );

    // /**
    //  * @return Timesheet[] Returns an array of Timesheet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Timesheet
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
