<?php
/**
 * @author <smouheb@senso.lu>
 */

namespace App\Controller;


use App\Entity\Timesheet;
use App\Service\DateGeneratorService;
use App\Service\GeneratePdfReport;
use App\Timesheet\TimesheetHydrator;
use App\Timesheet\TimesheetValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TimesheetController extends AbstractController
{
    private $security;
    private $timesheet;
    private $entitymanager;

    const TIMESHEET_VALIDATED = 'Validated';

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {

        $this->security = $security;
        $this->timesheet = new Timesheet();
        $this->entitymanager = $entityManager;
    }

    /**
     * @Route(path="/user/timesheet", name="timesheet")
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
        TimesheetHydrator $timeSheetHydrator
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
            ///add created date as datetime and updated date as datetime
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

    }

    /**
     *
     * @param Request $request
     *
     * @param $id
     *
     * @Route(path="/user/timesheet/pdf/{id}", name="viewtimesheetpdf")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewTimeSheetPdfInBrowser($id)
    {
        $path = $this->entitymanager
                     ->getRepository(Timesheet::class)
                     ->find($id);

        return $this->file($path->getPath(), null, ResponseHeaderBag::DISPOSITION_INLINE);

    }

    /**
     * @param Request $request
     *
     * @param $id
     *
     * @Route(path="/user/timesheet/downloadpdf/{id}", name="downloadtimesheet")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadTimeSheet($id)
    {
        $path = $this->entitymanager
                     ->getRepository(Timesheet::class)
                     ->find($id);

        return $this->file($path->getPath());

    }

    /**
     * @Route(path="/newadmin/uploadtimesheet", name="upload_timesheet")
     */
    public function validateTimeSheet(Request $request)
    {
        //dd($request);
        $filepath  = $this->getParameter('kernel.project_dir').'/report/timesheet_signed';
        /**
         * @var UploadedFile $uploadedfile
         */
       $uploadedfile =  $request->files->get('file');

       $month = $request->request->get('month');

       $originalfilename = pathinfo($uploadedfile->getClientOriginalName(), PATHINFO_FILENAME);
       $newfilename = $originalfilename.uniqid().'.'.$uploadedfile->guessExtension();
       $finalpath = $filepath.'/'.$newfilename;

       $uploadedfile->move($filepath, $newfilename);

       $user = $this->security->getToken()->getUsername();

       $entity = $this->entitymanager->getRepository(Timesheet::class);

       $id = $entity->selectPerMonth($user, $month);

       $entity->updateStatus(self::TIMESHEET_VALIDATED, $id, $finalpath);

       $this->addFlash('success', 'Timesheet validated, the invoice process has started');

       return $this->redirectToRoute('user_dashboard');

        /**
         * TODO upload timehseet
         * - manage exception
         * - Add exception when the query returns null (wrong month etc...)
         * */

    }

    public function editTimesheet()
    {

    }


    /**
     * @Route("/newadmin/deletetimesheet/{id}", name="deletetimesheet")
     */
    public function deleteTimesheet($id)
    {
        $timesheettodelete = $this->entitymanager
                                  ->getRepository(Timesheet::class)
                                  ->find($id);

        $this->entitymanager->remove($timesheettodelete);
        $this->entitymanager->flush();

        $this->addFlash('success', 'Timesheet deleted successfully');

        return $this->redirectToRoute('user_dashboard');

    }

}