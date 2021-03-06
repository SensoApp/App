<?php


namespace App\Service;


use App\Entity\TaxeClasses;
use Doctrine\ORM\EntityManagerInterface;

class SimulationCalculator
{
    const PACKAGE_BASIC = 350;

    const CAISSE_MALADIE = 0.028;
    const CAISSE_MALADIE_ESPECE = 0.0025;
    const CAISSE_PENSION = 0.08;
    const ASSURANCE_DEPENDANCE = 0.014;
    const SOINS_SANTE = 0.0011;
    const CMU = 0.0107;
    const LUNCH_VOUCHERS = 194.40;
    const FIXE_DEDUCTION_DEPENDANCE = 535.50;
    const LUNCH_VOUCHERS_EMPLOYEE = 50.40;
    const DAYS_WORKED = 18;

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
        $numberofdays = self::DAYS_WORKED;
        $managementfees = self::PACKAGE_BASIC;
        $specifictaxebool = $this->request->get('taxe-class') === 'specific-tax-rate' ? true : false;
        $taxeclass = $this->request->get('taxe-class') === 'specific-tax-rate' ? $this->request->get('specificrate') : $this->request->get('taxe-class');
        $lunchvouchers =  self::LUNCH_VOUCHERS;
        $lunchVouchersEmployee = self::LUNCH_VOUCHERS_EMPLOYEE;
        //Car leasing a rajouter au taxable amount
        $benefitinkind = $this->request->get('carLeasingAmount') === '' ?  0.00 : $this->request->get('carLeasingAmount');
        $travelExpenses = $this->request->get('travelExpenses') === '' ?  0.00 : $this->request->get('travelExpenses');

        //gross Salary  +  benefit in kind
        $grossSalaryPluBenefInKind = $salary + $benefitinkind;

        //social fees
        //formatted with 2 decimals only
        $caissemaladie = $grossSalaryPluBenefInKind * self::CAISSE_MALADIE;
        $caissemaladieespece = $salary * self::CAISSE_MALADIE_ESPECE;
        $caissepension = $grossSalaryPluBenefInKind * self::CAISSE_PENSION;
        $assurancedependance = $grossSalaryPluBenefInKind * self::ASSURANCE_DEPENDANCE;
        $soinsante = $grossSalaryPluBenefInKind * self::SOINS_SANTE;
        $cmu = $salary * self::CMU;

        //Employer charges/cost
        $sumcharges = $caissemaladie + $caissemaladieespece + $caissepension + $assurancedependance + $soinsante + $cmu;
        $sumchargesEmployees = $caissemaladie + $caissemaladieespece + $caissepension;
        $totalemployerscosts = (float)$sumcharges + $salary;

        //Invoice calculation
        $invoice  = $numberofdays * $rate;

        //Taxable income for the employees after deduction of the social taxes and fees
        $taxesToAdd = $lunchVouchersEmployee + $benefitinkind;
        $taxesMinusTravelExpenses = (float)$salary - $travelExpenses;
        $taxableincome = $taxesMinusTravelExpenses + $taxesToAdd -  ($sumchargesEmployees);
        // if yes add + Lunch vouchers amount percentage du montant 50,40 if no null => done
        // + add amount of car leasing (percentage of the total car price e.g. 1,8 etc...) => done
        // - frais de deplacement (rajouter champ) => done

        //Taxe amount retrieved from the database
        $finaltaxmount = $this->calculTaxAmount($taxeclass, (int)$taxableincome, $specifictaxebool);

        //Employee dependance insurance amount calculation
        $assurancedependanceemployee = ((float)$grossSalaryPluBenefInKind - self::FIXE_DEDUCTION_DEPENDANCE)  * self::ASSURANCE_DEPENDANCE;

        //Net salary calculation
        $amountWithoutTravelFees = (float)$taxableincome - ($finaltaxmount + $assurancedependanceemployee   + $taxesToAdd /*???*/);
        $netamount = $amountWithoutTravelFees + $travelExpenses;
        // - benefice in kind ?? => added car leasing in case
        // - 50,40 lunch vouchers => done
        // + Frais de deplacement => done

        //Remainder on the bank account for the consultant
        $remainder = (float)$invoice - ($totalemployerscosts + $managementfees + $lunchvouchers);

