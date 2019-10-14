<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-08-03
 * Time: 20:50
 */

namespace App\Timesheet;


use App\Entity\ClientContract;
use App\Entity\Timesheet;
use Doctrine\ORM\EntityManagerInterface;

class TimesheetHydrator
{
    private $timesheet;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->timesheet = new Timesheet();
        $this->entityManager = $entityManager;
    }

    public function hydrateTimesheet($request, $security) : Timesheet
    {
        foreach ($request->request as $dayType => $days){

            $days = (int) $days;

            switch ($dayType){
                case $dayType === 'nbrOfDays':
                    $this->timesheet->setNbreDaysWorked($days);
                    break;
                case $dayType === 'nbreOfSundays' && $days > 0:
                    $this->timesheet->setNbreOfSundays($days);
                    break;
                case $dayType === 'nbreOfSaturdays'&& $days > 0:
                    $this->timesheet->setNbreOfSaturdays($days);
                    break;
                case $dayType === 'nbrOfBankHolidays' && $days > 0:
                    $this->timesheet->setNbrOfBankHolidays($days);
                    break;
                case $dayType === 'clientcontract':

                    $clientcontract = $this->entityManager
                                           ->getRepository(ClientContract::class)
                                           ->findBy(['id'=> $days]);

                    foreach ($clientcontract as $cli){

                        $this->timesheet->setContract($cli);

                    }
                    break;
            }
        }
        $month =$request->request->get('currentMonth');
        $this->timesheet->setMonth($month);
        $this->timesheet->setUser($security);
        $this->timesheet->setStatus('Created');

        return $this->timesheet;
    }
}