<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-08-03
 * Time: 20:50
 */

namespace App\Timesheet;


use App\Entity\Timesheet;

class TimesheetHydrator
{
    private $timesheet;

    public function __construct()
    {
        $this->timesheet = new Timesheet();
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
            }
        }
        $month =$request->request->get('currentMonth');
        $this->timesheet->setMonth($month);
        $this->timesheet->setUser($security);
        $this->timesheet->setStatus('Created');

        return $this->timesheet;
    }
}