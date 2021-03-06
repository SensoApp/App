<?php

namespace App\Invoice;


use App\Entity\ClientContract;
use App\Entity\Invoice;
use App\Entity\InvoiceCreationData;
use App\Entity\InvoiceRandom;
use App\Entity\Timesheet;
use App\Entity\User;
use App\Service\ExcelGeneratorReport;
use App\Service\GeneratePdfReport;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\DatabaseDriver;
use Exception;

class InvoiceGenerator
{

    public const STATUS = 'To Be Validated';
    public const INVOICE_SENT = 'Sent for validation';
    private $nbreDaysWorked;
    private $nbrOfBankHolidays;
    private $nbreOfSaturdays;
    private $nbreOfSundays;
    private $user;
    private $month;
    private $rate;
    private $invoiceCreationDataId;
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
    private $amounttc;
    private $normalamount;
    private $invoice;
    private $entityManager;
    private $timesheet;
    private $contract;
    private $generatepdf;
    private $vat;
    private $vatamount;
    private $excelGeneratorReport;

    public function __construct(EntityManagerInterface $entityManager, GeneratePdfReport $generatePdfReport, ExcelGeneratorReport $excelGeneratorReport)
    {
        $this->invoice = new Invoice();
        $this->entityManager = $entityManager;
        $this->generatepdf = $generatePdfReport;
        $this->excelGeneratorReport = $excelGeneratorReport;
    }

    /**
     * Retrieves the data passed by the event to the Subscriber and then triggers the invoice calculation
     * @param $id
     */
    public function retrieveDataForInvoice(array $timesheetdata, $manual = false)
    {
        if($manual){

            foreach ($timesheetdata as $item) {

                $this->nbreDaysWorked = $item['days_worked'];
                $this->nbrOfBankHolidays = $item['bank_holidays'];
                $this->nbreOfSaturdays = $item['work_saturdays'];
                $this->nbreOfSundays = $item['work_sundays'];
                $this->user = $item['mail'];
                $this->month = $item['month'];
                $this->rate = $item['rate'];
                $this->invoiceCreationDataId = $item['id'];
                //$this->timesheetid = $item->getId();
                $this->contractid = $item['clientcontractid'];
                $this->extrapercentsatyrday = $item['extrapercentsatyrday'];
                $this->extrapercentsunday = $item['extrapercentsunday'];
                $this->extrapercentbankholidays = $item['extrapercentbankholidays'];
                $this->vat = $item['vat'];

                $this->hydrateInvoiceManual($this->invoiceCalculationUtil(), $timesheetdata);

            }

        } else{

            //Get the timesheet + the contract data related to the user
            foreach ($timesheetdata as $item){

                $this->nbreDaysWorked =  $item->getnbreDaysWorked();
                $this->nbrOfBankHolidays = $item->getnbrOfBankHolidays();
                $this->nbreOfSaturdays = $item->getnbreOfSaturdays();
                $this->nbreOfSundays = $item->getnbreOfSundays();
                $this->user = $item->getUser();
                $this->month = $item->getMonth();
                $this->rate = $item->getContract()->getRate();
                $this->timesheetid = $item->getId();
                $this->contractid = $item->getContract()->getId();
                $this->extrapercentsatyrday = $item->getContract()->getExtrapercentsatyrday();
                $this->extrapercentsunday = $item->getContract()->getExtrapercentsunday();
                $this->extrapercentbankholidays = $item->getContract()->getExtrapercentbankholidays();
                $this->vat = $item->getContract()->getVat();

            }

            $this->hydrateInvoice($this->invoiceCalculationUtil(), $timesheetdata);

        }
    }