         return $simulation = [
                                'dailyrate' => $rate,
                                'numberofdays' => $numberofdays,
                                'taxeclass' => $taxeclass,
                                'sumcharges' => number_format($sumcharges, 2, '.', ','),
                                'travelExpenses' => number_format($travelExpenses, 2, '.', ','),
                                'totalemployerscosts' =>  number_format($totalemployerscosts, 2, '.', ','),
                                'taxableincome' =>  number_format($taxableincome, 2, '.', ','),
                                'finaltaxamount' =>  number_format($finaltaxmount, 2, '.', ','),
                                'lunchvouchers' =>  number_format($lunchvouchers, 2, '.', ','),
                                'invoiceamount' =>  number_format($invoice, 2, '.', ','),
                                'managementfees' =>  number_format($managementfees, 2, '.', ','),
                                'caissemaladie' =>  number_format($caissemaladie, 2, '.', ','),
                                'caissemaladieespece' =>  number_format($caissemaladieespece, 2, '.', ','),
                                'caissepension' =>  number_format($caissepension, 2, '.', ','),
                                'assurancedependance' =>  number_format($assurancedependance, 2, '.', ','),
                                'soinsante' =>  number_format($soinsante, 2, '.', ','),
                                'cmu' =>  number_format($cmu, 2, '.', ','),
                                'grosssalary' =>  number_format($salary, 2, '.', ','),
                                'grossSalaryPluBenefInKind' => number_format($grossSalaryPluBenefInKind, 2, '.', ','),
                                'netamount' =>  number_format($netamount, 2, '.', ','),
                                'remainder' =>  number_format($remainder, 2, '.', ','),
                                'assurancedependanceemployee' =>  number_format($assurancedependanceemployee, 2, '.', ','),
                                'lunchvouchersemployee' => number_format($lunchVouchersEmployee, 2, '.', ','),
                                'carleasing' => number_format($benefitinkind, 2, '.', ','),
                                'benefitinkind' => number_format($benefitinkind, 2, '.', ',')
                            ];

    }

    public function calculTaxAmount($taxeclass, $salary, bool $specifictaxe = false)
    {
        switch ($salary){

            case $specifictaxe :

                $taxeclasscalculated = ($taxeclass * $salary)/100;

                break;

            //Normal tranches rate/amount of taxe to deduct
            case $salary <= 9039.99 :

                $s = $salary <= 9039.99 ? true : false;

                $taxefromdb  = $this->entityManager->getTaxeAmount($salary, $taxeclass);

                $taxeclasscalculated = (float)$taxefromdb[0]['Taxe_rate'];

                break;

            //first tranche high salary
            case $salary >= 9040 && $salary <= 12589.99 && $taxeclass !== 'class2':

                if($taxeclass === 'class1'){

                    $taxeclasscalculated = 0.40 * $salary - 983.4275;

                } else {

                    $taxeclasscalculated = 0.40 * $salary - 1042.4000;
                }

                break;

            case $salary >= 9040 && $salary <= 16754.99 && $taxeclass === 'class2':

                $taxeclasscalculated = 0.39 * $salary - 1765.335;

                break;

            //Second tranche high salary
            case $salary >= 12590 && $salary <= 16754.99 && $taxeclass !== 'class2':

                if($taxeclass === 'class1'){

                    $taxeclasscalculated = 0.41 * $salary - 1109.2775;

                } else {

                    $taxeclasscalculated = 0.41 * $salary - 1168.25;
                }

                break;

            case $salary >= 16755 && $salary <= 25089.99 && $taxeclass === 'class2':

                $taxeclasscalculated = 0.40 * $salary - 1932.855;

                break;

            //Third tranche high salary
            case $salary >= 16755 &&  $taxeclass !== 'class2':

                if($taxeclass === 'class1'){

                    $taxeclasscalculated = 0.42 * $salary - 1276.7975;

                } else {

                    $taxeclasscalculated = 0.42 * $salary - 1335.77;
                }

                break;

            case $salary >= 25090 && $salary <= 33419.99 && $taxeclass === 'class2':

                $taxeclasscalculated = 0.41 * $salary - 2183.705;

                break;

        }

        return $taxeclasscalculated;
    }


}