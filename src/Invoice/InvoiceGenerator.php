<?php

namespace App\Invoice;


use App\Entity\ClientContract;
use App\Entity\Invoice;
use App\Entity\Timesheet;
use App\Service\GeneratePdfReport;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class InvoiceGenerator
{

    public const STATUS = 'To Be Validated';
    private $nbreDaysWorked;
    private $nbrOfBankHolidays;
    private $nbreOfSaturdays;
    private $nbreOfSundays;
    private $user;
    private $month;
    private $rate;
    private $timesheetid;
    private $contractid;
    private $extrapercentsatyrday;
    private $extrapercentsunday;
    private $extrapercentbankholidays;
    private $pricebankholidays;
    private $pricesaturday;
    private $pricesunday;
    private $businessdays;
    private $invoicenormaldays;
    private $finalamountwithspecialdays;
    private $normalamount;
    private $invoice;
    private $entityManager;
    private $timesheet;
    private $contract;
    private $generatepdf;

    public function __construct(EntityManagerInterface $entityManager, GeneratePdfReport $generatePdfReport)
    {
        $this->invoice = new Invoice();
        $this->entityManager = $entityManager;
        $this->generatepdf = $generatePdfReport;
    }

    /**
     * Retrieves the data passed by the event to the Subscriber and then triggers the invoice calculation
     * @param $id
     */
    public function retrieveDataForInvoice(array $timesheetdata)
    {
        //Get the timesheet + the contract data related to the user

        foreach ($timesheetdata as $item){

           $this->nbreDaysWorked =  $item['nbreDaysWorked'];
           $this->nbrOfBankHolidays = $item['nbrOfBankHolidays'];
           $this->nbreOfSaturdays = $item['nbreOfSaturdays'];
           $this->nbreOfSundays = $item['nbreOfSundays'];
           $this->user = $item['user'];
           $this->month = $item['month'];
           $this->rate = $item['rate'];
           $this->timesheetid = $item['timesheetid'];
           $this->contractid = $item['contractid'];
           $this->extrapercentsatyrday = $item['extrapercentsatyrday'];
           $this->extrapercentsunday = $item['extrapercentsunday'];
           $this->extrapercentbankholidays = $item['extrapercentbankholidays'];

        }

        $this->hydrateInvoice($this->invoiceCalculationUtil(),$timesheetdata );

    }

    /**
     * It calculates the amount to be invoiced to the client
     * @return float|int
     */
    public function invoiceCalculationUtil() : array
    {
        //test if there is a special percentage for special days, if yes then the below

        if( $this->extrapercentsatyrday > 0 || $this->extrapercentsunday > 0 || $this->extrapercentbankholidays > 0){

            if($this->nbrOfBankHolidays > 0 || $this->nbreOfSaturdays > 0 || $this->nbreOfSundays > 0){

                $this->businessdays =  $this->nbreDaysWorked - ($this->nbrOfBankHolidays + $this->nbreOfSaturdays + $this->nbreOfSundays) ;

                $this->invoicenormaldays = $this->rate * $this->businessdays;

                $this->pricebankholidays = $this->nbrOfBankHolidays * ($this->rate * $this->extrapercentbankholidays);

                $this->pricesaturday = $this->nbreOfSaturdays * ($this->rate * $this->extrapercentsatyrday);

                $this->pricesunday = $this->nbreOfSundays * ($this->rate * $this->extrapercentsunday);

                $this->finalamountwithspecialdays = $this->invoicenormaldays + $this->pricebankholidays + $this->pricesaturday + $this->pricesunday;

                $splitInvoice = [

                        'Bank holidays' => $this->pricebankholidays,
                        'Work on Saturdays' => $this->pricesaturday,
                        'Work on Sundays' => $this->pricesunday,
                        'Business days' => $this->invoicenormaldays,
                        'Total amount' => $this->finalamountwithspecialdays
                ];

                return $splitInvoice;

            }

        }
        $this->normalamount = $this->rate * $this->nbreDaysWorked;

        $normalInvoice = [
                            'Business days' => $this->normalamount,
                            'Total amount' => $this->normalamount
                         ];

        return $normalInvoice ;

    }

    /**
     * It sets the invoice entity with the data retrieved after the Timehseet has been validated
     */
    public function hydrateInvoice($amount, array $timesheetdata)
    {

        $this->contract = $this->entityManager
                               ->getRepository(ClientContract::class)
                               ->findBy(['id'=>$this->contractid]);

        $this->timesheet = $this->entityManager
                                ->getRepository(Timesheet::class)
                                ->findBy(['id'=>$this->timesheetid]);

        $invoicenumber = $this->entityManager
                   ->getRepository(Invoice::class)
                   ->retrieveLastInvoiceId();

        $this->invoice->setStatus(self::STATUS);
        $this->invoice->setUser($this->user);
        array_key_exists('Bank holidays', $amount ) ?  $this->invoice->setBankholidayamount($amount['Bank holidays']) : $this->invoice->setBankholidayamount(0);
        array_key_exists('Work on Saturdays', $amount)  ? $this->invoice->setSaturdyamount($amount['Work on Saturdays']): $this->invoice->setSaturdyamount(0);
        array_key_exists('Work on Sundays', $amount)  ? $this->invoice->setSundayamount($amount['Work on Sundays']) : $this->invoice->setSundayamount(0);
        $this->invoice->setBusinessdaysamount($amount['Business days']);
        $this->invoice->setTotalAmount($amount['Total amount']);
        $this->invoice->setTimesheet($this->timesheet[0]);
        $this->invoice->setContract($this->contract[0]);
        $this->invoice->setInvoicenumber($invoicenumber);
        $this->invoice->setVat(0);
        $this->invoice->setMonth($this->month);

        $invoice = $this->invoice;

        try{

            $this->entityManager->persist($invoice);
            $this->entityManager->flush();

            $this->generatepdf->reportConstructInvoice($this->invoice,$invoice->getId(), $timesheetdata);

        } catch (Exception $exception){

             $exception->getMessage();

        }

    }

    /**
     * TODO Retrieve rate from the contract of the related User and if there is a VAT to apply
     */

}