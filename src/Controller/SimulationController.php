<?php


namespace App\Controller;


use App\Service\ExcelGeneratorReport;
use App\Service\MailerService;
use App\Service\SimulationCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        /**
         * TODO: Save email and contact details in DB
         */
        $simulation = null;

        if($request->request->count() > 0) {

            $simulation = $this->simulationCalculator->calculationSimulation($request);

            if(!empty($request->request->get('detailed'))){

                try {
                    $firsname = $request->request->get('firstname');
                    $lastname = $request->request->get('lastname');
                    $email = $request->request->get('email');

                    $pathAfterSaving = $this->excelReportInstance->writeToExcelTemplate($simulation);
                    $this->mailerService->sendSimulation($firsname, $lastname, $email, $pathAfterSaving);

                    register_shutdown_function(function () use ($pathAfterSaving){
                        if(file_exists($pathAfterSaving)){
                            unlink($pathAfterSaving);
                        }
                    });

                } catch (\Exception $exception) {

                    $this->addFlash('error', 'Ouups the follwoing error occured '. $exception->getMessage());

                    return $this->redirectToRoute('simulation');

                }
                $this->addFlash('success', 'Thank you '.$firsname.' '.$lastname.'! An email is being sent to you...');

                return $this->redirectToRoute('simulation');
            }
        }
        return $this->render('simulation/simulation_simplified.html.twig', [

            'simulation' => $simulation
        ]);
    }

}