<?php


namespace App\Controller;


use App\Entity\Contact;
use App\Entity\Mail;
use App\Service\ExcelGeneratorReport;
use App\Service\MailerService;
use App\Service\SimulationCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SimulationController extends AbstractController
{


    private $simulationCalculator;
    private $excelReportInstance;
    private $mailerService;

    public function __construct(SimulationCalculator $simulationCalculator, ExcelGeneratorReport $excelReportInstance, MailerService $mailerService)
    {

        $this->simulationCalculator = $simulationCalculator;
        $this->excelReportInstance = $excelReportInstance;
        $this->mailerService =$mailerService;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(path="/simulation", name="simulation")
     */
    public function createSimulation(Request $request)
    {

        $simulation = null;

        if($request->request->count() > 0) {

            $simulation = $this->simulationCalculator->calculationSimulation($request);

            return $this->render('simulation/simulation_simplified_result.html.twig', [

                'simulation' => $simulation
            ]);
        }
        return $this->render('simulation/simulation_simplified.html.twig');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route(path="/simulSend", name="sendSimul")
     */
    public function sendSimulation(Request $request)
    {
        try {
            $firsname = $request->request->get('firstname');
            $lastname = $request->request->get('lastname');
            $email = $request->request->get('email');
            $dataRequest = $request->request->getIterator()->getArrayCopy();
            $pathAfterSaving = $this->excelReportInstance->writeToExcelTemplate($dataRequest);

            /**
             * TODO: Add archiving
             */
            //$x =$this->excelReportInstance->zipfileCreated($pathAfterSaving);

            $this->mailerService->sendSimulation($firsname, $lastname, $email, $pathAfterSaving);

            register_shutdown_function(function () use ($pathAfterSaving){
                if(file_exists($pathAfterSaving)){
                    unlink($pathAfterSaving);
                }
            });

            $this->saveContactFromSimulation($firsname, $lastname, $email);

        } catch (\Exception $exception) {

            $this->addFlash('error', 'Ouups an error occured: '.$exception->getMessage());

            return new JsonResponse([
                                      'error' => 'Ouups an error occured...'
                                    ]);
        }

        $this->addFlash('success', 'Thank you '.$firsname.' '.$lastname.'! An email is being sent to you...');

        return new JsonResponse([
            'success' => 'Thank you '.$firsname.' '.$lastname.'! An email is being sent to you...'
        ]);

    }

    private function saveContactFromSimulation($firstname, $lastname, $email)
    {
        //Persists Contact that requests the detailled simulation
        //Add GDPR stuff as well
        $contact = new Contact();
        $contact->setFirstname($firstname);
        $contact->setLastname($lastname);
        $contact->setContacttype('Prospect');

        $mail = new Mail();
        $mail->setMail($email);
        $mail->setContact($contact);

        if($this->mailerService->checkMail($mail->getMail()) == false){

            $entitymanager = $this->getDoctrine()->getManager();
            $entitymanager->persist($contact);
            $entitymanager->persist($mail);
            $entitymanager->flush();
        }

        $this->mailerService->sendNewContactSimulation($firstname, $lastname, $email);

    }

}