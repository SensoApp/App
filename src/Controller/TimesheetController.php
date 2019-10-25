<?php
/**
 * @author <smouheb@senso.lu>
 */

namespace App\Controller;


use App\Entity\ClientContract;
use App\Entity\Timesheet;
use App\Events\TimeSheetValidationEvent;
use App\Message\SendDocument;
use App\Service\DateGeneratorService;
use App\Service\GeneratePdfReport;
use App\Service\UploadHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class TimesheetController extends AbstractController
{
    private $security;
    private $timesheet;
    private $entitymanager;

    const TIMESHEET_VALIDATED = 'Validated';
    const EDIT_TIMESHEET = 'edit';

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {

        $this->security = $security;
        $this->timesheet = new Timesheet();
        $this->entitymanager = $entityManager;
        $this->security = $security;
    }

    /**
     * @Route(path="/user/timesheet", name="timesheet")
     */
    public function viewTimesheet(DateGeneratorService $dateGenerator, Request $request)
    {

        $month = $request->server->get('REQUEST_TIME');

        $dates = $dateGenerator->periodRequest($month, $request->getQueryString());

        $contract = $this->entitymanager->getRepository(ClientContract::class)
                                        ->findBy(['user' =>$this->getUser()->getId()]);

        return $this->render('timesheet/timesheetview.html.twig', [

           'date' => $dates,
           'contractdetails' => $contract
       ]);
    }

    /**
     * @param Request $request
     * @Route(path="/timesheet/save", name="saveTimesheet")
     */
    public function saveTimesheet(Request $request, MessageBusInterface $messageBus, Security $security)
    {
        $message = new SendDocument($request, $security);
        $messageBus->dispatch($message);

        return new JsonResponse([
                                    'success' => 'Your Timesheet is beeing processed, you  will receive a pdf copy shortly...'
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
     * @Route(path="/user/uploadtimesheet", name="upload_timesheet")
     */
    public function validateUploadTimeSheet(Request $request, UploadHelper $uploadHelper, EventDispatcherInterface $eventDispatcher)
    {
        try{

           $fileuploaded =  $uploadHelper->uploadTimesheet($request);

           switch($fileuploaded){

               case $fileuploaded['status'] === 'success':

                    $timesheetobject = $this->entitymanager->getRepository(Timesheet::class)->find($fileuploaded['id']);

                    $event = new TimeSheetValidationEvent($timesheetobject);
                    $eventDispatcher->dispatch(TimeSheetValidationEvent::NAME, $event);

                   $this->addFlash('success', sprintf('Timesheet validated, %s', $fileuploaded['message']));

                   break;

               case $fileuploaded['status'] === 'error':

                   $this->addFlash('error', sprintf('Error : %s',$fileuploaded['message']));

                   break;
           }

            return $this->redirectToRoute('user_dashboard');

        } catch (\Exception $exception){

           return $exception->getMessage();
        }

    }

    /**
     * @Route(path="/timesheet/edit/{id}", name="modify_timesheet")
     */
    public function editTimesheet(DateGeneratorService $dateGenerator, Request $request,$id, GeneratePdfReport $generatePdfReport)
    {

        if($request->request->get('edit')){

            $this->entitymanager
                  ->getRepository(Timesheet::class)
                  ->updateTimesheet($request, $id);

            $generatePdfReport->reportConstructTimeSheet($request->request->get('currentMonth'), self::EDIT_TIMESHEET);


            return new JsonResponse([
                'success' => 'Timesheet has been modified successfully.. a copy is on its way'
            ]);
        }

        $timesheettoedit = $this->entitymanager
                                ->getRepository(Timesheet::class)
                                ->find($id);

        $dates = $dateGenerator->periodRequest($timesheettoedit->getMonth());


        return $this->render('timesheet/timesheetEdit.html.twig', [

            'date' => $dates,
            'id' => $id
        ]);

    }


    /**
     * @Route("/newadmin/deletetimesheet/{id}", name="deletetimesheet")
     */
    public function deleteTimesheet($id)
    {
        $timesheettodelete = $this->entitymanager
                                  ->getRepository(Timesheet::class)
                                  ->find($id);

        $filetodelete = $timesheettodelete->getPath();

        try{

            $this->entitymanager->remove($timesheettodelete);
            $this->entitymanager->flush();

        } catch (\Exception $exception){

            echo $exception->getMessage();
        }

        unlink($filetodelete);

        $this->addFlash('success', 'Timesheet deleted successfully');

        return $this->redirectToRoute('user_dashboard');

    }

}