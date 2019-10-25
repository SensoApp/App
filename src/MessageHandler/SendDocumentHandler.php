<?php


namespace App\MessageHandler;


use App\Message\SendDocument;
use App\Service\GeneratePdfReport;
use App\Timesheet\TimesheetHydrator;
use App\Timesheet\TimesheetValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Security;

class SendDocumentHandler implements MessageHandlerInterface
{

    private $generatePdfReport;

    private $timesheetValidator;

    private $timeSheetHydrator;

    private $entityManager;

    private $security;


    public function __construct(
                                GeneratePdfReport $generatePdfReport,
                                TimesheetValidator $timesheetValidator,
                                TimesheetHydrator $timeSheetHydrator,
                                EntityManagerInterface $entityManager
                                )
    {
        $this->generatePdfReport = $generatePdfReport;
        $this->timesheetValidator = $timesheetValidator;
        $this->timeSheetHydrator = $timeSheetHydrator;
        $this->entityManager = $entityManager;
    }

    public function __invoke(SendDocument $sendDocument)
    {
        //$validation = $this->timesheetValidator->validateTimeSheet($sendDocument->getRequest(), $sendDocument->getUsername());

        $timesheet = $this->timeSheetHydrator->hydrateTimesheet($sendDocument->getRequest(), $sendDocument->getUsername()->getToken()->getUsername());

        $this->entityManager->persist($timesheet);
        $this->entityManager->flush();


        $this->generatePdfReport->reportConstructTimeSheet($timesheet->getMonth());



    }

}