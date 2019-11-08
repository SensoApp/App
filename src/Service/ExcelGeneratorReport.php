<?php


namespace App\Service;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ExcelGeneratorReport
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {

        $this->params = $params;
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

        $writer = new Xls($spreadsheet);

        $filename = 'Statement'.date('dmy').'_'.uniqid().'.'.'xls';

        $temp =  $this->params->get('kernel.project_dir').'/report/temp/'.$filename;

        $writer->save($temp);

        return $temp;
    }
}