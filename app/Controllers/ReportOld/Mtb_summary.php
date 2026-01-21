<?php namespace App\Controllers\Report;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Transaction\M_tr_timesheet;
use App\Models\Transaction\M_upload;
use App\Models\Transaction\M_proses;
use App\Models\Transaction\M_tr_overtime;
use App\Models\Transaction\M_tr_slip;

class Mtb_summary extends BaseController
{
    public function index()
     {
         /* ***Using Valid Path */
         $data['actView'] = 'Report/v_mtb_summery';
         return view('home', $data);
     }
 
    public function getPayrollList($tahun,$bulan)
    {
        $data_proses = new M_tr_timesheet();
        $data_proses->setYearProcess($tahun);
        $data_proses->setMonthProcess($bulan);

        $data = $data_proses->Print1();
        // echo $this->db->last_query(); exit(0);
        // echo $this->db->last_query(); exit(0);
        /*return json_encode($query);*/
        $myData = array();
        foreach ($data as $key => $row) 
        {
               $myData[] = array(
                $row['biodata_id'],
                $row['full_name'],         
                $row['tax_no'],         
                $row['ts_id'],         
                $row['monthly'],         
                $row['totalot'],         
                $row['thr'],         
                $row['tax_non_reg'],        
                $row['gaji'],         
                $row['adjustment'],         
                $row['totalgaji']
            );            
        }
          

        echo json_encode($myData);  
        // echo $this->db->last_query(); 
    } 


     public function exportSummaryPayrollMtb($yearPeriod, $monthPeriod)
    {
        // Create new Spreadsheet object
        $summary = new M_tr_timesheet();
        $summary->setYearProcess($yearPeriod);
        $summary->setMonthProcess($monthPeriod);
        $data = $summary->Print1();
        $spreadsheet = new Spreadsheet();

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
                // 'color' => ['argb' => '0000FF'],
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

        
        
        // Add some data
        $spreadsheet->getActiveSheet()->getStyle('A6:AG7')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B'); 

        
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1', 'PT. SANGATI SOERYA SEJAHTERA')
            ->setCellValue('A2', 'SUMMARY PAYROLL PT. Agincourt')
            ->setCellValue('A4', 'Periode : '.$monthPeriod.'-'.$yearPeriod);

        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true)->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle("A2:D2")->getFont()->setBold(true)->setSize(14)->setUnderline(true);
        $spreadsheet->getActiveSheet()->getStyle("A4:G4")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("A6:AG7")->getFont()->setBold(true)->setSize(12);

        $spreadsheet->getActiveSheet()->mergeCells("A6:A7");
        $spreadsheet->getActiveSheet()->mergeCells("B6:B7");
        $spreadsheet->getActiveSheet()->mergeCells("C6:C7");
        $spreadsheet->getActiveSheet()->mergeCells("D6:D7");
        $spreadsheet->getActiveSheet()->mergeCells("E6:E7");
        $spreadsheet->getActiveSheet()->mergeCells("F6:F7");
        $spreadsheet->getActiveSheet()->mergeCells("G6:G7");
        $spreadsheet->getActiveSheet()->mergeCells("H6:H7");
        $spreadsheet->getActiveSheet()->mergeCells("I6:I7");
        $spreadsheet->getActiveSheet()->mergeCells("J6:J7");
        $spreadsheet->getActiveSheet()->mergeCells("K6:K7");
        $spreadsheet->getActiveSheet()->mergeCells("L6:L7");
        $spreadsheet->getActiveSheet()->mergeCells("M6:M7");
        $spreadsheet->getActiveSheet()->mergeCells("N6:N7");
        $spreadsheet->getActiveSheet()->mergeCells("O6:O7");
        $spreadsheet->getActiveSheet()->mergeCells("P6:P7");
        $spreadsheet->getActiveSheet()->mergeCells("Q6:Q7");
        $spreadsheet->getActiveSheet()->mergeCells("R6:R7");
        $spreadsheet->getActiveSheet()->mergeCells("S6:S7");
        $spreadsheet->getActiveSheet()->mergeCells("T6:T7");
        $spreadsheet->getActiveSheet()->mergeCells("U6:U7");
        $spreadsheet->getActiveSheet()->mergeCells("V6:V7");
        $spreadsheet->getActiveSheet()->mergeCells("W6:W7");
        $spreadsheet->getActiveSheet()->mergeCells("X6:X7");
        $spreadsheet->getActiveSheet()->mergeCells("Y6:Y7");
        $spreadsheet->getActiveSheet()->mergeCells("Z6:Z7");
        $spreadsheet->getActiveSheet()->mergeCells("AA6:AA7");
        $spreadsheet->getActiveSheet()->mergeCells("AB6:AB7");
        $spreadsheet->getActiveSheet()->mergeCells("AC6:AC7");
        $spreadsheet->getActiveSheet()->mergeCells("AD6:AD7");
        $spreadsheet->getActiveSheet()->mergeCells("AE6:AE7");
        $spreadsheet->getActiveSheet()->mergeCells("AF6:AF7");
        $spreadsheet->getActiveSheet()->mergeCells("AG6:AG7");
        // $spreadsheet->getActiveSheet()->mergeCells("AH6:AH7");
        // $spreadsheet->getActiveSheet()->mergeCells("AM6:AM7");
            
