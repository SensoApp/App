<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-07-29
 * Time: 18:37
 */

namespace App\Service;

use App\Entity\Invoice;
use App\Entity\InvoiceRandom;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use LogicException;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\Timesheet;


class GeneratePdfReport
{

    private $dompdf;
    private $options;
    private $security;
    private $entity;
    private $template;
    private $params;
    private $mailerservice;

    const TIMESHEET_SENT = 'Sent';
    const TIMESHEET_VALIDATED = 'Validated';
    const EDIT_TIMESHEET = 'edit';

    const INVOICE_SENT = 'Sent for validation';
    const INVOICE_VALIDATED = 'Validated - sent to client';
    const INVOICE_CLOSED = 'Closed';

    public function __construct(
                                Security $security,
                                EntityManagerInterface $entityManager,
                                ParameterBagInterface $params,
                                MailerService $mailerService
                                )
    {
        $this->entity = $entityManager;
        $this->security = $security;
        $this->dompdf = new Dompdf();
        $this->options = new Options();
        $this->params = $params;
        $this->mailerservice = $mailerService;

        $projectDir = $this->params->get('kernel.project_dir');

        $filesystemLoader = new FilesystemLoader($projectDir.'/templates/');

        $this->template = new Environment($filesystemLoader);

    }

    public function generatePdfReport($template, $filename, $id = null, $invoice = false, $random = false, $amountForUnit = null)
    {
        $option1 = $this->options->setIsRemoteEnabled(true);
        $option2 = $this->options->set('defaultFont', 'titillium');

        $this->dompdf->setOptions($option1);
        $this->dompdf->setOptions($option2);
        $this->dompdf->loadHtml($template);
        $this->dompdf->setPaper('A4', 'portrait');

        $this->dompdf->render();
        $output = $this->dompdf->output();

        if(!$invoice){

            $filepath = $this->params->get('kernel.project_dir').'/report/timesheet/'.$filename;

            file_put_contents($filepath, $output);

            try{

                $this->mailerservice->sendMail('Hello Test','Timesheet', $filepath);

                $this->entity->getRepository(Timesheet::class)
                    ->updateStatus(self::TIMESHEET_SENT, $id, $filepath);

            } catch (\Exception $e){

                return $e->getMessage();
            }

        } elseif($invoice && $random) {

            $filepath = $this->params->get('kernel.project_dir').'/report/invoice/'.$filename;

            file_put_contents($filepath, $output);

            try{

                $this->mailerservice->sendMail('Hello Test','Random Invoice', $filepath);

                $this->entity->getRepository(InvoiceRandom::class)
                            ->updateStatus(self::INVOICE_SENT, $id, $filepath, $amountForUnit);

            } catch (\Exception $e){

                return $e->getMessage();
            }

        } else {

            $filepath = $this->params->get('kernel.project_dir').'/report/invoice/'.$filename;

            file_put_contents($filepath, $output);

            try{

                $this->mailerservice->sendMail('Hello Test','Invoice', $filepath);

                $this->entity->getRepository(Invoice::class)
                    ->updateStatus(self::INVOICE_SENT, $id, $filepath);

            } catch (\Exception $e){

                return $e->getMessage();
            }
        }

    }

    public function reportConstructTimeSheet($month, $edit = null)
    {
        $firstname = $this->security->getToken()->getUser()->getFirstName();
        $lastname = $this->security->getToken()->getUser()->getLastName();
        $user = $this->security->getToken()->getUsername();

        if($edit !==null){

            $query = $this->entity->getRepository(Timesheet::class)
                ->getTimesheetData($user, $month, self::EDIT_TIMESHEET);

        } else {

            $query = $this->entity->getRepository(Timesheet::class)
                ->getTimesheetData($user, $month);
        }

        if(!empty($query)){


            foreach ($query as $key){


            $nbreofdays = $key->getNbreDaysWorked() > 0  ? $key->getNbreDaysWorked() : 0;
            $nbreofbk = $key->getNbrOfBankHolidays() > 0  ? $key->getNbrOfBankHolidays() : 0;
            $nbreofsat = $key->getNbreOfSaturdays() > 0  ? $key->getNbreOfSaturdays() : 0;
            $nbreofsun = $key->getNbreOfSundays() > 0  ? $key->getNbreOfSundays() : 0;
            $id = $key->getId();

            };

        }  else {

                throw new LogicException('The timesheet you are trying to submit contains no data');
        }

        $filename = date('dmy').'_'.uniqid().'_'.$firstname.$lastname.'.pdf';

        $template =  $this->template->render('/timesheet/timesheetTemplatePDF.html.twig' , [

            'month' => $month,
            'name' => $firstname.' '.$lastname,
            'nbreofdays'=>$nbreofdays,
            'nbreofbk' => $nbreofbk,
            'nbreofsat' => $nbreofsat,
            'nbreofsun' => $nbreofsun

        ]);

        $this->generatePdfReport($template, $filename, $id);
    }

    /**
     * Construct to Generate Invoice
     * @param $invoice
     * @param array $timesheetdata
     */
    public function reportConstructInvoice($firstname, $lastname, $invoice, $invoiceid, $random = false, array $timesheetdata = null)
    {
        if($random){

            $filename = date('dmy').'_'.uniqid().'_'.$invoice['description'].'-'.$firstname.$lastname.'.pdf';

            $template = $this->template->render('invoice/invoiceTemplatePDFRandom.html.twig' , [

                'name' => $firstname.' '.$lastname,
                'invoice'=> $invoice
            ]);

            $amountForUnit = $invoice['amountForUnits'] > 0 ? $invoice['amountForUnits'] : null;
            $this->generatePdfReport($template, $filename, $invoiceid, $invoice=true, $random=true, $amountForUnit);

        } else {

            $filename = date('dmy').'_'.uniqid().'_'.$firstname.$lastname.'.pdf';

            $template =  $this->template->render('/invoice/invoiceTemplatePDF.html.twig' , [

                'name' => $firstname.' '.$lastname,
                'invoice'=> $invoice,
                'timesheetdata' => $timesheetdata

            ]);

            $this->generatePdfReport($template, $filename, $invoiceid, $invoice=true);
        }
    }

}