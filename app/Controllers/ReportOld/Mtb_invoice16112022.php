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

     public function exportSummaryPayrollPontilSwq($yearPeriod, $sm, $monthPeriod,  $dept)
    {
        // Create new Spreadsheet object
        $summary = new M_tr_timesheet();
        $depart = str_replace("%20"," ",$dept);
        $summary->setYearProcess($yearPeriod);
        $summary->setMonthProcess($monthPeriod);
	$summary->setpayrollGroup($sm);
        $summary->setdept($depart);
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
	$spreadsheet->getActiveSheet()->mergeCells("A3:W3");
        $spreadsheet->getActiveSheet()->getStyle("A2:W2")->applyFromArray($center);
	$spreadsheet->getActiveSheet()->getStyle("A3:W3")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true)->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle("A2:D2")->getFont()->setBold(true)->setSize(13);
	$spreadsheet->getActiveSheet()->getStyle("A3:D3")->getFont()->setBold(true)->setSize(13);
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
	$jkkjkm1 = 0;
	$empbpjs1 = 0;
	$jhtjp1 = 0;
	$pph211 = 0;

	

        foreach ($data as $row) {
        	$totalsementara = $row['totalgaji'];
            $rowIdx++;
            $rowNo++;
            $total = $totalsementara + $row['thr'] + $row['allowance_03'] + $row['adjustment'] - $row['pph21'] - $row['emp_bpjs'] - $row['jhtjp'] - $row['jkkjkm'];
        $jkkjkm = $row['jkkjkm'];
	$empbpjs = $row['emp_bpjs'];
	$jhtjp = $row['jhtjp'];
	$pph21 = $row['pph21'];
	if ($jkkjkm != 0){
		$jkkjkm1 = '-'.$row['jkkjkm'];
	}
	if ($empbpjs != 0){
		$empbpjs1 = '-'.$row['emp_bpjs'];
	}
	if ($jhtjp != 0){
		$jhtjp1 = '-'.$row['jhtjp'];
	}
	if ($pph21 != 0){
		$pph211= '-'.$row['pph21'];
	}

        


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
                ->setCellValue('O'.$rowIdx, $jkkjkm1)
                ->setCellValue('P'.$rowIdx, $empbpjs1)
                ->setCellValue('Q'.$rowIdx, $jhtjp1)
                ->setCellValue('R'.$rowIdx, $pph211)
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
	    $payrollGroup = $row['payroll_group'];

        
        $spreadsheet->getActiveSheet()
		->setCellValue('A1', $payrollGroup.'/'.$monthPeriod.'/'.$yearPeriod) 
                ->setCellValue('A2', 'SUMMARY PAYMENT PT SANGATI SOERYA SEJAHTERA - PT '.'AGINCOURT MARTABE RESOURCES')
                ->setCellValue('A3', 'DEPARTEMENT : '.$row['dept'])
                ->setCellValue('A5', 'DUE DATE : ');
	} /* end foreach ($query as $row) */
        $spreadsheet->getActiveSheet()
        	->setCellValue('G'.($rowIdx+2), $totalbasic)
       		->setCellValue('H'.($rowIdx+2), $totalott)
        	->setCellValue('I'.($rowIdx+2), $totalnshift)
        	->setCellValue('J'.($rowIdx+2), $totaljkkjkm)
        	->setCellValue('K'.($rowIdx+2), $totalshift)
        	->setCellValue('L'.($rowIdx+2), $totalbpjs1)
        	->setCellValue('M'.($rowIdx+2), $totalthr)
        	->setCellValue('N'.($rowIdx+2), $totaldanakonpen)
        	->setCellValue('O'.($rowIdx+2), $totaljkkjkm1)
        	->setCellValue('P'.($rowIdx+2), $totalbpjs)
        	->setCellValue('Q'.($rowIdx+2), $totaljhtjp)
        	->setCellValue('R'.($rowIdx+2), $totalpph21)
        	->setCellValue('S'.($rowIdx+2), $totaladjustmen)
        	->setCellValue('T'.($rowIdx+2), $totaltotal)
        	->setCellValue('U'.($rowIdx+2), $totalround)
            ->setCellValue('A'.($rowIdx+2), 'TOTAL')
            ->setCellValue('V'.($rowIdx+2), $totalgaaji)
            ;
       

        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, ($rowIdx+2), "TOTAL");
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(16, ($rowIdx+2), "=SUM(Q".$startIdx.":Q".$rowIdx.")");
        $spreadsheet->getActiveSheet()->getStyle('G8:W'.($rowIdx+2))->getNumberFormat()->setFormatCode('#,##0.00');       
        $spreadsheet->getActiveSheet()->mergeCells("A".($rowIdx+2).":F".($rowIdx+2));
	$spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+2).":W".($rowIdx+2))->applyFromArray($outlineBorderStyle);
 	$spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+2).":W".($rowIdx+2))->applyFromArray($allBorderStyle);
	$spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+2).":W".($rowIdx+2))->applyFromArray($center);


        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+2).":W".($rowIdx+2))
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B');

        /* SET NUMBERS FORMAT*/
        
        unset($allBorderStyle);
        unset($center);
        unset($right);
        unset($left);
       
	$spreadsheet->getActiveSheet()->setTitle($sm.'-'.$monthPeriod.'-'.$yearPeriod.'PaymentSummary');
       

        $str = $sm.$monthPeriod.$yearPeriod.'PaymentSummary';
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
            ->setCellValue('A3', $sm.'/'.$monthPeriod.'/'.$yearPeriod );
            //->setCellValue('A4', 'Periode : '.$monthPeriod.'-'.$yearPeriod);

        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true)->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle("A2:D2")->getFont()->setBold(true)->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle("A3:D3")->getFont()->setBold(true)->setSize(13);
	//$spreadsheet->getActiveSheet()->getStyle("A4:D4")->getFont()->setBold(true)->setSize(13);
        // $spreadsheet->getActiveSheet()->getStyle("A4:G4")->getFont()->setBold(true)->setSize(12); 

        $spreadsheet->getActiveSheet()
            ->mergeCells("A1:I1")
            ->mergeCells("A2:I2")
            ->mergeCells("A3:I3");

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

        $spreadsheet->getActiveSheet()->setTitle($sm.'-'.$monthPeriod.'-'.$yearPeriod.'PaymentList');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        //Nama File
        // $str = $rowData['name'].$rowData['bio_rec_id'];
        $str = $sm.$monthPeriod.$yearPeriod.'PaymentList';
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

    public function getPayrolldeptList($tahun,$bulan,$sm,$dept)
    {
	$depart = str_replace("%20"," ",$dept);
        $data_proses = new M_tr_timesheet();
        $data_proses->setMonthProcess($bulan);
        $data_proses->setYearProcess($tahun);
	$data_proses->setpayrollGroup($sm);
	$data_proses->setdept($depart);
        $data = $data_proses->getAlldeptData();
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
            ->setCellValue('A2', 'INVOICE PAYROLL PT. '.strtoupper('AGINCOURT').'')
            ->setCellValue('A3', $sm.'/'.$monthPeriod.'/'.$yearPeriod );

	$spreadsheet->getActiveSheet()->getStyle("A1:R1")->applyFromArray($center);
	$spreadsheet->getActiveSheet()->getStyle("A2:R2")->applyFromArray($center);
	$spreadsheet->getActiveSheet()->getStyle("A4:R4")->applyFromArray($center);
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
            ->setCellValue('C6', 'Cost Code')
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
                ->setCellValue('C'.($rowIdx), '')
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
        $spreadsheet->getActiveSheet()->setTitle($payrollGroup.'-'.$monthPeriod.'-'.$yearPeriod.'Invoice');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        $str = $payrollGroup.'-'.$monthPeriod.'-'.$yearPeriod.'PaymentList';
        $fileName = preg_replace('/\s+/', '', $str);
        // $str = 'PTLSmbInvoice';
        // $fileName = 'Invoice Payroll PT.'.$ptName.'';
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

	
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath('./assets/images/logo.png');
            $drawing->setCoordinates('A4');
            $drawing->setHeight(36);
            $drawing->setWorksheet($spreadsheet->getActiveSheet());
        
	
        
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

        $data = $summary->formlist();
        
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
	$spreadsheet->getActiveSheet()
            ->mergeCells("A4:F4")
            ->mergeCells("A5:F5")
;
	$spreadsheet->getActiveSheet()->getStyle("A4:F4")->applyFromArray($center);
	$spreadsheet->getActiveSheet()->getStyle("A5:F5")->applyFromArray($center);


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
            ->setCellValue('F6', 'Total Pembayaran');  
            

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
        $spreadsheet->getActiveSheet()->setTitle($sm.'-'.$monthPeriod.'-'.$yearPeriod.'Payment Form');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        $str = $sm.'-'.$monthPeriod.'-'.$yearPeriod.'PaymentForm';
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

    
    
   


} 