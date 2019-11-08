<?php


namespace App\Controller;


use App\Service\SimulationCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SimulationController extends AbstractController
{


    private $simulationCalculator;

    public function __construct(SimulationCalculator $simulationCalculator)
    {

        $this->simulationCalculator = $simulationCalculator;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(path="/user/simulation", name="simulation")
     */
    public function createSimulation(Request $request)
    {
        $simulation = null;

        if($request->request->count() > 0){

            $simulation = $this->simulationCalculator->calculationSimulation($request);
        }

        return $this->render('simulation/simulation.html.twig', [

            'simulation' => $simulation
        ]);
    }

}