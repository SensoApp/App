<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-09-17
 * Time: 17:22
 */

namespace App\Events;


use App\Entity\Timesheet;
use Symfony\Component\EventDispatcher\Event;

class TimeSheetValidationEvent extends Event
{

    public const NAME = 'timesheet.validated';

    protected $timesheet;

    public function __construct(Timesheet $timesheet)
    {
        $this->timesheet = $timesheet;
    }

    public function getTimesheetId()
    {
        return $this->timesheet->getId();
    }
}