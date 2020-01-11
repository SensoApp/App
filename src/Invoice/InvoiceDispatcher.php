<?php


namespace App\Invoice;


use App\Entity\Mail;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceDispatcher
{

    private $filepath = [];
    private $accountantemail;
    private $clientemail;
    private $entityManager;
    private $mailerService;


    public function __construct(EntityManagerInterface $entityManager, MailerService $mailerService)
    {
        $this->entityManager = $entityManager;
        $this->mailerService = $mailerService;
    }

    public function retrieveDataForFinalInvoice($invocedata)
    {
        foreach ($invocedata as $inv){

           $this->filepath['invoicepath'] = $inv->getPath();

            /**
             * TODO : Change this in order to keep that part for Invoice triggered by generated Timesheet
             */
           //$this->filepath['timesheetpath'] = $inv->getTimesheet()->getPath();

           $req = $this->entityManager
                       ->getRepository(Mail::class)
                       ->findBy(['clientcontact' => $inv->getContract()->getClientName()->getId()]);

           foreach ($req as $reqmail){

               $this->clientemail = $reqmail->getMail();
           }

           $this->sendMailToAllParties($this->filepath, $this->clientemail);
        }
    }

    public function sendMailToAllParties(array $filepath, $emailclient)
    {
        $message = 'Please find attached the Invoice and the related timesheet';
        $subject = 'Invoice';

        $this->mailerService->sendMultipleAttachement($emailclient, $message,  $subject, $filepath);

        /**
         * TODO Add accountant email address
         */
        // send email to accountant (subject Vente... and vente email address)

    }


}