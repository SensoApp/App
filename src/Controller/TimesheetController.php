<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-07-07
 * Time: 21:47
 */

namespace App\Controller;


use App\Form\TimesheetType;
use App\Service\DateGeneratorService;
use function PHPSTORM_META\type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TimesheetController extends AbstractController
{
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
    public function saveTimesheet(Request $request)
    {
        dd($request);

        $parseRequest = $request->request->get('nbrOfDays');


        $totalNbreOfDays = (int) $parseRequest;


        //get the lenght of the set of 1 received from the request


        dd($totalNbreOfDays);

        //Retrieve data posted and check the sum of days
            // within the days collected count the number of Saturdays or Sundays

        //Generate PDF timesheet
        //Save to DB with inital status

        //Send email to the related user with the pdf generated and the link to the app page with the generated timesheet
        // on the UI create an entry with its status and a button to validate
    }

    public function editTimesheet()
    {

    }

    public function deleteTimesheet()
    {

    }


    public function validateTimesheet()
    {

    }

}