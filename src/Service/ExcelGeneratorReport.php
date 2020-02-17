<?php


namespace App\Service;


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ExcelGeneratorReport
{

    private $params;
    private $template;
    private $pathTemplate;
    private $writer;

    public function __construct(ParameterBagInterface $params)
    {
        $this->pathTemplate = __DIR__ . '/../../report/template/Senso_package_simulation_standard_2.xls';
        $this->params = $params;
        $this->template = IOFactory::load($this->pathTemplate);

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

        $this->writer = new Xls($spreadsheet);

        $filename = 'Statement'.date('dmy').'_'.uniqid().'.'.'xls';

        $temp =  $this->params->get('kernel.project_dir').'/report/temp/'.$filename;

        $this->writer->save($temp);

        return $temp;
    }

    public function writeToExcelTemplate(array $simulationData) : string
    {
        $worksheet = $this->template->getActiveSheet();

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

            $writer = IOFactory::createWriter($this->template, 'Xls');
            $filename = 'Simulation'.date('dmy').'_'.uniqid().'.'.'xls';
            $tempxls =  $this->params->get('kernel.project_dir').'/report/temp/'.$filename;
            $writer->save($tempxls);

        } catch (Exception $e) {

            return $e->getMessage().'Excel...issue';
        }

        return $tempxls;
    }

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