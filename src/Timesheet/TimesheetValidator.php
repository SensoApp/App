<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-07-27
 * Time: 23:49
 */

namespace App\Timesheet;


use App\Entity\Timesheet;
use Doctrine\ORM\EntityManagerInterface;

class TimesheetValidator
{
    private $entity;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entity = $entityManager;
    }

    public function validateTimeSheet($request, $user): bool
    {
        $month = $request->request->get('currentMonth');
        $req = $this->entity->getRepository(Timesheet::class)
            ->findBy([
                'month' => $month,
                'user' => $user
            ]);

        if(!empty($req)){

            foreach ($req as $arg) {

                if ($user === $arg->getUser() && $month === $arg->getMonth()) {

                    return false;
                }
            }
        }

        return true;
    }
}