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
use App\Models\Master\M_dept;


class Mtb_invoice extends BaseController
{
	public function index()
     {
         /* ***Using Valid Path */
         $mtDept = new M_dept();
         $data['data_dept'] = $mtDept->get_dept();
         $data['actView'] = 'Report/v_mtb_invoice';
         return view('home', $data);
     }

     public function exportSummaryPayrollPontilSwq($yearPeriod, $monthPeriod, $sm, $dept)
    {
        // Create new Spreadsheet object

        $summary = new M_tr_timesheet();
        $depart = str_replace("%20"," ",$dept);
        // echo $depart;
        // exit();
        $summary->setYearProcess($yearPeriod);
        $summary->setMonthProcess($monthPeriod);
	    $summary->setpayrollGroup($sm);
        $summary->setdept($depart);
        echo ($dept);
        exit();
        $data = $summary->summary();
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

        

        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'SUMMARY INVOICE PT SANGATI SOERYA SEJAHTERA - PT. Agincourt Resources Martabe Gold Mine');
        $spreadsheet->getActiveSheet()->mergeCells("A1:W1");
        $spreadsheet->getActiveSheet()->getStyle("A1:W1")->applyFromArray($center);
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'PT. PONTIL');
        // $spreadsheet->getActiveSheet()->mergeCells("A2:U2");
        // $spreadsheet->getActiveSheet()->getStyle("A2:U2")->applyFromArray($center);
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'PERIOD : '.$monthPeriod.'-'.$yearPeriod);
        $spreadsheet->getActiveSheet()->mergeCells("A2:W2");
        $spreadsheet->getActiveSheet()->getStyle("A2:W2")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true)->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle("A2:D2")->getFont()->setBold(true)->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle("A3:G5")->getFont()->setBold(true)->setSize(12); 
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'DATE        : ');
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'INVOICE NO  : ');
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 5, 'CONTRACT NO : ');        

        /* SET HEADER BG COLOR*/
        $spreadsheet->getActiveSheet()->getStyle('A6:W6')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B'); 

        /* START INVOICE TITLE */
        $spreadsheet->getActiveSheet()->getStyle("A6:W6")->getFont()->setSize(12);

        $spreadsheet->getActiveSheet()->getStyle("A6:A6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("B6:B6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("C6:C6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("D6:D6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("E6:E6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("F6:F6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("G6:G6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("H6:H6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("I6:I6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("J6:J6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("K6:K6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("L6:L6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("M6:M6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("N6:N6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("O6:O6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("P6:P6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("Q6:Q6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("R6:R6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("S6:S6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("T6:T6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("U6:U6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("V6:V6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("W6:W6")->applyFromArray($allBorderStyle);


        $spreadsheet->getActiveSheet()->getStyle("A6:A6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("B6:B6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("C6:C6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("D6:D6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("F6:F6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("G6:G6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("H6:H6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("I6:I6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("J6:J6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("K6:K6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("L6:L6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("M6:M6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("N6:N6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("O6:O6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("P6:P6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("Q6:Q6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("R6:R6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("S6:S6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("T6:T6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("U6:U6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("V6:V6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("W6:W6")->applyFromArray($center);

        $spreadsheet->getActiveSheet()->getStyle("B6:B6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("C6:C6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("F6:F6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("G6:G6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("H6:H6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("I6:I6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("J6:J6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("K6:K6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("L6:L6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("M6:M6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("N6:N6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("O6:O6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("P6:P6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("Q6:Q6")->getAlignment()->setWrapText(true);

        $spreadsheet->getActiveSheet()->getStyle("A6:H6")->applyFromArray($outlineBorderStyle);

        $spreadsheet->getActiveSheet()->mergeCells("A6:A6");
        $spreadsheet->getActiveSheet()->mergeCells("B6:B6");
        $spreadsheet->getActiveSheet()->mergeCells("C6:C6");
        $spreadsheet->getActiveSheet()->mergeCells("D6:D6");
        $spreadsheet->getActiveSheet()->mergeCells("F6:F6");
        $spreadsheet->getActiveSheet()->mergeCells("G6:G6");
        $spreadsheet->getActiveSheet()->mergeCells("H6:H6");
        $spreadsheet->getActiveSheet()->mergeCells("I6:I6");
        $spreadsheet->getActiveSheet()->mergeCells("J6:J6");
        $spreadsheet->getActiveSheet()->mergeCells("K6:K6");
        $spreadsheet->getActiveSheet()->mergeCells("M6:M6");
        $spreadsheet->getActiveSheet()->mergeCells("N6:N6");
        $spreadsheet->getActiveSheet()->mergeCells("O6:O6");
        $spreadsheet->getActiveSheet()->mergeCells("P6:P6");
        $spreadsheet->getActiveSheet()->mergeCells("Q6:Q6");

        $total = 0;
        $rounded = 0;
        $gaji = 0;
        $dana = 0;
        

        $spreadsheet->getActiveSheet()
                ->setCellValue('A6', 'NO')
                ->setCellValue('B6', 'NO KODE PAYROLL')
                ->setCellValue('C6', 'NAMA')
                ->setCellValue('D6', 'JABATAN DAN LOKASI KERJA')
                ->setCellValue('E6', 'BANK')
                ->setCellValue('F6', 'NO. REK')
                ->setCellValue('G6', 'BASIC')
                ->setCellValue('H6', 'OT')
                ->setCellValue('I6', 'N SHIFT')
                ->setCellValue('J6', 'JKK/JKM')
                ->setCellValue('K6', 'SHIFT')
                ->setCellValue('L6', 'BPJS KES 4%')
                ->setCellValue('M6', 'THR')
                ->setCellValue('N6', 'DANA KONPENSASI')
                ->setCellValue('O6', 'JKK/JKM')
                ->setCellValue('P6', 'BPJS KES 5%')
                ->setCellValue('Q6', 'JHT & JP')
                ->setCellValue('R6', 'PPH 21')
                ->setCellValue('S6', 'ADJ')
                ->setCellValue('T6', 'TOTAL')
                ->setCellValue('U6', 'ROUNDED')
                ->setCellValue('V6', 'GAJI')
                ->setCellValue('W6', 'KETERANGAN');

        /* START TOTAL WORK HOUR */     

        $rowIdx = 7;
        $startIdx = $rowIdx; 
        $rowNo = 0;
        $totalbasic = 0;
        $totalott = 0;
        $totalnshift = 0;
        $totaljkkjkm  = 0;
        $totalshift = 0;
        $totalbpjs1 = 0;
        $totalthr = 0;
        $totaldanakonpen = 0;
        $totaljkkjkm = 0;
        $totaljkkjkm1 = 0;
        $totalbpjs = 0;
        $totaljhtjp = 0;
        $totalpph21 = 0;
        $totaladjustmen = 0;
        $totaltotal = 0;
        $totalround = 0;
        $totalgaaji = 0;

        foreach ($data as $row) {
        	$totalsementara = $row['totalgaji'];
            $rowIdx++;
            $rowNo++;
            $total = $totalsementara + $row['thr'] + $row['allowance_03'] + $row['adjustment'] - $row['pph21'] - $row['emp_bpjs'] - $row['jhtjp'] - $row['jkkjkm'];
        

        


            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.$rowIdx, $rowNo)
                ->setCellValue('B'.$rowIdx, $row['biodata_id']) 
                ->setCellValue('C'.$rowIdx, $row['full_name'])
                ->setCellValue('D'.$rowIdx, $row['position'])
                ->setCellValue('E'.$rowIdx, $row['bank_name'])
                ->setCellValue('F'.$rowIdx, $row['account_no'])
                ->setCellValue('G'.$rowIdx, $row['gaji'])
                ->setCellValue('H'.$rowIdx, $row['totalot'])
                ->setCellValue('I'.$rowIdx, $row['allowance_02'])
                ->setCellValue('J'.$rowIdx, $row['jkkjkm'])
                ->setCellValue('K'.$rowIdx, $row['allowance_01'])
                ->setCellValue('L'.$rowIdx, $row['bpjs'])
                ->setCellValue('M'.$rowIdx, $row['thr'])
                ->setCellValue('N'.$rowIdx, $row['allowance_03'])
                ->setCellValue('O'.$rowIdx, '(-'.$row['jkkjkm'].')')
                ->setCellValue('P'.$rowIdx, '(-'.$row['emp_bpjs'].')')
                ->setCellValue('Q'.$rowIdx, '(-'.$row['jhtjp'].')')
                ->setCellValue('R'.$rowIdx, '(-'.$row['pph21'].')')
                ->setCellValue('S'.$rowIdx, $row['adjustment'])
                ->setCellValue('T'.$rowIdx, $row['gaji'])
                ->setCellValue('U'.$rowIdx, $row['round'])
                ->setCellValue('V'.$rowIdx, $row['totalgaji'])
                ->setCellValue('W'.$rowIdx, '');

 			$spreadsheet->getActiveSheet()->getStyle("A".$rowIdx.":W".$rowIdx)->applyFromArray($outlineBorderStyle);
 			$spreadsheet->getActiveSheet()->getStyle("A".$rowIdx.":W".$rowIdx)->applyFromArray($allBorderStyle);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rowIdx, $rowNo);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $rowIdx, $row['name']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $rowIdx, $row['job_desc']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $rowIdx, $row['basic_salary']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $rowIdx, $row['rate']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $rowIdx, $row['normal_time']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $rowIdx, $row['ot_count1']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $rowIdx, $row['ot_count2']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $rowIdx, $row['ot_count3']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $rowIdx, $row['ot_count4']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $rowIdx, $row['worked_hours']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $rowIdx, $row['current_salary']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $rowIdx, $row['ot_total']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $rowIdx, $row['travel_bonus']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $rowIdx, $gross);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $rowIdx, $row['uplift']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $rowIdx, '=IF(K'.$rowIdx.'<=312,((E'.$rowIdx.'*K'.$rowIdx.')*64%),((E'.$rowIdx.'*312)*64%))');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $rowIdx, '1');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $rowIdx, $chargeTotal);
            /* SET ROW COLOR */
            if($rowIdx % 2 == 1)
            {
                $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':W'.$rowIdx)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('EAEBAF');             
            } 
            /* END UPDATE TAX */
            $totalbasic = $totalbasic + $row['gaji'];
            $totalott = $totalott + $row['totalot'];
            $totalnshift = $totalnshift + $row['allowance_02'];
            $totaljkkjkm  = $totaljkkjkm + $row['jkkjkm'];
            $totalshift = $totalshift + $row['allowance_01'];
            $totalbpjs1 = $totalbpjs1 + $row['bpjs'];
            $totalthr = $totalthr + $row['thr'];
            $totaldanakonpen = $totaldanakonpen + $row['allowance_03'];
            $totaljkkjkm1 = $totaljkkjkm1 + $row['jkkjkm'];
            $totalbpjs = $totalbpjs + $row['emp_bpjs'];
            $totaljhtjp = $totaljhtjp + $row['jhtjp'];
            $totalpph21 = $totalpph21 + $row['pph21'];
            $totaladjustmen = $totaladjustmen + $row['adjustment'];
            $totaltotal = $totaltotal + $row['gaji'];
            $totalround = $totalround + $row['round'];
            $totalgaaji = $totalgaaji + $row['totalgaji'];

        
        $spreadsheet->getActiveSheet()
                ->setCellValue('A1', 'SUMMARY PAYMENT PT SANGATI SOERYA SEJAHTERA - PT '.'AGINCOURT MARTABE RESOURCES')
                ->setCellValue('A2', 'DEPARTEMENT : '.$row['dept'])
                ->setCellValue('A5', 'DUE DATE : '.$monthPeriod.'-'.$yearPeriod);
	} /* end foreach ($query as $row) */
        $spreadsheet->getActiveSheet()
        	->setCellValue('G'.($rowIdx+1), $totalbasic)
       		->setCellValue('H'.($rowIdx+1), $totalott)
        	->setCellValue('I'.($rowIdx+1), $totalnshift)
        	->setCellValue('J'.($rowIdx+1), $totaljkkjkm)
        	->setCellValue('K'.($rowIdx+1), $totalshift)
        	->setCellValue('L'.($rowIdx+1), $totalbpjs1)
        	->setCellValue('M'.($rowIdx+1), $totalthr)
        	->setCellValue('N'.($rowIdx+1), $totaldanakonpen)
        	->setCellValue('O'.($rowIdx+1), $totaljkkjkm1)
        	->setCellValue('P'.($rowIdx+1), $totalbpjs)
        	->setCellValue('Q'.($rowIdx+1), $totaljhtjp)
        	->setCellValue('R'.($rowIdx+1), $totalpph21)
        	->setCellValue('S'.($rowIdx+1), $totaladjustmen)
        	->setCellValue('T'.($rowIdx+1), $totaltotal)
        	->setCellValue('U'.($rowIdx+1), $totalround)
            ->setCellValue('F'.($rowIdx+1), 'TOTAL')
            ->setCellValue('V'.($rowIdx+1), $totalgaaji)
            ;
       

        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, ($rowIdx+2), "TOTAL");
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(16, ($rowIdx+2), "=SUM(Q".$startIdx.":Q".$rowIdx.")");
        $spreadsheet->getActiveSheet()->getStyle('G8:W'.($rowIdx+1))->getNumberFormat()->setFormatCode('#,##0.00');       
        
        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+1).":W".($rowIdx+1))
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B');

        /* SET NUMBERS FORMAT*/
        
        unset($allBorderStyle);
        unset($center);
        unset($right);
        unset($left);
        
        $spreadsheet->setActiveSheetIndex(0);

        $str = 'AGRSummary';
        $fileName = preg_replace('/\s+/', '', $str);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
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

    public function exportPaymentPayrollPontilSwq($yearPeriod, $monthPeriod, $sm, $dept)
    {   

        $depart = str_replace("%20"," ",$dept);
        // echo $depart;
        // exit();
        $spreadsheet = new Spreadsheet(); 
        $summary = new M_tr_timesheet();
        $summary->setYearProcess($yearPeriod);
        $summary->setMonthProcess($monthPeriod);
	    $summary->setpayrollGroup($sm);
        $summary->setdept($depart);
        $data = $summary->summary(); 

        $spreadsheet->getProperties()->setCreator('Maurice - Web - Android')
            ->setLastModifiedBy('Maurice - Web - Android')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');

        if (file_exists('assets/images/Report_logo.jpg')) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath('./assets/images/Report_logo.jpg');
            $drawing->setCoordinates('A1');
            $drawing->setHeight(36);
            $drawing->setWorksheet($spreadsheet->getActiveSheet());
        }
        foreach(range('B','I') as $columnID)
        {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Nama Field Baris Pertama
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1', 'PT. SANGATI SOERYA SEJAHTERA')
            ->setCellValue('A2', 'LIST PEMBAYARAN GAJI KARYAWAN PT. '.strtoupper('AGINCOURT').'')
            // ->setCellValue('A3', ''.$dept.' ' )
            ->setCellValue('A3', 'Periode : '.$monthPeriod.'-'.$yearPeriod);

        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true)->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle("A2:D2")->getFont()->setBold(true)->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle("A3:D3")->getFont()->setBold(true)->setSize(13);
        // $spreadsheet->getActiveSheet()->getStyle("A4:G4")->getFont()->setBold(true)->setSize(12); 

        $spreadsheet->getActiveSheet()
            ->mergeCells("A1:I1")
            ->mergeCells("A2:I2")
            ->mergeCells("A3:I3");
            // ->mergeCells("A4:I4");

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

        /* COLOURING FOOTER */
        $spreadsheet->getActiveSheet()->getStyle("A6:I7")
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B'); 

        $spreadsheet->getActiveSheet()
            ->setCellValue('A6', 'NO')  
            ->setCellValue('B6', 'NAMA')  
            ->setCellValue('C6', 'ID')  
            ->setCellValue('D6', 'CLASS')  
            ->setCellValue('E6', 'NO REKENING')  
            ->setCellValue('F6', 'NAMA REKENING')  
            ->setCellValue('G6', 'JUMLAH') 
            ->setCellValue('H6', 'BANK CODE')  
            ->setCellValue('I6', 'BANK'); 

        $spreadsheet->getActiveSheet()->getStyle("A6:I6")->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("A6:I6")->applyFromArray($outlineBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A6:A7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("B6:B7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("C6:C7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("D6:D7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("E6:E7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("F6:F7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("G6:G7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("H6:H7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("I6:I7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A6:A7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("B6:B7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("C6:C7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("D6:D7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("E6:E7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("F6:F7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("G6:G7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("H6:H7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("I6:I7")->applyFromArray($center);

        $spreadsheet->getActiveSheet()->getStyle("A1:I4")->applyFromArray($center);

        $spreadsheet->getActiveSheet()
            ->mergeCells("A6:A7")
            ->mergeCells("B6:B7")
            ->mergeCells("C6:C7")
            ->mergeCells("D6:D7")
            ->mergeCells("E6:E7")
            ->mergeCells("F6:F7")
            ->mergeCells("G6:G7")
            ->mergeCells("H6:H7")
            ->mergeCells("I6:I7");

        $spreadsheet->getActiveSheet()->getStyle("B6:B7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("C6:C7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("D6:D7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("E6:E7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("F6:F7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("G6:G7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("H6:H7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("I6:I7")->getAlignment()->setWrapText(true);


        $rowIdx = 8;
        $rowNo = 0;
        foreach ($data as $row) {
            $rowIdx++;
            $rowNo++;
            $biodataId = $row['biodata_id'];
            $totalTerima = $row['totalgaji'];
        $spreadsheet->getActiveSheet()
                ->setCellValue('A'.$rowIdx, $rowNo)
                ->setCellValue('B'.$rowIdx, $row['full_name'])
                ->setCellValueExplicit('C'.$rowIdx, $row['biodata_id'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValue('D'.$rowIdx, $row['position'])
                ->setCellValueExplicit('E'.$rowIdx, $row['account_no'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValue('F'.$rowIdx, $row['account_name'])
                ->setCellValue('G'.$rowIdx, $totalTerima)
                ->setCellValue('H'.$rowIdx, $row['bank_code'])
                ->setCellValue('I'.$rowIdx, $row['bank_name'])
                ;
               
                
            /* END UPDATE TAX */
            /* SET ROW COLOR */
            if($rowIdx % 2 == 1)
            {
                $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':I'.$rowIdx)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('EAEBAF');             
            } 

        } /* end foreach ($query as $row) */       
        

        $spreadsheet->getActiveSheet()
                ->setCellValue('F'.($rowIdx+2), 'JUMLAH')
                ->setCellValue('G'.($rowIdx+2), '=SUM(G9:G'.$rowIdx.')');

        $spreadsheet->getActiveSheet()->getStyle("I6:I7")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("H6:H7")->applyFromArray($totalStyle);
        $totalBorder = $rowIdx+2;
        $spreadsheet->getActiveSheet()->getStyle("A".$totalBorder.":I".$totalBorder)->applyFromArray($outlineBorderStyle);

        /* SET NUMBERS FORMAT*/
        $spreadsheet->getActiveSheet()->getStyle('I8:I'.($rowIdx+2))->getNumberFormat()->setFormatCode('#,##0.00');
        /* COLOURING FOOTER */
        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+2).":I".($rowIdx+2))
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B');

        unset($allBorderStyle);
        unset($center);
        unset($right);
        unset($left);
        
        // Rename worksheet

        $spreadsheet->getActiveSheet()->setTitle('PaymentListAGR '.date('d-m-Y H'));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        //Nama File
        // $str = $rowData['name'].$rowData['bio_rec_id'];
        $str = 'PAYMENTLISTAGR'.$monthPeriod.$yearPeriod;
        $fileName = preg_replace('/\s+/', '', $str);

        // Redirect output to a clientâ€™s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
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

    public function getPayrollList($tahun,$bulan,$sm)
    {
        $data_proses = new M_tr_timesheet();
        $data_proses->setMonthProcess($bulan);
        $data_proses->setYearProcess($tahun);
	    $data_proses->setpayrollGroup($sm);
        $data = $data_proses->getAllData();
        // echo $this->db->last_query(); exit(0);
        // echo $this->db->last_query(); exit(0);
        /*return json_encode($query);*/
        $myData = array();
        foreach ($data as $key => $row) 
        {
               $myData[] = array(
                $row['slip_id'],
                $row['full_name'],         
                // $row['production_bonus'],         
                // $row['workday_adj'],         
                // $row['adjust_in'],         
                // $row['adjust_out'],         
                $row['dept'],         
                $row['position']        
                // $row['attendance_bonus'],         
                // $row['other_allowance1'],         
                // $row['other_allowance2'],         
                // $row['cc_payment'],         
                // $row['thr'],         
                // $row['debt_burden'],
                // $row['debt_explanation']
            );            
        }
          

        echo json_encode($myData);  
        // echo $this->db->last_query(); 
    } 
    public function exportinvoicePayrollPontilSwq($yearPeriod, $sm,$monthPeriod)
    {
        $summary = new M_tr_timesheet();
        $summary->setYearProcess($yearPeriod);
        $summary->setMonthProcess($monthPeriod);
	    $summary->setpayrollGroup($sm);
        $data = $summary->invoice();
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
        $spreadsheet->getActiveSheet()->getStyle('A6:R7')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B'); 

        
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1', 'PT. SANGATI SOERYA SEJAHTERA')
            ->setCellValue('A2', 'INVOICE PAYROLL PT. Agincourt ')
            ->setCellValue('A4', 'Periode : '.$monthPeriod.'-'.$yearPeriod);

        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true)->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle("A2:D2")->getFont()->setBold(true)->setSize(14)->setUnderline(true);
        $spreadsheet->getActiveSheet()->getStyle("A4:G4")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("A6:R7")->getFont()->setBold(true)->setSize(12);

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
            
        $spreadsheet->getActiveSheet()->getStyle("A6:R7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A6:R7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()
            ->setCellValue('A6', 'No.')
            ->setCellValue('B6', 'Department / User')
            ->setCellValue('C6', 'SM')
            ->setCellValue('D6', 'Total Empl.')
            ->setCellValue('E6', 'Total Work Day(s)')
            ->setCellValue('F6', 'Gaji Pokok Perbulana (Rp)')
            ->setCellValue('G6', 'Over Time dalam 1 (Satu) Bulan (Rp)')
            ->setCellValue('H6', 'Tunjangan Shift (Rp)')
            ->setCellValue('I6', 'TOTAL Gaji, Overtime Dan Tunjangan Shift (Rp)')
            ->setCellValue('J6', 'BPJS TENAGA KERJA (Rp)')
            ->setCellValue('K6', 'BPJS KESEHATAN')
            ->setCellValue('L6', 'BPJS PENSIUN')
            ->setCellValue('M6', 'SUB TOTAL INVOICE')
            ->setCellValue('N6', 'MANAGAMENT FEE Kontraktor')
            ->setCellValue('O6', 'PPN 11% (WAPU)')
            ->setCellValue('P6', 'PPH 23 (2%)')
            ->setCellValue('Q6', 'TOTAL INVOICE')
            ->setCellValue('R6', 'PAID BY PTAR');
            
            

        /* START GET DAYS TOTAL BY ROSTER */
        $rowIdx = 7;
        $rowNo = 0;
        $totalemplall = 0;
        $totalworkall = 0;
        $gajipokokall = 0;
        $overtimeall = 0;
        $tunjanganall = 0;
        $bpjsTNKall = 0;
        $bpjsPNSall = 0; 
        $bpjsKSHall = 0;
        $totalall = 0;
        $subTOINVall = 0;
        $managamentFEEall = 0;
        $ppn11all = 0;
        $pph23all = 0;
        $totalIVCall = 0;
        $paidBPTRall = 0;

        foreach ($data as $row) {                      
            $rowIdx++;
            $rowNo++;  
            $totalempl = $row['totalempl'];
            $totalwork = $row['totalwork'];
            $gajipokok = $row['gajipokok'];
            $overtime = $row['overtime'];
            $tunjangan = $row['tunjangan'];
            $bpjsTNK = $totalempl * $gajipokok * 0.0574;
            $bpjsKSH = $totalempl * $gajipokok * 0.04;
            $bpjsPNS = $totalempl * $gajipokok * 0.02;
            $total = $gajipokok + $overtime + $tunjangan;
            $subTOINV = $total + $bpjsTNK + $bpjsKSH + $bpjsPNS;
            $managamentFEE = $total * 0.15;
            $ppn11 = $managamentFEE * 0.11;
            $pph23 = $managamentFEE * 0.02;
            $totalIVC = $subTOINV + $managamentFEE + $ppn11 - $pph23;
            $paidBPTR = $totalIVC - $ppn11; 

            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.($rowIdx), $rowNo)
                ->setCellValue('B'.($rowIdx), $row['dept'])
                ->setCellValue('C'.($rowIdx), $sm)
                ->setCellValue('D'.($rowIdx), $row['totalempl'])
                ->setCellValue('E'.($rowIdx), $row['totalwork'])
                ->setCellValue('F'.($rowIdx), $row['gajipokok'])
                ->setCellValue('G'.($rowIdx), $row['overtime'])
                ->setCellValue('H'.($rowIdx), $row['tunjangan'])
                ->setCellValue('I'.($rowIdx), $total)
                ->setCellValue('J'.($rowIdx), $bpjsTNK)
                ->setCellValue('K'.($rowIdx), $bpjsKSH)
                ->setCellValue('L'.($rowIdx), $bpjsPNS)
                ->setCellValue('M'.($rowIdx), $subTOINV)
                ->setCellValue('N'.($rowIdx), $managamentFEE)
                ->setCellValue('O'.($rowIdx), $ppn11)
                ->setCellValue('P'.($rowIdx), '('.$pph23.')')
                ->setCellValue('Q'.($rowIdx), $totalIVC)
                ->setCellValue('R'.($rowIdx), $paidBPTR)                
                ;   
            



            /* SET ROW COLOR */
            if($rowIdx % 2 == 1)
            {
                $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':R' .$rowIdx)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('EAEBAF');             
            } 
            $totalemplall = $totalemplall + $totalempl;
            $totalworkall = $totalworkall + $totalwork;
            $gajipokokall = $gajipokokall + $gajipokok;
            $overtimeall = $overtimeall + $overtime;
            $tunjanganall = $tunjanganall + $tunjangan;
            $bpjsTNKall = $bpjsTNKall + $bpjsTNK;
            $bpjsPNSall = $bpjsPNSall + $bpjsPNS;
            $bpjsKSHall = $bpjsKSHall + $bpjsKSH;
            $totalall = $totalall + $total;
            $subTOINVall = $subTOINVall + $subTOINV;
            $managamentFEEall = $managamentFEEall + $managamentFEE;
            $ppn11all = $ppn11all + $ppn11;
            $pph23all = $pph23all + $pph23;
            $totalIVCall = $totalIVCall + $totalIVC;
            $paidBPTRall = $paidBPTRall + $paidBPTR;
        }
        
            $spreadsheet->getActiveSheet()
                ->setCellValue('C'.($rowIdx+1), "TOTAL")
                ->setCellValue('D'.($rowIdx+1), $totalemplall)
                ->setCellValue('E'.($rowIdx+1), $totalworkall)
                ->setCellValue('F'.($rowIdx+1), $gajipokokall)
                ->setCellValue('G'.($rowIdx+1), $overtimeall)
                ->setCellValue('H'.($rowIdx+1), $tunjanganall)
                ->setCellValue('I'.($rowIdx+1), $totalall)
                ->setCellValue('J'.($rowIdx+1), $bpjsTNKall)
                ->setCellValue('K'.($rowIdx+1), $bpjsKSHall)
                ->setCellValue('L'.($rowIdx+1), $bpjsPNSall)
                ->setCellValue('M'.($rowIdx+1), $subTOINVall)
                ->setCellValue('N'.($rowIdx+1), $managamentFEEall)
                ->setCellValue('O'.($rowIdx+1), $ppn11all)
                ->setCellValue('P'.($rowIdx+1), '('.$pph23all.')')
                ->setCellValue('Q'.($rowIdx+1), $totalIVCall)
                ->setCellValue('R'.($rowIdx+1), $paidBPTRall)  
                ;

        $spreadsheet->getActiveSheet()->getStyle('D8:R'.($rowIdx+1))->getNumberFormat()->setFormatCode('#,##0.00');       
        
        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+1).":R".($rowIdx+1))
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B');

        $spreadsheet->getActiveSheet()->getStyle("A6:R".($rowIdx+1))->applyFromArray($allBorderStyle);
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Report Excel '.date('d-m-Y H'));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        $str = 'Summary Payroll PT.Agincourt ';
        $fileName = preg_replace('/\s+/', '', $str);
        // $str = 'PTLSmbInvoice';
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

    public function exportPaymentForm($yearPeriod, $monthPeriod, $sm)
    {
        $summary = new M_tr_timesheet();
        $summary->setYearProcess($yearPeriod);
        $summary->setMonthProcess($monthPeriod);
	$summary->setMonthProcess($sm);
        $data = $summary->invoice();
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

        $bottomBorderStyle = [ 'borders' => [ 'bottom' => [ 'borderStyle' =>
        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' =>
        ['argb' => '00000000'], ], ], ];

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

        $formList = new M_tr_timesheet();
        $formList->setYearProcess($yearPeriod);
        $formList->setMonthProcess($monthPeriod);
        $data = $formList->formlist();
        
        // Add some data
        $spreadsheet->getActiveSheet()->getStyle('A6:F7')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B');
        $rowIdx = 8;
        $rowNo = 1;
        

         $spreadsheet->getActiveSheet()
            ->setCellValue('A4', 'PENGAJUAN PEMBAYARAN')
            ->setCellValue('A5', 'PT AGINCOURT RESOURCES');

        $spreadsheet->getActiveSheet()->getStyle("A4:F4")->getFont()->setBold(true)->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle("A4:F4")->getFont()->setBold(true)->setSize(14)->setUnderline(true);
        $spreadsheet->getActiveSheet()->getStyle("A5:F5")->getFont()->setBold(true)->setSize(12);

        $spreadsheet->getActiveSheet()->mergeCells("A6:A7");
        $spreadsheet->getActiveSheet()->mergeCells("B6:B7");
        $spreadsheet->getActiveSheet()->mergeCells("C6:D6");
        $spreadsheet->getActiveSheet()->mergeCells("D7:D7");
        $spreadsheet->getActiveSheet()->mergeCells("C7:D7");
        $spreadsheet->getActiveSheet()->mergeCells("E6:E7");
        $spreadsheet->getActiveSheet()->mergeCells("F6:F7");

        $spreadsheet->getActiveSheet()->getStyle("A6:F7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A6:F7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()   
            ->setCellValue('A6', 'No.')
            ->setCellValue('B6', 'Uraian')
            ->setCellValue('C6', 'Invoicable')
            ->setCellValue('C7', 'YA')
            ->setCellValue('D7', 'TIDAK')
            ->setCellValue('E6', 'COST CENTER')
            ->setCellValue('F6', 'Total Work Day(s)');  
            

        /* START GET DAYS TOTAL BY ROSTER */
        foreach ($data as $row) {
            $totalKaryawan = $row['total_karyawan'];
            $totalgaji  = $row['total'];

        $spreadsheet->getActiveSheet()
                ->setCellValue('A'.($rowIdx), $rowNo)
                ->setCellValue('B'.($rowIdx), 'Pembayaran Gaji Karyawan PT Agincourt Resources ('.$totalKaryawan.')')
                ->setCellValue('B'.($rowIdx+2), 'Periode :')
                ->setCellValue('B'.($rowIdx+4), 'Ket : Terlampir')
                ->setCellValue('B'.($rowIdx+5), 'Due Date:')
                ->setCellValue('C'.($rowIdx), '')
                ->setCellValue('D'.($rowIdx), '')
                ->setCellValue('E'.($rowIdx), '')
                ->setCellValue('F'.($rowIdx), $totalgaji);  
        // $spreadsheet->getActiveSheet()->getStyle("B8:D8")->getFont()->setBold(true)->setSize(12);



            /* SET ROW COLOR */
            if($rowIdx % 2 == 1)
            {
                $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':F' .$rowIdx)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('EAEBAF');             
            } 
            
        
        
            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.($rowIdx+9), "")
                ->setCellValue('B'.($rowIdx+9), "Total Pengajuan Pembayaran")
                ->setCellValue('C'.($rowIdx+9), "")
                ->setCellValue('D'.($rowIdx+9), "")
                ->setCellValue('E'.($rowIdx+9), "")
                ->setCellValue('F'.($rowIdx+9), $totalgaji);
                $datenow = date("Y/m/d");
                
            $spreadsheet->getActiveSheet()
                ->setCellValue('B'.($rowIdx+11), "Penarikan Cheque/BG No. : ……………………………………………..
")
                ->setCellValue('B'.($rowIdx+12), "Tanggal                                  : ……………………………………………..
")
                ->setCellValue('B'.($rowIdx+14), "Disetujui,                                                                     Checked,
")
                ->setCellValue('B'.($rowIdx+19), "(  Heri Susanto   )                                                      (                           )
")
                ->setCellValue('B'.($rowIdx+21), "Controller,                                                                     Finance,
")
                ->setCellValue('B'.($rowIdx+27), "( Etty Aryati )                                                                   (                            )
")
                ->setCellValue('F'.($rowIdx+13), 'JAKARTA  '.$datenow)
                ->setCellValue('F'.($rowIdx+14), "Diajukan,
")
                ->setCellValue('F'.($rowIdx+19), "(                                 )
")
                ->setCellValue('F'.($rowIdx+21), "Accounting,
")
                ->setCellValue('F'.($rowIdx+27), "(                                        )
");
                }
        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+9).':F'.($rowIdx+9))->getNumberFormat()->setFormatCode('#,##0.00');       
        
        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+9).":F".($rowIdx+9))
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B');

        $spreadsheet->getActiveSheet()->getStyle("A6:F".($rowIdx+9))->applyFromArray($allBorderStyle);
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Report Excel '.date('d-m-Y H'));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        $str = 'Summary Payroll PT.Agincourt_Resources ';
        $fileName = preg_replace('/\s+/', '', $str);
        // $str = 'PTLSmbInvoice';
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

    public function exportDbForm($yearPeriod, $monthPeriod, $sm, $startDate)
    {
        // Create new Spreadsheet object

        $summary = new M_tr_timesheet();
        // echo $depart;
        // exit();
        $summary->setYearProcess($yearPeriod);
        $summary->setMonthProcess($monthPeriod);
        $summary->setpayrollGroup($sm);
        $summary->setstartDate($startDate);
        // echo ($dept);
        // exit();
        $data = $summary->PrintDB();
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

        

        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'SUMMARY INVOICE PT SANGATI SOERYA SEJAHTERA - PT. Agincourt Resources Martabe Gold Mine');
        
        // $spreadsheet->getActiveSheet()->getStyle("A1:W1")->applyFromArray($center);
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'PT. PONTIL');
        // $spreadsheet->getActiveSheet()->mergeCells("A2:U4");
        // $spreadsheet->getActiveSheet()->getStyle("A2:U4")->applyFromArray($center);
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'PERIOD : '.$monthPeriod.'-'.$yearPeriod);
        // $spreadsheet->getActiveSheet()->mergeCells("A2:W4");
        // $spreadsheet->getActiveSheet()->getStyle("A2:W4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true)->setSize(18); 
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'DATE        : ');
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'INVOICE NO  : ');
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 5, 'CONTRACT NO : ');        

        /* SET HEADER BG COLOR*/
        

        $spreadsheet->getActiveSheet()->getStyle("A2:A4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("B2:B4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("C2:C4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("D2:D4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("E2:E4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("F2:F4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("G2:G4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("H2:H4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("I2:I4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("J2:J4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("K2:K4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("L2:L4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("M2:M4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("FM2:FM4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("FN2:FN4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("FO2:FO4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("FP2:FP4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("FQ2:FQ4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("FR2:FR4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("FS2:FS4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("FT2:FT4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("FU2:FU4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("FV2:FV4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("FW2:FW4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("FX2:FX4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("FY2:FY4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("FZ2:FZ4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("GA2:GA4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("GB2:GB4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("GC2:GC4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("GD2:GD4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("GE2:GE4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("GF2:GF4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("GG2:GG4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("GH2:GH4")->applyFromArray($allBorderStyle);


        $spreadsheet->getActiveSheet()->getStyle("A2:A4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("B2:B4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("C2:C4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("D2:D4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("E2:E4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("F2:F4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("G2:G4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("H2:H4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("I2:I4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("J2:J4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("K2:K4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("L2:L4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("M2:M4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("FM2:FM4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("FN2:FN4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("FO2:FO4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("FP2:FP4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("FQ2:FQ4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("FR2:FR4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("FS2:FS4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("FT2:FT4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("FU2:FU4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("FV2:FV4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("FW2:FW4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("FX2:FX4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("FY2:FY4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("FZ2:FZ4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("GA2:GA4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("GB2:GB4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("GC2:GC4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("GD2:GD4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("GE2:GE4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("GF2:GF4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("GG2:GG4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("GH2:GH4")->applyFromArray($center);

        $spreadsheet->getActiveSheet()->getStyle("B2:B4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("C2:C4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("F2:F4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("G2:G4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("H2:H4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("I2:I4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("J2:J4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("K2:K4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("L2:L4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("M2:M4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("FM2:FM4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("FN2:FN4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("FO2:FO4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("FP2:FP4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("FQ2:FQ4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("FR2:FR4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("FS2:FS4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("FT2:FT4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("FU2:FU4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("FV2:FV4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("FW2:FW4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("FX2:FX4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("FY2:FY4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("FZ2:FZ4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("GA2:GA4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("GB2:GB4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("GC2:GC4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("GD2:GD4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("GE2:GE4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("GF2:GF4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("GG2:GG4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("GH2:GH4")->getAlignment()->setWrapText(true);

        $spreadsheet->getActiveSheet()->getStyle("A2:H4")->applyFromArray($outlineBorderStyle);

        $spreadsheet->getActiveSheet()->mergeCells("A2:A4");
        $spreadsheet->getActiveSheet()->mergeCells("B2:B4");
        $spreadsheet->getActiveSheet()->mergeCells("C2:C4");
        $spreadsheet->getActiveSheet()->mergeCells("D2:D4");
        $spreadsheet->getActiveSheet()->mergeCells("E2:E4");
        $spreadsheet->getActiveSheet()->mergeCells("F2:F4");
        $spreadsheet->getActiveSheet()->mergeCells("G2:G4");
        $spreadsheet->getActiveSheet()->mergeCells("H2:H4");
        $spreadsheet->getActiveSheet()->mergeCells("I2:I4");
        $spreadsheet->getActiveSheet()->mergeCells("J2:J4");
        $spreadsheet->getActiveSheet()->mergeCells("K2:K4");
        $spreadsheet->getActiveSheet()->mergeCells("L2:L4");
        $spreadsheet->getActiveSheet()->mergeCells("M2:M4");
        $spreadsheet->getActiveSheet()->mergeCells("FM2:FM4");
        $spreadsheet->getActiveSheet()->mergeCells("FN2:FN4");
        $spreadsheet->getActiveSheet()->mergeCells("FO2:FO4");
        $spreadsheet->getActiveSheet()->mergeCells("FP2:FP4");
        $spreadsheet->getActiveSheet()->mergeCells("FQ2:FQ4");
        $spreadsheet->getActiveSheet()->mergeCells("FR2:FR4");
        $spreadsheet->getActiveSheet()->mergeCells("FS2:FS4");
        $spreadsheet->getActiveSheet()->mergeCells("FT2:FT4");
        $spreadsheet->getActiveSheet()->mergeCells("FU2:FU4");
        $spreadsheet->getActiveSheet()->mergeCells("FV2:FV4");
        $spreadsheet->getActiveSheet()->mergeCells("FW2:FW4");
        $spreadsheet->getActiveSheet()->mergeCells("FX2:FX4");
        $spreadsheet->getActiveSheet()->mergeCells("FY2:FY4");
        $spreadsheet->getActiveSheet()->mergeCells("FZ2:FZ4");
        $spreadsheet->getActiveSheet()->mergeCells("GA2:GA4");
        $spreadsheet->getActiveSheet()->mergeCells("GB2:GB4");
        $spreadsheet->getActiveSheet()->mergeCells("GC2:GC4");
        $spreadsheet->getActiveSheet()->mergeCells("GD2:GD4");
        $spreadsheet->getActiveSheet()->mergeCells("GE2:GF3");
        $spreadsheet->getActiveSheet()->mergeCells("GG2:GH3");

        $total = 0;
        $rounded = 0;
        $gaji = 0;
        $dana = 0;
        $y = 0;
        $z = 0;
        $x = 0;
        $AA = 0;
        $AB = 0;
        $AC = 0;
         $spreadsheet->getActiveSheet()->getStyle('A2:GH2')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B'); 

        $spreadsheet->getActiveSheet()
                ->setCellValue('A1', 'PERHITUNGAN GAJI PER BULAN');
        $alphabet =  
        array('','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        for ($x = 14; $x <= 168; $x+=5) {
        if ($x < 27) {
         $hasil = $alphabet[$x];
        }
        else if ($x > 26 && $x < 53) {
         $y = $x - 26;
         $hasil = 'A'.$alphabet[$y];
        }  else if ($x > 52 && $x < 79){
            $z = $x - 52;
            $hasil = 'B'.$alphabet[$z];
        } else if ($x > 78 && $x < 105) { 
            $AA = $x - 78;
            $hasil = 'C'.$alphabet[$AA];
        } else if ($x > 104 && $x < 131) {
            $AB = $x - 104;
            $hasil = 'D'.$alphabet[$AB];
        } else if ($x > 130 && $x < 157) {
            $AC = $x - 130;
            $hasil = 'E'.$alphabet[$AC];
        } else if ($x > 156 && $x < 183) {
            $AD = $x - 156;
            $hasil = 'F'.$alphabet[$AD];
        }
        $spreadsheet->getActiveSheet()
                ->setCellValue($hasil.'2', '18-Jun-22')
                ->setCellValue($hasil.'4', 'IN');
        $spreadsheet->getActiveSheet()->mergeCells($hasil."2:".$hasil."3");
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->applyFromArray($allBorderStyle);
       

        /* START INVOICE TITLE */
        $spreadsheet->getActiveSheet()->getStyle('A2:'.$hasil.'2')->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->mergeCells('A1:'.$hasil.'1');
        }
        for ($x = 15; $x <= 169; $x+=5) {
        if ($x < 27) {
         $hasil = $alphabet[$x];
        }
        else if ($x > 26 && $x < 53) {
         $y = $x - 26;
         $hasil = 'A'.$alphabet[$y];
        }  else if ($x > 52 && $x < 79){
            $z = $x - 52;
            $hasil = 'B'.$alphabet[$z];
        } else if ($x > 78 && $x < 105) { 
            $AA = $x - 78;
            $hasil = 'C'.$alphabet[$AA];
        } else if ($x > 104 && $x < 131) {
            $AB = $x - 104;
            $hasil = 'D'.$alphabet[$AB];
        } else if ($x > 130 && $x < 157) {
            $AC = $x - 130;
            $hasil = 'E'.$alphabet[$AC];
        } else if ($x > 156 && $x < 183) {
            $AD = $x - 156;
            $hasil = 'F'.$alphabet[$AD];
        }
        $spreadsheet->getActiveSheet()
                ->setCellValue($hasil.'2', 'Sabtu')
                ->setCellValue($hasil.'4', 'OUT');
                $spreadsheet->getActiveSheet()->mergeCells($hasil."2:".$hasil."3");
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle('A2:'.$hasil.'2')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B'); 

        /* START INVOICE TITLE */
        $spreadsheet->getActiveSheet()->getStyle('A2:'.$hasil.'2')->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->mergeCells('A1:'.$hasil.'1');
        }
        for ($x = 16; $x <= 169; $x+=5) {
        if ($x < 27) {
         $hasil = $alphabet[$x];
        }
        else if ($x > 26 && $x < 53) {
         $y = $x - 26;
         $hasil = 'A'.$alphabet[$y];
        }  else if ($x > 52 && $x < 79){
            $z = $x - 52;
            $hasil = 'B'.$alphabet[$z];
        } else if ($x > 78 && $x < 105) { 
            $AA = $x - 78;
            $hasil = 'C'.$alphabet[$AA];
        } else if ($x > 104 && $x < 131) {
            $AB = $x - 104;
            $hasil = 'D'.$alphabet[$AB];
        } else if ($x > 130 && $x < 157) {
            $AC = $x - 130;
            $hasil = 'E'.$alphabet[$AC];
        } else if ($x > 156 && $x < 183) {
            $AD = $x - 156;
            $hasil = 'F'.$alphabet[$AD];
        }
        $spreadsheet->getActiveSheet()
                ->setCellValue($hasil.'2', 'Shift Day')
                ->setCellValue($hasil.'4', 'D/S - NS');
        $spreadsheet->getActiveSheet()->mergeCells($hasil."2:".$hasil."4");
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle('A2:'.$hasil.'2')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B'); 

        /* START INVOICE TITLE */
        $spreadsheet->getActiveSheet()->getStyle('A2:'.$hasil.'2')->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->mergeCells('A1:'.$hasil.'1');
        }
        for ($x = 17; $x <= 169; $x+=5) {
         if ($x < 27) {
         $hasil = $alphabet[$x];
        }
        else if ($x > 26 && $x < 53) {
         $y = $x - 26;
         $hasil = 'A'.$alphabet[$y];
        }  else if ($x > 52 && $x < 79){
            $z = $x - 52;
            $hasil = 'B'.$alphabet[$z];
        } else if ($x > 78 && $x < 105) { 
            $AA = $x - 78;
            $hasil = 'C'.$alphabet[$AA];
        } else if ($x > 104 && $x < 131) {
            $AB = $x - 104;
            $hasil = 'D'.$alphabet[$AB];
        } else if ($x > 130 && $x < 157) {
            $AC = $x - 130;
            $hasil = 'E'.$alphabet[$AC];
        } else if ($x > 156 && $x < 183) {
            $AD = $x - 156;
            $hasil = 'F'.$alphabet[$AD];
        }
        $spreadsheet->getActiveSheet()
                ->setCellValue($hasil.'2', 'PO')
                ->setCellValue($hasil.'3', 'PH');
        $spreadsheet->getActiveSheet()->mergeCells($hasil."2:".$hasil."2");
        $spreadsheet->getActiveSheet()->mergeCells($hasil."3:".$hasil."4");
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle('A2:'.$hasil.'2')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B'); 

        /* START INVOICE TITLE */
        $spreadsheet->getActiveSheet()->getStyle('A2:'.$hasil.'2')->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->mergeCells('A1:'.$hasil.'1');
        }
        for ($x = 18; $x <= 169; $x+=5) {
        if ($x < 27) {
         $hasil = $alphabet[$x];
        }
        else if ($x > 26 && $x < 53) {
         $y = $x - 26;
         $hasil = 'A'.$alphabet[$y];
        }  else if ($x > 52 && $x < 79){
            $z = $x - 52;
            $hasil = 'B'.$alphabet[$z];
        } else if ($x > 78 && $x < 105) { 
            $AA = $x - 78;
            $hasil = 'C'.$alphabet[$AA];
        } else if ($x > 104 && $x < 131) {
            $AB = $x - 104;
            $hasil = 'D'.$alphabet[$AB];
        } else if ($x > 130 && $x < 157) {
            $AC = $x - 130;
            $hasil = 'E'.$alphabet[$AC];
        } else if ($x > 156 && $x < 183) {
            $AD = $x - 156;
            $hasil = 'F'.$alphabet[$AD];
        }
        $spreadsheet->getActiveSheet()
                ->setCellValue($hasil.'2', 'WD');
        $spreadsheet->getActiveSheet()->mergeCells($hasil."2:".$hasil."4");
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle('A2:'.$hasil.'2')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B'); 

        /* START INVOICE TITLE */
        $spreadsheet->getActiveSheet()->getStyle('A2:'.$hasil.'2')->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->mergeCells('A1:'.$hasil.'1');
        }

        $spreadsheet->getActiveSheet()
                ->setCellValue('A2', 'NO')
                ->setCellValue('B2', 'PERIODE')
                ->setCellValue('C2', 'NAME')
                ->setCellValue('D2', 'Nomor Badge')
                ->setCellValue('E2', 'Position')
                ->setCellValue('F2', 'Client Location')
                ->setCellValue('G2', 'Tanggal Mulai Kerja')
                ->setCellValue('H2', 'END KONTRAK KERJA')
                ->setCellValue('I2', 'NOMOR NPWP')
                ->setCellValue('J2', 'UMK 2019')
                ->setCellValue('K2', 'Status')
                ->setCellValue('L2', 'ANAK')
                ->setCellValue('M2', 'STATUS PEMBAYARAN BPJS')
                ->setCellValue('FM2', 'Nama Dept')
                ->setCellValue('FN2', 'STATUS KARYAWAN')
                ->setCellValue('FO2', 'SITE PROJECT')
                ->setCellValue('FP2', 'GAJI')
                ->setCellValue('FR2', 'COST CODE')
                ->setCellValue('FS2', '')
                ->setCellValue('FT2', '')
                ->setCellValue('FU2', '')
                ->setCellValue('FV2', '')
                ->setCellValue('FW2', '')
                ->setCellValue('FX2', '')
                ->setCellValue('FY2', '')
                ->setCellValue('FZ2', '')
                ->setCellValue('GA2', '')
                ->setCellValue('GB2', 'THR')
                ->setCellValue('GC2', 'NAMA BANk')
                ->setCellValue('GD2', 'NOMOR REKENING')
                ->setCellValue('GE2', 'ADJUSMENT')
                ->setCellValue('GE4', 'KETERANGAN')
                ->setCellValue('GF4', 'JUMLAH')
                ->setCellValue('GG2', 'Deducation')
                ->setCellValue('GG4', 'KETERANGAN')
                ->setCellValue('GH4', 'JUMLAH')

                // ->setCellValue('N6', 'DANA KONPENSASI')
                // ->setCellValue('O6', 'JKK/JKM')
                // ->setCellValue('P6', 'BPJS KES 5%')
                // ->setCellValue('Q6', 'JHT & JP')
                // ->setCellValue('R6', 'PPH 21')
                // ->setCellValue('S6', 'ADJ')
                // ->setCellValue('T6', 'TOTAL')
                // ->setCellValue('U6', 'ROUNDED')
                // ->setCellValue('V6', 'GAJI')
                // ->setCellValue('W6', 'KETERANGAN')
                ;

                $rowIdx = 4;
                $startIdx = $rowIdx; 
                $rowNo = 0;
                $totalbasic = 0;
                $totalott = 0;
                $totalnshift = 0;
                $totaljkkjkm  = 0;
                $totalshift = 0;
                $totalbpjs1 = 0;
                $totalthr = 0;
                $totaldanakonpen = 0;
                $totaljkkjkm = 0;
                $totaljkkjkm1 = 0;
                $totalbpjs = 0;
                $totaljhtjp = 0;
                $totalpph21 = 0;
                $totaladjustmen = 0;
                $totaltotal = 0;
                $totalround = 0;
                $totalgaaji = 0;
        foreach ($data as $row) {
            $rowIdx++;
            $rowNo++;
            $startDate = $row['start_date'];
            $monthProcess = $row['month_process'];
            $yearProcess = $row['year_process'];
            $start = $yearProcess.'-'.$monthProcess.'-'.$startDate;

            $dayss = 31;
            $umk = 0;
            $endDate = date('Y-m-d', strtotime('+'.$dayss.'days', strtotime($start)));
            $status = $row['status_payroll'];
            $anak = 0;
            $Mstatus = '';
            $statuss = $row['marital_status'];
            if ($status == 'daily') {
                $umk = $row['daily'];
            } else if ($status == 'monthly'){
                $umk = $row['monthly'];
            }
            if ($statuss == 'TK0') {
                $Mstatus = 'TK';
                $anak = 0;
            } elseif ($statuss == 'K0') {
                $Mstatus = 'K';
                $anak = 0;
            } elseif ($statuss == 'K1') {
                $Mstatus = 'K';
                $anak = 1;
            } elseif ($statuss == 'K2') {
                $Mstatus = 'K';
                $anak = 2;
            } elseif ($statuss == 'K3') {
                $Mstatus = 'K';
                $anak = 3;
            } 
            $bpjsCheck = $row['bpjs_check'];
            if ($bpjsCheck == 1) {
                $statusBpjs = 'YA+';
            } else {
                $statusBpjs = 'TIDAK';
            }
            $workTotal = $row['work_total'];
            

        


            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.$rowIdx, $rowNo)
                ->setCellValue('B'.$rowIdx, $start.'  -  '.$endDate) 
                ->setCellValue('C'.$rowIdx, $row['full_name'])
                ->setCellValue('D'.$rowIdx, '')
                ->setCellValue('E'.$rowIdx, $row['position'])
                ->setCellValue('F'.$rowIdx, $row['client_name'])
                ->setCellValue('G'.$rowIdx, $row['join_date'])
                ->setCellValue('H'.$rowIdx, $row['end_date'])
                ->setCellValue('I'.$rowIdx, $row['tax_no'])
                ->setCellValue('J'.$rowIdx, $umk)
                ->setCellValue('K'.$rowIdx, $Mstatus)
                ->setCellValue('L'.$rowIdx, $anak)
                ->setCellValue('M'.$rowIdx, $statusBpjs)
                ->setCellValue('N'.$rowIdx,$row["in01"])
                ->setCellValue('S'.$rowIdx,$row["in02"])
                ->setCellValue('X'.$rowIdx,$row["in03"])
                ->setCellValue('AC'.$rowIdx,$row["in04"])
                ->setCellValue('AH'.$rowIdx,$row["in05"])
                ->setCellValue('AM'.$rowIdx,$row["in06"])
                ->setCellValue('AR'.$rowIdx,$row["in07"])
                ->setCellValue('AW'.$rowIdx,$row["in08"])
                ->setCellValue('BB'.$rowIdx,$row["in09"])
                ->setCellValue('BG'.$rowIdx,$row["in10"])
                ->setCellValue('BL'.$rowIdx,$row["in11"])
                ->setCellValue('BQ'.$rowIdx,$row["in12"])
                ->setCellValue('BV'.$rowIdx,$row["in13"])
                ->setCellValue('CA'.$rowIdx,$row["in14"])
                ->setCellValue('CF'.$rowIdx,$row["in15"])
                ->setCellValue('CK'.$rowIdx,$row["in16"])
                ->setCellValue('CP'.$rowIdx,$row["in17"])
                ->setCellValue('CU'.$rowIdx,$row["in18"])
                ->setCellValue('CZ'.$rowIdx,$row["in19"])
                ->setCellValue('DE'.$rowIdx,$row["in20"])
                ->setCellValue('DJ'.$rowIdx,$row["in21"])
                ->setCellValue('DO'.$rowIdx,$row["in22"])
                ->setCellValue('DT'.$rowIdx,$row["in23"])
                ->setCellValue('DY'.$rowIdx,$row["in24"])
                ->setCellValue('ED'.$rowIdx,$row["in25"])
                ->setCellValue('EI'.$rowIdx,$row["in26"])
                ->setCellValue('EN'.$rowIdx,$row["in27"])
                ->setCellValue('ES'.$rowIdx,$row["in28"])
                ->setCellValue('EX'.$rowIdx,$row["in29"])
                ->setCellValue('FC'.$rowIdx,$row["in30"])
                ->setCellValue('FH'.$rowIdx,$row["in31"])
                ;
        

        for ($x = 15; $x <= 169; $x+=5) {
            if ($x < 27) {
             $hasil = $alphabet[$x];
            }
            else if ($x > 26 && $x < 53) {
             $y = $x - 26;
             $hasil = 'A'.$alphabet[$y];
            }  else if ($x > 52 && $x < 79){
                $z = $x - 52;
                $hasil = 'B'.$alphabet[$z];
            } else if ($x > 78 && $x < 105) { 
                $AA = $x - 78;
                $hasil = 'C'.$alphabet[$AA];
            } else if ($x > 104 && $x < 131) {
                $AB = $x - 104;
                $hasil = 'D'.$alphabet[$AB];
            } else if ($x > 130 && $x < 157) {
                $AC = $x - 130;
                $hasil = 'E'.$alphabet[$AC];
            } else if ($x > 156 && $x < 183) {
                $AD = $x - 156;
                $hasil = 'F'.$alphabet[$AD];
            }
            $spreadsheet->getActiveSheet()
                ->setCellValue($hasil.$rowIdx, '')
                ;
        }

        for ($x = 16; $x <= 169; $x+=5) {
            if ($x < 27) {
             $hasil = $alphabet[$x];
            }
            else if ($x > 26 && $x < 53) {
             $y = $x - 26;
             $hasil = 'A'.$alphabet[$y];
            }  else if ($x > 52 && $x < 79){
                $z = $x - 52;
                $hasil = 'B'.$alphabet[$z];
            } else if ($x > 78 && $x < 105) { 
                $AA = $x - 78;
                $hasil = 'C'.$alphabet[$AA];
            } else if ($x > 104 && $x < 131) {
                $AB = $x - 104;
                $hasil = 'D'.$alphabet[$AB];
            } else if ($x > 130 && $x < 157) {
                $AC = $x - 130;
                $hasil = 'E'.$alphabet[$AC];
            } else if ($x > 156 && $x < 183) {
                $AD = $x - 156;
                $hasil = 'F'.$alphabet[$AD];
            }
            $spreadsheet->getActiveSheet()
                ->setCellValue($hasil.$rowIdx, '')
                ;
        }

        for ($x = 17; $x <= 169; $x+=5) {
            if ($x < 27) {
             $hasil = $alphabet[$x];
            }
            else if ($x > 26 && $x < 53) {
             $y = $x - 26;
             $hasil = 'A'.$alphabet[$y];
            }  else if ($x > 52 && $x < 79){
                $z = $x - 52;
                $hasil = 'B'.$alphabet[$z];
            } else if ($x > 78 && $x < 105) { 
                $AA = $x - 78;
                $hasil = 'C'.$alphabet[$AA];
            } else if ($x > 104 && $x < 131) {
                $AB = $x - 104;
                $hasil = 'D'.$alphabet[$AB];
            } else if ($x > 130 && $x < 157) {
                $AC = $x - 130;
                $hasil = 'E'.$alphabet[$AC];
            } else if ($x > 156 && $x < 183) {
                $AD = $x - 156;
                $hasil = 'F'.$alphabet[$AD];
            }
            $spreadsheet->getActiveSheet()
                ->setCellValue($hasil.$rowIdx, '')
                ;
        }

        for ($x = 18; $x <= 169; $x+=5) {
            if ($x < 27) {
             $hasil = $alphabet[$x];
            }
            else if ($x > 26 && $x < 53) {
             $y = $x - 26;
             $hasil = 'A'.$alphabet[$y];
            }  else if ($x > 52 && $x < 79){
                $z = $x - 52;
                $hasil = 'B'.$alphabet[$z];
            } else if ($x > 78 && $x < 105) { 
                $AA = $x - 78;
                $hasil = 'C'.$alphabet[$AA];
            } else if ($x > 104 && $x < 131) {
                $AB = $x - 104;
                $hasil = 'D'.$alphabet[$AB];
            } else if ($x > 130 && $x < 157) {
                $AC = $x - 130;
                $hasil = 'E'.$alphabet[$AC];
            } else if ($x > 156 && $x < 183) {
                $AD = $x - 156;
                $hasil = 'F'.$alphabet[$AD];
            }
            $spreadsheet->getActiveSheet()
                ->setCellValue($hasil.$rowIdx, '')
                ;
        }

            $spreadsheet->getActiveSheet()->getStyle("A".$rowIdx.":GH".$rowIdx)->applyFromArray($outlineBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("A".$rowIdx.":GH".$rowIdx)->applyFromArray($allBorderStyle);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rowIdx, $rowNo);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $rowIdx, $row['name']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $rowIdx, $row['job_desc']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $rowIdx, $row['basic_salary']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $rowIdx, $row['rate']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $rowIdx, $row['normal_time']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $rowIdx, $row['ot_count1']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $rowIdx, $row['ot_count2']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $rowIdx, $row['ot_count3']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $rowIdx, $row['ot_count4']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $rowIdx, $row['worked_hours']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $rowIdx, $row['current_salary']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $rowIdx, $row['ot_total']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $rowIdx, $row['travel_bonus']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $rowIdx, $gross);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $rowIdx, $row['uplift']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $rowIdx, '=IF(K'.$rowIdx.'<=312,((E'.$rowIdx.'*K'.$rowIdx.')*64%),((E'.$rowIdx.'*312)*64%))');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $rowIdx, '1');
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $rowIdx, $chargeTotal);
            /* SET ROW COLOR */
            if($rowIdx % 2 == 1)
            {
                $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':GH'.$rowIdx)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('EAEBAF');             
            } 
        }

        /* SET NUMBERS FORMAT*/
        
        unset($allBorderStyle);
        unset($center);
        unset($right);
        unset($left);
        
        $spreadsheet->setActiveSheetIndex(0);

        $str = 'AGRSummary';
        $fileName = preg_replace('/\s+/', '', $str);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
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