<?php


namespace App\Service;


use App\Entity\TaxeClasses;
use Doctrine\ORM\EntityManagerInterface;

class SimulationCalculator
{
    const PACKAGE_BASIC = 350;
    const PACKAGE_PREMIUM = 425;

    const CAISSE_MALADIE = 0.028;
    const CAISSE_MALADIE_ESPECE = 0.0025;
    const CAISSE_PENSION = 0.08;
    const ASSURANCE_DEPENDANCE = 0.014;
    const SOINS_SANTE = 0.0011;
    const CMU = 0.0107;
    const LUNCH_VOUCHERS = 194.40;

    private $request;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager->getRepository(TaxeClasses::class);
    }

    public function calculationSimulation($request)
    {
        $this->request = $request;

        //data posted from the form
        $salary = $this->request->get('gross-salary');
        $rate = $this->request->get('daily-rate');
        $numberofdays = $this->request->get('days-worked');
        $managementfees = $this->request->get('Package')  === 'basic' ? self::PACKAGE_BASIC  : self::PACKAGE_PREMIUM;
        $taxeclass = $this->request->get('taxe-class');

        $taxeamount  = $this->entityManager->getTaxeAmount($salary, $taxeclass);

        //social fees
        $caissemaladie = $salary * self::CAISSE_MALADIE;
        $caissemaladieespece = $salary * self::CAISSE_MALADIE_ESPECE;
        $caissepension = $salary * self::CAISSE_PENSION;
        $assurancedependance = $salary * self::ASSURANCE_DEPENDANCE;
        $soinsante = $salary * self::SOINS_SANTE;
        $cmu = $salary * self::CMU;
        $lunchvouchers = $this->request->get('lunch-vouchers') === 'lunchv-yes' ? self::LUNCH_VOUCHERS : 0.00;

        //Employer charges/cost
        $sumcharges = $caissemaladie + $caissemaladieespece + $caissepension + $assurancedependance + $soinsante + $cmu;
        $totalemployerscosts = $sumcharges + $salary;

        //Invoice calculation
        $invoice  = $numberofdays * $rate;

        //taxamount calculatioin
        if($salary > 9035){

            $taxrateformatted = number_format((float)$taxeamount[0]['Taxe_rate'], 2, '.', '');

            $taxmount =  number_format((float)$taxeamount[0]['Taxe_amount'], 2, '.', '');

            $portionwithpercentage = $salary - 9035;

            $finaltaxmount = $taxmount + ($portionwithpercentage * $taxrateformatted);

        } else {

            $finaltaxmount = number_format((float)$taxeamount[0]['Taxe_rate'], 2, '.', '');
        }

        $taxableincome = $salary - ($caissemaladie + $caissemaladieespece + $caissepension);

        $netamount = $taxableincome - ($finaltaxmount + $assurancedependance);

        $remainder = $invoice - ($totalemployerscosts + $managementfees + $lunchvouchers);

         return $simulation = [
                                'sum-charges' => $sumcharges,
                                'total-employers-costs' => $totalemployerscosts,
                                'taxable-income' => $taxableincome,
                                'final-taxamount' => $finaltaxmount,
                                'lunch-vouchers' => $lunchvouchers,
                                'invoice-amount' => $invoice,
                                'management-fees' => $managementfees,
                                'caisse-maladie' => $caissemaladie,
                                'caisse-maladie-espece' => $caissemaladieespece,
                                'caisse-pension' => $caissepension,
                                'assurance-dependance' => $assurancedependance,
                                'soinsante' => $soinsante,
                                'cmu' => $cmu,
                                'gross-salary' => $salary,
                                'net-amount' => $netamount,
                                'remainder' => $remainder
                            ];

    }


}