<?php

namespace App\Controllers\Report;

use App\Controllers\BaseController;
use App\Models\Master\Client;
use App\Models\Master\M_mt_salary;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Report_salary extends BaseController
{
    protected $salaryModel;
    protected $clientModel;

    public function __construct()
    {
        $this->salaryModel = new M_mt_salary();
        $this->clientModel = new Client();
    }
    public function index()
    {
        $session = session();
        $clients = $session->get('userClients');
        $data['clients'] = $clients;
        $data['actView'] = 'Report/salary_report';
        return view('home', $data);
    }

    public function getEmployeeListByClient($clientName, $employeeType)
    {
        $data = $this->salaryModel->getEmployeeListByClient($clientName, $employeeType);
        return $this->response->setJSON([
            'status' => true,
            'data'   => $data
        ]);
    }

    public function printSalary($clientName, $employeeType)
    {
        $data = $this->salaryModel->getEmployeeListByClient($clientName, $employeeType);
        $spreadsheet = new Spreadsheet();

        $clientFullName = "AGINCOURT MARTABE RESOURCES";
        if ($clientName != "All") {
            $clientFullName = $this->clientModel->clientDisplay($clientName);
            $client = $this->clientModel->clientDisplay($clientName);
            $clientFullName = mb_strtoupper($client['clientDisplay'], 'UTF-8');
        }

        $spreadsheet->getProperties()->setCreator('Maurice - Web - Android')
            ->setLastModifiedBy('Maurice - Web - Android')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');

        $boldFont = [
            'font' => [
                'bold' => true
            ],
        ];

        $totalStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => '0000FF'],
            ],
        ];

        $allBorderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        $outlineBorderStyle = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        $topBorderStyle = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        $bottomBorderStyle = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        $center = array();
        $center['alignment'] = array();
        $center['alignment']['horizontal'] = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
        $center['alignment']['vertical'] = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;

        $right = array();
        $right['alignment'] = array();
        $right['alignment']['horizontal'] = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
        $right['alignment']['vertical'] = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;

        $left = array();
        $left['alignment'] = array();
        $left['alignment']['horizontal'] = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
        $left['alignment']['vertical'] = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;

        $lastColumn = "I";


        $spreadsheet->getActiveSheet()->mergeCells("A1:" . $lastColumn . "1");
        $spreadsheet->getActiveSheet()->getStyle("A1:" . $lastColumn . "1")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->mergeCells("A2:" . $lastColumn . "2");
        $spreadsheet->getActiveSheet()->getStyle("A2:" . $lastColumn . "2")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true)->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle("A2:D2")->getFont()->setBold(true)->setSize(16);

        /* SET HEADER BG COLOR*/
        $spreadsheet->getActiveSheet()->getStyle("A4:" . $lastColumn . "4")
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B');

        /* START INVOICE TITLE */
        $spreadsheet->getActiveSheet()->getStyle("A4:" . $lastColumn . "4")->getFont()->setSize(12);

        $spreadsheet->getActiveSheet()->getStyle("A4:A4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("B4:B4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("C4:C4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("D4:D4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("E4:E4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("F4:F4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("G4:G4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("H4:H4")->applyFromArray($allBorderStyle);


        $spreadsheet->getActiveSheet()->getStyle("A4:A4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("B4:B4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("C4:C4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("D4:D4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("F4:F4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("G4:G4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("H4:H4")->applyFromArray($center);

        $spreadsheet->getActiveSheet()->getStyle("B4:B4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("C4:C4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("F4:F4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("G4:G4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("H4:H4")->getAlignment()->setWrapText(true);

        $spreadsheet->getActiveSheet()->getStyle("A4:" . $lastColumn . "4")->applyFromArray($outlineBorderStyle);

        $spreadsheet->getActiveSheet()->mergeCells("A4:A4");
        $spreadsheet->getActiveSheet()->mergeCells("B4:B4");
        $spreadsheet->getActiveSheet()->mergeCells("C4:C4");
        $spreadsheet->getActiveSheet()->mergeCells("D4:D4");
        $spreadsheet->getActiveSheet()->mergeCells("F4:F4");
        $spreadsheet->getActiveSheet()->mergeCells("G4:G4");
        $spreadsheet->getActiveSheet()->mergeCells("H4:H4");

        $total = 0;
        $rounded = 0;
        $gaji = 0;
        $dana = 0;


        $spreadsheet->getActiveSheet()
            ->setCellValue('A4', 'NO')
            ->setCellValue('B4', 'BIODATA ID')
            ->setCellValue('C4', 'CLIENT NAME')
            ->setCellValue('D4', 'BANK NAME')
            ->setCellValue('E4', 'NOMER REKENING')
            ->setCellValue('F4', 'NAMA REKENING')
            ->setCellValue('G4', 'MONTHLY')
            ->setCellValue('H4', 'DAILY')
            ->setCellValue('I4', 'STATUS PAYROLL')
        ;

        /* START TOTAL WORK HOUR */

        $rowIdx = 5;
        $startIdx = $rowIdx;
        $rowNo = 0;

        foreach ($data as $row) {
            $rowIdx++;
            $rowNo++;

            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $rowIdx, $rowNo)
                // ->setCellValue('B'.$rowIdx, $row['salary_id']) 
                ->setCellValue('B' . $rowIdx, $row['biodata_id'])
                ->setCellValue('C' . $rowIdx, $row['company_name'])
                ->setCellValue('D' . $rowIdx, $row['bank_name'])
                ->setCellValue('E' . $rowIdx, $row['account_no'])
                ->setCellValueExplicit(
                    'E' . $rowIdx,
                    $row['account_no'],
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                )

                ->setCellValue('F' . $rowIdx, $row['account_name'])
                ->setCellValue('G' . $rowIdx, $row['monthly'])
                ->setCellValue('H' . $rowIdx, $row['daily'])
                ->setCellValue('I' . $rowIdx, $row['status_payroll'])
            ;

            $spreadsheet->getActiveSheet()->getStyle("A" . $rowIdx . ":" . $lastColumn . "" . $rowIdx)->applyFromArray($outlineBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("A" . $rowIdx . ":" . $lastColumn . "" . $rowIdx)->applyFromArray($allBorderStyle);
            /* SET ROW COLOR */
            if ($rowIdx % 2 == 1) {
                $spreadsheet->getActiveSheet()->getStyle('A' . $rowIdx . ':' . $lastColumn . '' . $rowIdx)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('EAEBAF');
            }
        } /* end foreach ($query as $row) */


        $spreadsheet->getActiveSheet()
            ->setCellValue('A1', 'LIST SALARY KARYAWAN PT SANGATI SOERYA SEJAHTERA')
            ->setCellValue('A2', 'PT ' . $clientFullName);

        /* SET NUMBERS FORMAT*/

        unset($allBorderStyle);
        unset($center);
        unset($right);
        unset($left);

        $spreadsheet->setActiveSheetIndex(0);
        $datenow = date('Y-m-d');
        $str = 'LIST_SALARY_' . $clientName . '_' . $datenow;
        $fileName = preg_replace('/\s+/', '', $str);

        // Redirect output to a client�s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '.Xlsx"');
        // header('Content-Disposition: attachment;filename="Report Excel.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        /* BY COMPOSER */
        // $writer = new Xlsx($spreadsheet);
        /* OFFLINE/ BY COPY EXCEL FOLDER  */
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit(0);
    }
}
