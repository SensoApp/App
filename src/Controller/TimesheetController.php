<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-07-07
 * Time: 21:47
 */

namespace App\Controller;


use App\Entity\Timesheet;
use App\Service\DateGeneratorService;
use App\Service\GeneratePdfReport;
use App\Timesheet\TimeSheetHydrator;
use App\Timesheet\TimesheetValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class TimesheetController extends AbstractController
{
    private $security;
    private $timesheet;
    private $entitymanager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {

        $this->security = $security;
        $this->timesheet = new Timesheet();
        $this->entitymanager = $entityManager;
    }

    /**
     * @Route(path="/timesheet", name="timesheet")
     */
    public function viewTimesheet(DateGeneratorService $dateGenerator, Request $request)
    {

        $month = $request->server->get('REQUEST_TIME');

        $dates = $dateGenerator->periodRequest($month, $request->getQueryString());

        return $this->render('timesheet/timesheetview.html.twig', [

           'date' => $dates
       ]);
    }

    /**
     * @param Request $request
     * @Route(path="/timesheet/save", name="saveTimesheet")
     */
    public function saveTimesheet(
                                  Request $request,
                                  TimesheetValidator $timesheetValidator,
                                  GeneratePdfReport $generatePdfReport,
                                  TimeSheetHydrator $timeSheetHydrator
                                )
    {
        $security = $this->security->getToken()->getUsername();
        $validation =  $timesheetValidator->validateTimeSheet($request, $security);
        $timesheet = $timeSheetHydrator->hydrateTimesheet($request, $security);

        if($validation){

            try{
                $this->entitymanager->persist($timesheet);
                $this->entitymanager->flush();

            } catch (\Exception $e){

                echo  $e->getMessage();
            }

            /////// HOOOK THIS PROCESS TO AN EVENT OR SEND THIS TO A QUEUE//////
            ///
            $generatePdfReport->reportConstructTimeSheet($timesheet->getMonth());


            return new JsonResponse([
                                        'success' => '  Your Timesheet has been saved in the database, 
                                                   you  will receive a pdf copy shortly...'
                                    ]);
        }

        return new JsonResponse([
                                    'error' => '  A Timesheet for the same period and 
                                            for the same user has been generated already, 
                                            if you wish to modify it please got to your dashboard and edit it'
                                ]);
            //Retrieve data posted and check the sum of days ->done
            // within the days collected count the number of Saturdays or Sundays ->done

        //Generate PDF timesheet -> done
        //Save to DB with inital status -> done

        //Send email to the related user with the pdf generated and the link to the app page with the generated timesheet
        // on the UI create an entry with its status and a button to validate
    }


    public function editTimesheet()
    {

    }

    public function deleteTimesheet()
    {

    }

}