<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-07-29
 * Time: 18:37
 */

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
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

    public function generatePdfReport($template, $filename)
    {
        $option1 = $this->options->setIsRemoteEnabled(true);
        $option2 = $this->options->set('defaultFont', 'titillium');

        $this->dompdf->setOptions($option1);
        $this->dompdf->setOptions($option2);
        $this->dompdf->loadHtml($template);
        $this->dompdf->setPaper('A4', 'portrait');

        $this->dompdf->render();
        $output = $this->dompdf->output();

        $filepath = $this->params->get('kernel.project_dir').'/report/timesheet/'.$filename;

        file_put_contents($filepath, $output);

        /////////TO DO///////
        /// HOOK THIS TO A SERVICE///////
        //change email address recipient///
        $this->mailerservice->sendMail('Hello Test','Timesheet', $filepath);

        ////// THEN UPDATE STATUS OF RELATED TIMESHEET IN THE DATABASE///////


    }

    public function reportConstructTimeSheet($month)
    {
        $firstname = $this->security->getToken()->getUser()->getFirstName();
        $lastname = $this->security->getToken()->getUser()->getLastName();
        $user = $this->security->getToken()->getUsername();

        $query = $this->entity->getRepository(Timesheet::class)->getTimesheetData($user, $month);

        foreach ($query as $key){

            $nbreofdays = $key->getNbreDaysWorked() > 0  ? $key->getNbreDaysWorked() : 0;
            $nbreofbk = $key->getNbrOfBankHolidays() > 0  ? $key->getNbrOfBankHolidays() : 0;
            $nbreofsat = $key->getNbreOfSaturdays() > 0  ? $key->getNbreOfSaturdays() : 0;
            $nbreofsun = $key->getNbreOfSundays() > 0  ? $key->getNbreOfSundays() : 0;
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

        $this->generatePdfReport($template, $filename);
    }
}