        $spreadsheet->getActiveSheet()->getStyle("A6:AG7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A6:AG7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()
            ->setCellValue('A6', 'NO')
            ->setCellValue('B6', 'YEAR')
            ->setCellValue('C6', 'MONTH')
            ->setCellValue('D6', 'SM')
            ->setCellValue('E6', 'DEPARTMENT')
            ->setCellValue('F6', 'EXTERNAL ID')
            ->setCellValue('G6', 'NAMA')
            ->setCellValue('H6', 'GENDER')
            ->setCellValue('I6', 'MARITAL STATUS')
            ->setCellValue('J6', 'NPWP')
            ->setCellValue('K6', 'POSITION')
            ->setCellValue('L6', 'ADDRESS')
            ->setCellValue('M6', 'BASIC SALARY')
            ->setCellValue('N6', 'BASIC SALARY PRORATE')
            ->setCellValue('O6', 'OVERTIME TOTAL')
            ->setCellValue('P6', 'OTHER ALLOWANCE')
            ->setCellValue('Q6', 'THR')
            ->setCellValue('R6', 'NIGHT SHIFT')
            ->setCellValue('S6', 'DAY SHIFT')
            ->setCellValue('T6', 'JKK & JKM')
            ->setCellValue('U6', 'HEALTH BPJS')
            ->setCellValue('V6', 'UNPAID TOTAL')
            ->setCellValue('W6', 'GROSS')
            ->setCellValue('X6', 'PAJAK REGULER')
            ->setCellValue('Y6', 'PAJAK NON REGULER')
            ->setCellValue('Z6', 'PINALTY')
            ->setCellValue('AA6', 'JKK & JKM')
            ->setCellValue('AB6', 'HEALTH BPJS')
            ->setCellValue('AC6', 'EMP HEALTH BPJS')
            ->setCellValue('AD6', 'EMP JHT')
            ->setCellValue('AE6', 'EMP JP')
            ->setCellValue('AF6', 'ADJUSTMENT')
            ->setCellValue('AG6', 'NET PAYMENT');
            

        /* START GET DAYS TOTAL BY ROSTER */
        $rowIdx = 7;
        $rowNo = 0;
        foreach ($data as $row) {                      
            $rowIdx++;
            $rowNo++;
            
            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.($rowIdx), $rowNo)
                ->setCellValue('B'.($rowIdx), $row['year_process'])
                ->setCellValue('C'.($rowIdx), $row['month_process'])
                ->setCellValue('D'.($rowIdx), $row['payroll_group'])
                ->setCellValue('E'.($rowIdx), $row['dept'])
                ->setCellValue('F'.($rowIdx), $row['biodata_id'])
                ->setCellValue('G'.($rowIdx), $row['full_name'])
                ->setCellValue('H'.($rowIdx), $row['gender'])
                ->setCellValue('I'.($rowIdx), $row['marital_status'])
                ->setCellValue('J'.($rowIdx), $row['tax_no'])
                ->setCellValue('K'.($rowIdx), $row['position'])
                ->setCellValue('L'.($rowIdx), $row['id_card_address'])
                ->setCellValue('M'.($rowIdx), $row['monthly'])
                ->setCellValue('N'.($rowIdx), $row['gajipokok'])
                ->setCellValue('O'.($rowIdx), $row['totalot'])
                ->setCellValue('P'.($rowIdx), $row['allowance_03'])
                ->setCellValue('Q'.($rowIdx), $row['thr'])
                ->setCellValue('R'.($rowIdx), $row['allowance_01'])
                ->setCellValue('S'.($rowIdx), $row['allowance_02'])
                ->setCellValue('T'.($rowIdx), $row['jkkjkm'])
                ->setCellValue('U'.($rowIdx), $row['bpjs'])
                ->setCellValue('V'.($rowIdx), $row['unpaid'])                
                // ->setCellValue('W'.($rowIdx), $row['adjust_in'])
                // ->setCellValue('X'.($rowIdx), $row['adjust_out'])
                ->setCellValue('W'.($rowIdx), $row['gaji'])
                ->setCellValue('X'.($rowIdx), $row['tax_reg'])
                ->setCellValue('Y'.($rowIdx), $row['tax_non_reg'])
                ->setCellValue('Z'.($rowIdx), $row["tax_penalty"])
                ->setCellValue('AA'.($rowIdx), $row['jkkjkm'])
                ->setCellValue('AB'.($rowIdx), $row['bpjs'])
                ->setCellValue('AC'.($rowIdx), $row['emp_bpjs'])
                ->setCellValue('AD'.($rowIdx), $row['emp_jht'])
                ->setCellValue('AE'.($rowIdx), $row['emp_jp'])
                ->setCellValue('AF'.($rowIdx), $row['adjustment'])
                ->setCellValue('AG'.($rowIdx), $row['totalgaji'])                
                ;     

            /* SET ROW COLOR */
            if($rowIdx % 2 == 1)
            {
                $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':AG'.$rowIdx)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('EAEBAF');             
            } 
        }

        $spreadsheet->getActiveSheet()->getStyle("A6:AG".($rowIdx))->applyFromArray($allBorderStyle);
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Report Excel '.date('d-m-Y H'));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        $str = 'Summary Payroll'.$yearPeriod.$monthPeriod;
        $fileName = preg_replace('/\s+/', '', $str);
        // $str = 'PTLAgrInvoice';
        // $fileName = 'Summary Payroll PT.'.$ptName.'';
        // test($fileName,1);
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment;filename="Summary Payroll PT.'.$ptName.'.Xlsx"');
        header('Content-Disposition: attachment;filename="'.$fileName.'.Xlsx"');
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