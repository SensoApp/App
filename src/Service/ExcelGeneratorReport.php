<?php


namespace App\Service;


use App\Entity\ContactEndClient;
use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ExcelGeneratorReport
{


    const PATH_REPORT_DIR = __DIR__.'/../../report/template/';
    const PATH_SIMULATION_TEMPLATE = self::PATH_REPORT_DIR.'Senso_package_simulation_standard_4_1.xlsx';
    const PATH_INVOICE_TEMPLATE = self::PATH_REPORT_DIR.'Invoice_Senso_template.xlsx';
    const REPORT_TYPE_SIMULATION = 'simulation';
    const REPORT_TYPE_INVOICE = 'invoice';

    private $params;
    private $template;
    private $writer;
    private $entityManager;

    public function __construct(ParameterBagInterface $params, EntityManagerInterface $entityManager)
    {
        $this->params = $params;

        $this->entityManager = $entityManager;
    }

    public function reportInstance($report) : Spreadsheet
    {
       $path =  $report === self::REPORT_TYPE_SIMULATION ? self::PATH_SIMULATION_TEMPLATE : self::PATH_INVOICE_TEMPLATE;

       $this->template = IOFactory::load($path);

        return $this->template;
    }


    public function generateStatementInExcel($statement)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'Reference');
        $spreadsheet->getActiveSheet()->setCellValue('B1', 'Operation');
        $spreadsheet->getActiveSheet()->setCellValue('C1', 'Communication');
        $spreadsheet->getActiveSheet()->setCellValue('D1', 'Date');
        $spreadsheet->getActiveSheet()->setCellValue('E1', 'Amount');
        $spreadsheet->getActiveSheet()->setCellValue('F1', 'Currency');

        for($i = 0; $i < count($statement); $i++){

            $linenum = 2+$i;

            $spreadsheet->getActiveSheet()->setCellValue('A'.$linenum, $statement[$i]->getReferencemovement());
            $spreadsheet->getActiveSheet()->setCellValue('B'.$linenum, $statement[$i]->getOperations());
            $spreadsheet->getActiveSheet()->setCellValue('C'.$linenum, $statement[$i]->getCommunication());
            $spreadsheet->getActiveSheet()->setCellValue('D'.$linenum, $statement[$i]->getOperationdate());
            $spreadsheet->getActiveSheet()->setCellValue('E'.$linenum, $statement[$i]->getAmount());
            $spreadsheet->getActiveSheet()->setCellValue('F'.$linenum, 'EUR');
        }

        $styleArray = [
            'font' => [
                'bold' => true,
                'color' => [
                            'argb' => 'FFFFFFFF'
                        ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],
            'borders' => [
                    'top' => [
                        'borderStyle' => Border::BORDER_THIN,
                        ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'rotation' => 90,
                'startColor' => [
                    'argb' => '3364FF',
                ],
                'endColor' => [
                    'argb' => 'FFFFFFFF',
                ],
            ]
        ];

        $spreadsheet->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);

        $this->writer = new Xlsx($spreadsheet);

        $filename = 'Statement'.date('dmy').'_'.uniqid().'.'.'xlsx';

        $temp =  $this->params->get('kernel.project_dir').'/report/temp/'.$filename;

        $this->writer->save($temp);

        return $temp;
    }

    public function writeToExcelTemplateSimulation(array $simulationData) : string
    {
        $wrk  = $this->reportInstance(self::REPORT_TYPE_SIMULATION);

        $worksheet = $wrk->getActiveSheet();

        $worksheet->getCell('B4')->setValue($simulationData['dailyrate'].' €');
        $worksheet->getCell('B5')->setValue($simulationData['numberofdays']);
        $worksheet->getCell('B6')->setValue($simulationData['grosssalary'].' €');
        $worksheet->getCell('B7')->setValue($simulationData['carleasing'].' €');
        $worksheet->getCell('B8')->setValue($simulationData['lunchvouchersemployee'].' €');
        $worksheet->getCell('B9')->setValue($simulationData['taxeclass']);
        $worksheet->getCell('B10')->setValue('-');
        $worksheet->getCell('B11')->setValue($simulationData['managementfees'].' €');
        $worksheet->getCell('B12')->setValue($simulationData['travelExpenses'].' €');
        $worksheet->getCell('C16')->setValue($simulationData['invoiceamount'].' €');
        $worksheet->getCell('C17')->setValue($simulationData['managementfees'].' €');
        $worksheet->getCell('C18')->setValue($simulationData['lunchvouchers'].' €');
        $worksheet->getCell('C19')->setValue($simulationData['grosssalary'].' €');
        $worksheet->getCell('C20')->setValue($simulationData['benefitinkind'].' €');
        $worksheet->getCell('C22')->setValue((int)$simulationData['grossSalaryPluBenefInKind'].' €');
        $worksheet->getCell('C22')->setValue($simulationData['caissemaladie'].' €');
        $worksheet->getCell('C23')->setValue($simulationData['caissemaladieespece'].' €');
        $worksheet->getCell('C24')->setValue($simulationData['caissepension'].' €');
        $worksheet->getCell('C25')->setValue($simulationData['assurancedependance'].' €');
        $worksheet->getCell('C26')->setValue($simulationData['soinsante'].' €');
        $worksheet->getCell('C27')->setValue($simulationData['cmu'].' €');
        $worksheet->getCell('C28')->setValue($simulationData['totalemployerscosts'].' €');
        $worksheet->getCell('C29')->setValue($simulationData['travelExpenses'].' €');
        $worksheet->getCell('C30')->setValue($simulationData['caissemaladie'].' €');
        $worksheet->getCell('C31')->setValue($simulationData['caissemaladieespece'].' €');
        $worksheet->getCell('C32')->setValue($simulationData['caissepension'].' €');
        $worksheet->getCell('C33')->setValue($simulationData['lunchvouchersemployee'].' €');
        $worksheet->getCell('C34')->setValue($simulationData['taxableincome'].' €');
        $worksheet->getCell('C35')->setValue($simulationData['finaltaxamount'].' €');
        $worksheet->getCell('C36')->setValue($simulationData['assurancedependance'].' €');
        $worksheet->getCell('C37')->setValue($simulationData['benefitinkind'].' €');
        $worksheet->getCell('C38')->setValue($simulationData['netamount'].' €');
        $worksheet->getCell('C41')->setValue($simulationData['remainder'].' €');

        try {

            $writer = IOFactory::createWriter($this->template, 'Xlsx');
            $filename = 'Simulation'.date('dmy').'_'.uniqid().'.'.'xlsx';

            $tempxls =  $this->params->get('kernel.project_dir').'/report/temp/'.$filename;
            $writer->save($tempxls);


        } catch (Exception $e) {

            return $e->getMessage().'Excel...issue';
        }

        return $tempxls;
    }

    public function writeExcelTemplateInvoice(Invoice $invoice, UserInterface $user) : string
    {
        $clientId =  $invoice->getContract()->getClientName()->getId();

        //retrieve address with custom query (many to many so through the joint table)
        $clientAddress = $this->entityManager
                              ->getRepository(ContactEndClient::class)
                              ->findRelatedAddress($clientId);

        $wrkInstance = $this->reportInstance(self::REPORT_TYPE_INVOICE);
        $worksheet = $wrkInstance->getActiveSheet();

        $worksheet->getCell('A8')->setValue($invoice->getContract()->getClientName()->getClientName());
        if(!empty($clientAddress)){

            $worksheet->getCell('A9')->setValue($clientAddress['street']);
            $worksheet->getCell('A10')->setValue($clientAddress['postcode'].' '.$clientAddress['city']);

        } else {

            $worksheet->getCell('A9')->setValue('');
            $worksheet->getCell('A10')->setValue('');
        }
        $worksheet->getCell('B11')->setValue($invoice->getContract()->getClientName()->getVatNumber());
        $worksheet->getCell('F8')->setValue($invoice->getInvoicenumber());
        $worksheet->getCell('H8')->setValue($invoice->getDate()->format('d/m/Y'));
        //Description
        $worksheet->getCell('A16')->setValue(
                                                        'Consulting services from '.
                                                                $user->getFirstname().' '.
                                                                $user->getLastname().' for the month of '.$invoice->getMonth()
                                                        );
        //units
        $worksheet->getCell('F16')->setValue($invoice->getInvoiceCreationData()->getDaysWorked());
        //rate
        $worksheet->getCell('G16')->setValue($invoice->getContract()->getRate());
        //Grossamount
        $worksheet->getCell('H16')->setValue($invoice->getBusinessdaysamount());

        //Check whether there is a speacial day

        if($invoice->getBankholidayamount() > 0){

            //$bankHolidaysRate = $invoice->getContract()->getRate() * $invoice->getContract()->getExtrapercentbankholidays();
            //Description
            $worksheet->getCell('A17')->setValue('Bank Holidays');
            //units
            $worksheet->getCell('F17')->setValue($invoice->getInvoiceCreationData()->getBankHolidays());
            //rate
            $worksheet->getCell('G17')->setValue($invoice->getBankHolidayRate());
            //Grossamount
            $worksheet->getCell('H17')->setValue($invoice->getBankholidayamount());

        }

        if($invoice->getSaturdyamount() > 0){

            //$bankHolidaysRate = $invoice->getContract()->getRate() * $invoice->getContract()->getExtrapercentsatyrday();
            //Description
            $worksheet->getCell('A18')->setValue('Saturday work');
            //units
            $worksheet->getCell('F18')->setValue($invoice->getInvoiceCreationData()->getWorkSaturdays());
            //rate
            $worksheet->getCell('G18')->setValue($invoice->getSaturdayRate());
            //Grossamount
            $worksheet->getCell('H18')->setValue($invoice->getSaturdyamount());

        }

        if($invoice->getSundayamount() > 0){

            //$bankHolidaysRate = $invoice->getContract()->getRate() * $invoice->getContract()->getExtrapercentsunday();
            //Description
            $worksheet->getCell('A19')->setValue('Sunday work');
            //units
            $worksheet->getCell('F19')->setValue($invoice->getInvoiceCreationData()->getWorkSundays());
            //rate
            $worksheet->getCell('G19')->setValue($invoice->getSundayRate());
            //Grossamount
            $worksheet->getCell('H19')->setValue($invoice->getSundayamount());

        }

        $worksheet->getCell('H30')->setValue($invoice->getTotalAmount());
        $worksheet->getCell('H31')->setValue($invoice->getVat());
        $worksheet->getCell('H32')->setValue($invoice->getVatamount());
        $worksheet->getCell('H33')->setValue($invoice->getAmountttc());

        try {

            $writer = IOFactory::createWriter($this->template, 'Xlsx');
            $filename = 'Invoice'.'_'.$invoice->getMonth().'_'.$user->getFirstname().'_'.$user->getFirstname().uniqid().'.'.'xlsx';

            $tempxls =  $this->params->get('kernel.project_dir').'/report/temp/'.$filename;

            $writer->save($tempxls);

        } catch (Exception $e) {

            return $e->getMessage().'Excel...issue';
        }

        return $tempxls;
    }

    public function writeExcelTemplateInvoiceRandom(array $invoice) : string
    {


        //retrieve address with custom query (many to many so through the joint table)
        $clientAddress = $this->entityManager
            ->getRepository(ContactEndClient::class)
            ->findRelatedAddress($invoice['clientId']);

        $wrkInstance = $this->reportInstance(self::REPORT_TYPE_INVOICE);
        $worksheet = $wrkInstance->getActiveSheet();

        //Formatting the date using the DateTime Object
        $date = new \DateTime($invoice['date']);

        $worksheet->getCell('A8')->setValue($invoice['clientName']);

        if(!empty($clientAddress)){

            $worksheet->getCell('A9')->setValue($clientAddress['street']);
            $worksheet->getCell('A10')->setValue($clientAddress['postcode'].' '.$clientAddress['city']);

        } else {
            $worksheet->getCell('A9')->setValue('');
            $worksheet->getCell('A10')->setValue('');
        }
        $worksheet->getCell('B11')->setValue($invoice['vatNumber']);
        $worksheet->getCell('F8')->setValue('Rand_'.$invoice['invoiceId']);
        $worksheet->getCell('H8')->setValue($date->format('d-m-y'));
        //Description
        $worksheet->getCell('A16')->setValue($invoice['description']);
        //units
        $worksheet->getCell('F16')->setValue($invoice['units'] > 0 ? $invoice['units'] :  'N/A');
        //rate
        $worksheet->getCell('G16')->setValue($invoice['rate']  > 0  ? $invoice['rate']  : 'N/A');
        //Grossamount
        $worksheet->getCell('H16')->setValue($invoice['amountForUnits'] > 0 ? $invoice['amountForUnits'] : $invoice['amount']);

        $worksheet->getCell('H30')->setValue($invoice['amountForUnits'] > 0 ? $invoice['amountForUnits'] : $invoice['amount']);
        $worksheet->getCell('H31')->setValue($invoice['vat']);
        $worksheet->getCell('H32')->setValue($invoice['vatamount']);
        $worksheet->getCell('H33')->setValue($invoice['amounttc']);

        $worksheet->getColumnDimension('H')->setAutoSize(true);
        $worksheet->getColumnDimension('G')->setAutoSize(true);

        try {

            $writer = IOFactory::createWriter($this->template, 'Xlsx');
            $filename = 'Invoice_rand_'.$invoice['firstname'].'_'.$invoice['lastname'].uniqid().'.'.'xlsx';

            $tempxls =  $this->params->get('kernel.project_dir').'/report/temp/'.$filename;
            $writer->save($tempxls);


        } catch (Exception $e) {

            return $e->getMessage().'Excel...issue';
        }

        return $tempxls;
    }
    /*public function zipFileCreated($filename) : string
    {
        $fileZipped = 'Simulation.zip';

        try{

            $zip  = new \ZipArchive();
           if( $zip->open($fileZipped, \ZipArchive::CREATE===TRUE))
           {
               $zip->addFile($filename);
               $zip->close();

               return 'ok';

           } else {

               return 'did not work';
           }

        } catch (\Exception $e ){

            return $e->getMessage();
        }

    }*/

    /*public function convertToPDf($templateToConvert) : void
    {
        try {

            dd($templateToConvert);

            IOFactory::registerWriter('PDF', Dompdf::class);
            $pdfwriter = IOFactory::createWriter($templateToConvert, 'PDF');
            $pdfwriter->setOrientation('L');
            $pdfwriter->setPreCalculateFormulas(false);

        } catch (Exception $e) {

            dd($e->getMessage().'..pdf...issue');
        }

        $temppdf =  $this->params->get('kernel.project_dir').'/report/temp/test.pdf';
        $pdfwriter->save($temppdf);
      //  $filenamePrdf = 'Simulation'.date('dmy').'_'.uniqid().'.'.'pdf';
        //$writerPdf->save($temppdf);
    }*/
}