    /**
     * It calculates the amount to be invoiced to the client
     * @return float|int
     */
    public function invoiceCalculationUtil() : array
    {
        //test if there is a special percentage for special days, if yes then the below

        //Here we define the actual rate when it is different than the business rate
        $rateSunday = $this->rate * $this->extrapercentsunday;
        $rateSaturday = $this->rate * $this->extrapercentsatyrday;
        $rateBankHolidays = $this->rate * $this->extrapercentbankholidays;

        if( $this->extrapercentsatyrday > 0 || $this->extrapercentsunday > 0 || $this->extrapercentbankholidays > 0){

            if($this->nbrOfBankHolidays > 0 || $this->nbreOfSaturdays > 0 || $this->nbreOfSundays > 0){

                //$this->businessdays =  $this->nbreDaysWorked - ($this->nbrOfBankHolidays + $this->nbreOfSaturdays + $this->nbreOfSundays) ;

                $this->invoicenormaldays = $this->rate * $this->nbreDaysWorked;

                $this->pricebankholidays = $this->nbrOfBankHolidays * $rateBankHolidays;

                $this->pricesaturday = $this->nbreOfSaturdays * $rateSaturday;

                $this->pricesunday = $this->nbreOfSundays * $rateSunday;

                $this->finalamountwithspecialdays = $this->invoicenormaldays + $this->pricebankholidays + $this->pricesaturday + $this->pricesunday;

                $this->vatamount = $this->vat * $this->finalamountwithspecialdays;

                $this->amounttc = $this->finalamountwithspecialdays + $this->vatamount;

                $splitInvoice = [

                        'Bank holidays' => $this->pricebankholidays,
                        'Work on Saturdays' => $this->pricesaturday,
                        'Work on Sundays' => $this->pricesunday,
                        'Business days' => $this->invoicenormaldays,
                        'TotalamountHT' => $this->finalamountwithspecialdays,
                        'rate_Sunday' => $rateSunday,
                        'rate_Saturday' => $rateSaturday,
                        'rate_Bank_holidays' => $rateBankHolidays,
                        'AmountTTC' => $this->amounttc,
                        'VatAmount' => $this->vatamount
                ];

                return $splitInvoice;

            }

        }
        $this->normalamount = $this->rate * $this->nbreDaysWorked;

        $this->vatamount = $this->vat * $this->normalamount;

        $this->amounttc = $this->normalamount + $this->vatamount;

        $normalInvoice = [
                            'Business days' => $this->normalamount,
                            'TotalamountHT' => $this->normalamount,
                            'AmountTTC' => $this->amounttc,
                            'VatAmount' => $this->vatamount
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
            ->findBy(['id' => $this->contractid]);

        $this->timesheet = $this->entityManager
            ->getRepository(Timesheet::class)
            ->findBy(['id' => $this->timesheetid]);

        $invoicenumber = $this->entityManager
            ->getRepository(Invoice::class)
            ->retrieveLastInvoiceId();

        $userForNames  = $this->entityManager
            ->getRepository(User::class)
            ->findBy(['email' => $this->user]);

        $this->invoice->setStatus(self::STATUS);
        $this->invoice->setUser($this->user);
        array_key_exists('Bank holidays', $amount) ? $this->invoice->setBankholidayamount($amount['Bank holidays']) : $this->invoice->setBankholidayamount(0);
        array_key_exists('Work on Saturdays', $amount) ? $this->invoice->setSaturdyamount($amount['Work on Saturdays']) : $this->invoice->setSaturdyamount(0);
        array_key_exists('Work on Sundays', $amount) ? $this->invoice->setSundayamount($amount['Work on Sundays']) : $this->invoice->setSundayamount(0);
        $this->invoice->setBusinessdaysamount($amount['Business days']);
        $this->invoice->setTotalAmount($amount['TotalamountHT']);
        $this->invoice->setAmountttc($amount['AmountTTC']);
        $this->invoice->setVatamount($amount['VatAmount']);
        $this->invoice->setTimesheet($this->timesheet[0]);
        $this->invoice->setContract($this->contract[0]);
        $this->invoice->setInvoicenumber($invoicenumber);
        $this->invoice->setVat($this->vat);
        $this->invoice->setMonth($this->month);

        $invoice = $this->invoice;

        try {

            $this->entityManager->persist($invoice);
            $this->entityManager->flush();

            $this->generatepdf->reportConstructInvoice($userForNames[0]->getFirstname(), $userForNames[0]->getLastname(),$this->invoice,$invoice->getId(),false,  $timesheetdata);

        } catch (Exception $exception) {

            echo dd($exception->getMessage());

        }
    }


    /**
     * It sets the invoice entity with the data retrieved by the manual creation (from the form)
     */
    public function hydrateInvoiceManual($amount, array $timesheetdata)
    {

        $month  = $this->formatDate($this->month);

        $this->contract = $this->entityManager
                               ->getRepository(ClientContract::class)
                               ->findBy(['id'=>$this->contractid]);

        $invoicenumber = $this->entityManager
                              ->getRepository(Invoice::class)
                              ->retrieveLastInvoiceId();

        $invoiceCreationObject = $this->entityManager
                                      ->getRepository(InvoiceCreationData::class)
                                      ->find($this->invoiceCreationDataId);

        $userForNames  = $this->entityManager
                      ->getRepository(User::class)
                      ->findBy(['email' => $this->user]);


        $this->invoice->setStatus(self::STATUS);
        $this->invoice->setUser($this->user);
        array_key_exists('Bank holidays', $amount ) ?  $this->invoice->setBankholidayamount($amount['Bank holidays']) : $this->invoice->setBankholidayamount(0);
        array_key_exists('Work on Saturdays', $amount)  ? $this->invoice->setSaturdyamount($amount['Work on Saturdays']): $this->invoice->setSaturdyamount(0);
        array_key_exists('Work on Sundays', $amount)  ? $this->invoice->setSundayamount($amount['Work on Sundays']) : $this->invoice->setSundayamount(0);
        array_key_exists('rate_Sunday', $amount) ? $this->invoice->setSundayRate($amount['rate_Sunday']) : $this->invoice->setSundayRate(0);
        array_key_exists('rate_Saturday', $amount) ? $this->invoice->setSaturdayRate($amount['rate_Saturday']) : $this->invoice->setSaturdayRate(0);
        array_key_exists('rate_Bank_holidays', $amount) ? $this->invoice->setBankHolidayRate($amount['rate_Bank_holidays']) : $this->invoice->setBankHolidayRate(0);
        $this->invoice->setBusinessdaysamount($amount['Business days']);
        $this->invoice->setTotalAmount($amount['TotalamountHT']);
        $this->invoice->setAmountttc($amount['AmountTTC']);
        $this->invoice->setVatamount($amount['VatAmount']);
        $this->invoice->setContract($this->contract[0]);
        $this->invoice->setInvoiceCreationData($invoiceCreationObject);
        $this->invoice->setInvoicenumber($invoicenumber);
        $this->invoice->setVat($this->vat);
        $this->invoice->setMonth($month);

        $invoice = $this->invoice;

        try{

            $this->entityManager->persist($invoice);
            $this->entityManager->flush();

            //dd($this->invoice);

            $filepath = $this->excelGeneratorReport->writeExcelTemplateInvoice($this->invoice,$userForNames[0]);

            $this->entityManager->getRepository(Invoice::class)
                ->updateStatus(self::INVOICE_SENT, $this->invoice->getId(), $filepath);

            //$this->generatepdf->reportConstructInvoice($userForNames[0]->getFirstname(), $userForNames[0]->getLastname(),$this->invoice,$invoice->getId(),false,  $timesheetdata);

        } catch (Exception $exception){

            echo dd($exception->getMessage());

        }
    }

    /**
     * Calculates the amount and pass it to the pdf generator for Random invoices
     */
    public function randomInvoiceCalculation(array $invoice)
    {
        foreach ($invoice as $invoices){

            //When invoice entered in units
            $amountForUnits  =  $invoices['units'] * $invoices['rate'];
            $amountForUnitsVat = $amountForUnits * $invoices['vat'];
            $amountForUnitsTtc = $amountForUnits + $amountForUnitsVat;

            //When invoice entered in amount
            $amount = $invoices['amount'] !== null ? $invoices['amount'] : 0.00;
            $vatForAmount =  ($invoices['amount'] * $invoices['vat']);
            $ttc = $vatForAmount + $invoices['amount'];

            $amounttc =  $amountForUnits > 0  ? $amountForUnitsTtc : $ttc;
            $vatamount = $amountForUnits > 0  ? $amountForUnitsVat : $vatForAmount;

            //formatted with 2 decimal places
            $vatamount = number_format((float)$vatamount, 2, ',', '');
            $amounttc = number_format((float)$amounttc, 2, ',', '');

            //Get User object
            $userForNames  = $this->entityManager
                ->getRepository(User::class)
                ->findBy(['id' => $invoices['user_id']]);

            //Get related client
            $clientName = $userForNames[0]->getClientContracts()
                                        ->getValues()[0]
                                        ->getClientName()
                                        ->getClientName();

            //Get client Id
            $clientId = $userForNames[0]->getClientContracts()
                ->getValues()[0]
                ->getClientName()
                ->getId();

            $vatNumber = $userForNames[0]->getClientContracts()
                                        ->getValues()[0]
                                        ->getClientName()
                                        ->getVatNumber();

            $invoiceData = [

                'date' => $invoices['created_at'],
                'vat' => $invoices['vat'],
                'rate' =>  $amountForUnits > 0  ? $invoices['rate']  : 0,
                'vatamount' => $vatamount,
                'units' => $amountForUnits > 0  ? $invoices['units'] : 0,
                'description' => $invoices['description'],
                'amount' => $amount,
                'amountForUnits' => $amountForUnits > 0 ? $amountForUnits : 0.00,
                'amounttc' => $amounttc,
                'clientName' => $clientName,
                'clientId' => $clientId,
                'vatNumber' => $vatNumber,
                'invoiceId' => $invoices['invoiceid'],
                'firstname' => $invoices['firstname'],
                'lastname'  => $invoices['lastname']

            ];
        }

        try{

            $filepath = $this->excelGeneratorReport->writeExcelTemplateInvoiceRandom($invoiceData);

            $amountForUnit = $invoiceData['amountForUnits'] > 0 ? $invoiceData['amountForUnits'] : null;
            $this->entityManager->getRepository(InvoiceRandom::class)
                ->updateStatus(self::INVOICE_SENT, $invoices['invoiceid'], $filepath, $amountForUnit);

            //$this->generatepdf->reportConstructInvoice($invoices['firstname'], $invoices['lastname'],$invoiceData, $invoices['invoiceid'],true);

        } catch (\Exception $exception){

            dd($exception->getMessage().'hello '.$exception->getLine().' '.$exception->getTrace());
        }

    }

    /**
     * @param $month
     * @return string
     * Method to change the persisted date into an actual month in three letters e.g. 01 returns Jan
     */
    protected function formatDate($month) : string
    {
        $explodemonth  = explode('-', $month);

        $month = (int)$explodemonth[1];
        $dateObj   = DateTime::createFromFormat('!m', $month);
        $monthName = $dateObj->format('M');

        return $monthName;
    }
}