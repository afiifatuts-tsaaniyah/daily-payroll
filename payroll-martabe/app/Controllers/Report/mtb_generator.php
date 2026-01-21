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
use App\Models\Transaction\M_tr_overd;
use App\Models\Transaction\M_tr_slip;
use App\Models\Master\M_dept;
use App\Models\Transaction\M_rp_db;


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
        // echo ($dept);
        // exit();
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
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $rowIdx, $row['normal_d']);
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
            ->setCellValue('G6', 'Over d dalam 1 (Satu) Bulan (Rp)')
            ->setCellValue('H6', 'Tunjangan Shift (Rp)')
            ->setCellValue('I6', 'TOTAL Gaji, Overd Dan Tunjangan Shift (Rp)')
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
        $overdall = 0;
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
            $overd = $row['overd'];
            $tunjangan = $row['tunjangan'];
            $bpjsTNK = $totalempl * $gajipokok * 0.0574;
            $bpjsKSH = $totalempl * $gajipokok * 0.04;
            $bpjsPNS = $totalempl * $gajipokok * 0.02;
            $total = $gajipokok + $overd + $tunjangan;
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
                ->setCellValue('G'.($rowIdx), $row['overd'])
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
            $overdall = $overdall + $overd;
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
                ->setCellValue('G'.($rowIdx+1), $overdall)
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

    public function exportDbForm($yearPeriod, $monthPeriod)
    {
        // Create new Spreadsheet object
        $monthend = $monthPeriod + 1;
        $yearend = $yearPeriod;
        if ($monthend > 12) {
            $monthend = $monthend - 12;
            $yearend = $yearPeriod + 1;
        }
        if ($monthend < 10) {
            $monthend = '0'.$monthend;
        }
        $startDate = $yearPeriod.'-'.$monthPeriod.'-16';
        $endDate = $yearend.'-'.$monthend.'-15';
        // echo $startDate.'-'.$endDate;
        
        $rpDb = new M_rp_db();
        $rpDb->setstartDate($startDate);
        $rpDb->setendDate($endDate);
        // $dataDbDate = $rpDb->getDataDbByDate();
        $dataId = $rpDb->getSlipIdByDate(); 

        $tgl3 = strtotime($startDate); 
        $tgl4 = strtotime($endDate); 
        $jarak = $tgl3 - $tgl4;
        $hari = $jarak / 60 / 60 / 24;
        $hari = str_replace("-","",$hari);
        $daycount =  $hari*5+18;
        $excelAlpahbet = array('EX','EY','EZ','FA','FB','FC','FD','FE','FF','FG','FH','FI','FJ','FK','FL','FM','FN','FO','FP','FQ','FR','FS','FT','FU','FV','FW','FX','FY','FZ','FZ','GA','GB','GC','GD','GE','GF','GG','GH','GI','GJ','GK','GL','GM','GN','GO','GP','GQ');

        $numberRow = $daycount - 153;
        // $tanggal = 0;
        // for ($x = 14; $x <= $dayCount; $x+=5)
        // {
        // echo $tanggal;
        // $tanggal++;
        // }
        // echo $hari;
        // exit();
        

        
        //     foreach ($data as $dataa) {
        //         dd($dataa);
        //     }

        // $summary = new M_tr_timesheet();
        // echo $depart;
        // exit();
        // $summary->setYearProcess($yearPeriod);
        // $summary->setMonthProcess($monthPeriod);
        // $summary->setpayrollGroup($sm);
        // $summary->setstartDate($startDate);
        // // echo ($dept);
        // // exit();
        // $data = $summary->PrintDB();
        // exit();
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
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow]."2:".$excelAlpahbet[$numberRow]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+1]."2:".$excelAlpahbet[$numberRow+1]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+2]."2:".$excelAlpahbet[$numberRow+2]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+3]."2:".$excelAlpahbet[$numberRow+3]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+4]."2:".$excelAlpahbet[$numberRow+4]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+5]."2:".$excelAlpahbet[$numberRow+5]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+6]."2:".$excelAlpahbet[$numberRow+6]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+7]."2:".$excelAlpahbet[$numberRow+7]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+8]."2:".$excelAlpahbet[$numberRow+8]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+9]."2:".$excelAlpahbet[$numberRow+9]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+10]."2:".$excelAlpahbet[$numberRow+10]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+11]."2:".$excelAlpahbet[$numberRow+11]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+12]."2:".$excelAlpahbet[$numberRow+12]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+13]."2:".$excelAlpahbet[$numberRow+13]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+14]."2:".$excelAlpahbet[$numberRow+14]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+15]."2:".$excelAlpahbet[$numberRow+15]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+16]."2:".$excelAlpahbet[$numberRow+16]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+17]."2:".$excelAlpahbet[$numberRow+17]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+18]."2:".$excelAlpahbet[$numberRow+18]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+19]."2:".$excelAlpahbet[$numberRow+19]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+20]."2:".$excelAlpahbet[$numberRow+20]."4")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+21]."2:".$excelAlpahbet[$numberRow+21]."4")->applyFromArray($allBorderStyle);


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
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow]."2:".$excelAlpahbet[$numberRow]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+1]."2:".$excelAlpahbet[$numberRow+1]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+2]."2:".$excelAlpahbet[$numberRow+2]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+3]."2:".$excelAlpahbet[$numberRow+3]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+4]."2:".$excelAlpahbet[$numberRow+4]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+5]."2:".$excelAlpahbet[$numberRow+5]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+6]."2:".$excelAlpahbet[$numberRow+6]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+7]."2:".$excelAlpahbet[$numberRow+7]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+8]."2:".$excelAlpahbet[$numberRow+8]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+9]."2:".$excelAlpahbet[$numberRow+9]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+10]."2:".$excelAlpahbet[$numberRow+10]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+11]."2:".$excelAlpahbet[$numberRow+11]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+12]."2:".$excelAlpahbet[$numberRow+12]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+13]."2:".$excelAlpahbet[$numberRow+13]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+14]."2:".$excelAlpahbet[$numberRow+14]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+15]."2:".$excelAlpahbet[$numberRow+15]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+16]."2:".$excelAlpahbet[$numberRow+16]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+17]."2:".$excelAlpahbet[$numberRow+17]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+18]."2:".$excelAlpahbet[$numberRow+18]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+19]."2:".$excelAlpahbet[$numberRow+19]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+20]."2:".$excelAlpahbet[$numberRow+20]."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+21]."2:".$excelAlpahbet[$numberRow+21]."4")->applyFromArray($center);

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
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow]."2:".$excelAlpahbet[$numberRow]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+1]."2:".$excelAlpahbet[$numberRow+1]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+2]."2:".$excelAlpahbet[$numberRow+2]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+3]."2:".$excelAlpahbet[$numberRow+3]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+4]."2:".$excelAlpahbet[$numberRow+4]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+5]."2:".$excelAlpahbet[$numberRow+5]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+6]."2:".$excelAlpahbet[$numberRow+6]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+7]."2:".$excelAlpahbet[$numberRow+7]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+8]."2:".$excelAlpahbet[$numberRow+8]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+9]."2:".$excelAlpahbet[$numberRow+9]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+10]."2:".$excelAlpahbet[$numberRow+10]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+11]."2:".$excelAlpahbet[$numberRow+11]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+12]."2:".$excelAlpahbet[$numberRow+12]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+13]."2:".$excelAlpahbet[$numberRow+13]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+14]."2:".$excelAlpahbet[$numberRow+14]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+15]."2:".$excelAlpahbet[$numberRow+15]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+16]."2:".$excelAlpahbet[$numberRow+16]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+17]."2:".$excelAlpahbet[$numberRow+17]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+18]."2:".$excelAlpahbet[$numberRow+18]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+19]."2:".$excelAlpahbet[$numberRow+19]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+20]."2:".$excelAlpahbet[$numberRow+20]."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($excelAlpahbet[$numberRow+21]."2:".$excelAlpahbet[$numberRow+21]."4")->getAlignment()->setWrapText(true);

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
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow]."2:".$excelAlpahbet[$numberRow]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+1]."2:".$excelAlpahbet[$numberRow+1]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+2]."2:".$excelAlpahbet[$numberRow+2]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+3]."2:".$excelAlpahbet[$numberRow+3]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+4]."2:".$excelAlpahbet[$numberRow+4]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+5]."2:".$excelAlpahbet[$numberRow+5]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+6]."2:".$excelAlpahbet[$numberRow+6]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+7]."2:".$excelAlpahbet[$numberRow+7]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+8]."2:".$excelAlpahbet[$numberRow+8]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+9]."2:".$excelAlpahbet[$numberRow+9]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+10]."2:".$excelAlpahbet[$numberRow+10]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+11]."2:".$excelAlpahbet[$numberRow+11]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+12]."2:".$excelAlpahbet[$numberRow+12]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+13]."2:".$excelAlpahbet[$numberRow+13]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+14]."2:".$excelAlpahbet[$numberRow+14]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+15]."2:".$excelAlpahbet[$numberRow+15]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+16]."2:".$excelAlpahbet[$numberRow+16]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+17]."2:".$excelAlpahbet[$numberRow+17]."4");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+18]."2:".$excelAlpahbet[$numberRow+19]."3");
        $spreadsheet->getActiveSheet()->mergeCells($excelAlpahbet[$numberRow+20]."2:".$excelAlpahbet[$numberRow+21]."3");

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
         $spreadsheet->getActiveSheet()->getStyle('A2:'.$excelAlpahbet[$numberRow+21].'2')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B'); 

            $tanggal = 0;
            $startDate = 16;

        $spreadsheet->getActiveSheet()
                ->setCellValue('A1', 'PERHITUNGAN GAJI PER BULAN');
        $alphabet =  
        array('','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        for ($x = 14; $x <= $daycount; $x+=5) {
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
        $tgl = $startDate+$tanggal;
        if ($tgl > cal_days_in_month(CAL_GREGORIAN, $monthPeriod, $yearPeriod)) {
            $tgl = $startDate+$tanggal - cal_days_in_month(CAL_GREGORIAN, $monthPeriod, $yearPeriod);
            $monthNow = $monthPeriod +1;
        }else{
            $tgl = $startDate+$tanggal;
            $monthNow = $monthPeriod;
        }
        $yearsnow = $yearPeriod;
        if ($monthNow == 13) {
            $yearsnow = $yearPeriod +1;
            $monthNow = 1;
        } else {
            $yearsnow = $yearPeriod;
            // $monthNow = 1;
        }
        $spreadsheet->getActiveSheet()
                ->setCellValue($hasil.'2', $tgl.'-'. $monthNow.'-'.$yearsnow)
                ->setCellValue($hasil.'4', 'IN');
        $spreadsheet->getActiveSheet()->mergeCells($hasil."2:".$hasil."3");
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->applyFromArray($allBorderStyle);
       

        /* START INVOICE TITLE */
        $spreadsheet->getActiveSheet()->getStyle('A2:'.$hasil.'2')->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->mergeCells('A1:'.$hasil.'1');
        $tanggal++;
        }
        $tanggal = 0;
        for ($x = 15; $x <= $daycount; $x+=5) {
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
        
        $tgl = $startDate+$tanggal;
        if ($tgl > cal_days_in_month(CAL_GREGORIAN, $monthPeriod, $yearPeriod)) {
            $tgl = $startDate+$tanggal - cal_days_in_month(CAL_GREGORIAN, $monthPeriod, $yearPeriod);
            $monthNow = $monthPeriod +1;
        }else{
            $tgl = $startDate+$tanggal;
            $monthNow = $monthPeriod;
        }
        $yearsnow = $yearPeriod;
        if ($monthNow == 13) {
            $yearsnow = $yearPeriod +1;
            $monthNow = 1;
        } else {
            $yearsnow = $yearPeriod;
            // $monthNow = 1;
        }
        $yearss = $tgl.'-'. $monthNow.'-'.$yearsnow;
        
        $day = date('D', strtotime($yearss));
            $dayList = array(
                'Sun' => 'Minggu',
                'Mon' => 'Senin',
                'Tue' => 'Selasa',
                'Wed' => 'Rabu',
                'Thu' => 'Kamis',
                'Fri' => 'Jumat',
                'Sat' => 'Sabtu'
            );
        $rdData =  $dayList[$day];
        $spreadsheet->getActiveSheet()
                ->setCellValue($hasil.'2', $rdData)
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
        $tanggal++;
        }
        for ($x = 16; $x <= $daycount; $x+=5) {
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
        for ($x = 17; $x <= $daycount; $x+=5) {
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
        for ($x = 18; $x <= $daycount; $x+=5) {
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
        
        
        // echo $excelAlpahbet[$numberRow+1];
        // exit();

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
                ->setCellValue($excelAlpahbet[$numberRow].'2', 'Nama Dept')
                ->setCellValue($excelAlpahbet[$numberRow+1].'2', 'STATUS KARYAWAN')
                ->setCellValue($excelAlpahbet[$numberRow+2].'2', 'SITE PROJECT')
                ->setCellValue($excelAlpahbet[$numberRow+3].'2', 'GAJI')
                ->setCellValue($excelAlpahbet[$numberRow+4].'2', '')
                ->setCellValue($excelAlpahbet[$numberRow+5].'2', 'COST CODE')
                ->setCellValue($excelAlpahbet[$numberRow+6].'2', '')
                ->setCellValue($excelAlpahbet[$numberRow+7].'2', '')
                ->setCellValue($excelAlpahbet[$numberRow+8].'2', '')
                ->setCellValue($excelAlpahbet[$numberRow+9].'2', '')
                ->setCellValue($excelAlpahbet[$numberRow+10].'2', '')
                ->setCellValue($excelAlpahbet[$numberRow+11].'2', '')
                ->setCellValue($excelAlpahbet[$numberRow+12].'2', '')
                ->setCellValue($excelAlpahbet[$numberRow+13].'2', '')
                ->setCellValue($excelAlpahbet[$numberRow+14].'2', '')
                ->setCellValue($excelAlpahbet[$numberRow+15].'2', 'THR')
                ->setCellValue($excelAlpahbet[$numberRow+16].'2', 'NAMA BANK')
                ->setCellValue($excelAlpahbet[$numberRow+17].'2', 'NOMOR REKENING')
                ->setCellValue($excelAlpahbet[$numberRow+18].'2', 'ADJUSMENT')
                ->setCellValue($excelAlpahbet[$numberRow+18].'4', 'KETERANGAN')
                ->setCellValue($excelAlpahbet[$numberRow+19].'4', 'JUMLAH')
                ->setCellValue($excelAlpahbet[$numberRow+20].'2', 'Deducation')
                ->setCellValue($excelAlpahbet[$numberRow+20].'4', 'KETERANGAN')
                ->setCellValue($excelAlpahbet[$numberRow+21].'4', 'JUMLAH')

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
                $workDays01 = 0;
                $workDays02 = 0;
                $workDays03 = 0;
                $workDays04 = 0;
                $workDays05 = 0;
                $workDays06 = 0;
                $workDays07 = 0;
                $workDays08 = 0;
                $workDays09 = 0;
                $workDays10 = 0;
                $workDays11 = 0;
                $workDays12 = 0;
                $workDays13 = 0;
                $workDays14 = 0;
                $workDays15 = 0;
                $workDays16 = 0;
                $workDays17 = 0;
                $workDays18 = 0;
                $workDays19 = 0;
                $workDays20 = 0;
                $workDays21 = 0;
                $workDays22 = 0;
                $workDays23 = 0;
                $workDays24 = 0;
                $workDays25 = 0;
                $workDays26 = 0;
                $workDays27 = 0;
                $workDays28 = 0;
                $workDays29 = 0;
                $workDays30 = 0;
                $workDays31 = 0;
     foreach ($dataId as $row) {
            $slipId = $row['slip_id'];
            $data = $rpDb->getDataBySlipId($slipId);
            $dataDbDate = $rpDb->getDataDbByDate($slipId);
          
        foreach ($data as $row) {
            $rowIdx++;
            $rowNo++;
            $startDate = $row['slip_period'];
            $monthProcess = $row['month_period'];
            $yearProcess = $row['year_period'];
            $start = $yearProcess.'-'.$monthProcess.'-'.$startDate;
            foreach ($dataDbDate as $rowDate) {
                
                // $rowIds++;
           
        for ($x = 14; $x <= $daycount; $x+=5) {
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
        // $tgl = $startDate+$tanggal;
        // echo $tanggal.$startDate.'</br>';
        // if ($tgl > cal_days_in_month(CAL_GREGORIAN, $monthPeriod, $yearPeriod)) {
        //     $tgl = $startDate+$tanggal - cal_days_in_month(CAL_GREGORIAN, $monthPeriod, $yearPeriod);
        //     $monthNow = $monthPeriod +1;
        // }else{
        //     $tgl = $startDate+$tanggal;
        //     $monthNow = $monthPeriod;
        // }
        // $yearsnow = $yearPeriod;
        // if ($monthNow == 13) {
        //     $yearsnow = $yearPeriod +1;
        //     $monthNow = 1;
        // } else {
        //     $yearsnow = $yearPeriod;
        //     // $monthNow = 1;
        // }
        // echo $rowDate['in_db'];
        echo $hasil.'='.$rowIdx.'</br>';
        $spreadsheet->getActiveSheet()
                ->setCellValue($hasil.$rowIdx, $rowDate['in_db']);
        // $spreadsheet->getActiveSheet()
        //         ->setCellValue($hasil.'2', $tgl.'-'. $monthNow.'-'.$yearsnow)
        //         ->setCellValue($hasil.'4', 'IN');
        // $spreadsheet->getActiveSheet()->mergeCells($hasil."2:".$hasil."3");
        // $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->getAlignment()->setWrapText(true);
        // $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->applyFromArray($center);
        // $spreadsheet->getActiveSheet()->getStyle($hasil."2:".$hasil."4")->applyFromArray($allBorderStyle);
       

        /* START INVOICE TITLE */
        // $spreadsheet->getActiveSheet()->getStyle('A2:'.$hasil.'2')->getFont()->setSize(12);
        // $spreadsheet->getActiveSheet()->mergeCells('A1:'.$hasil.'1');
        // $tanggal++;
        }
     }  
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



            // $d01 = $row["d01"];
            // $d02 = $row["d02"];
            // $d03 = $row["d03"];
            // $d04 = $row["d04"];
            // $d05 = $row["d05"];
            // $d06 = $row["d06"];
            // $d07 = $row["d07"];
            // $d08 = $row["d08"];
            // $d09 = $row["d09"];
            // $d10 = $row["d10"];
            // $d11 = $row["d11"];
            // $d12 = $row["d12"];
            // $d13 = $row["d13"];
            // $d14 = $row["d14"];
            // $d15 = $row["d15"];
            // $d16 = $row["d16"];
            // $d17 = $row["d17"];
            // $d18 = $row["d18"];
            // $d19 = $row["d19"];
            // $d20 = $row["d20"];
            // $d21 = $row["d21"];
            // $d22 = $row["d22"];
            // $d23 = $row["d23"];
            // $d24 = $row["d24"];
            // $d25 = $row["d25"];
            // $d26 = $row["d26"];
            // $d27 = $row["d27"];
            // $d28 = $row["d28"];
            // $d29 = $row["d29"];
            // $d30 = $row["d30"];
            // $d31 = $row["d31"];

            //  $hours01 = substr($d01,2,4);
            //  $hours02 = substr($d02,2,4);
            //  $hours03 = substr($d03,2,4);
            //  $hours04 = substr($d04,2,4);
            //  $hours05 = substr($d05,2,4);
            //  $hours06 = substr($d06,2,4);
            //  $hours07 = substr($d07,2,4);
            //  $hours08 = substr($d08,2,4);
            //  $hours09 = substr($d09,2,4);
            //  $hours10 = substr($d10,2,4);
            //  $hours11 = substr($d11,2,4);
            //  $hours12 = substr($d12,2,4);
            //  $hours13 = substr($d13,2,4);
            //  $hours14 = substr($d14,2,4);
            //  $hours15 = substr($d15,2,4);
            //  $hours16 = substr($d16,2,4);
            //  $hours17 = substr($d17,2,4);
            //  $hours18 = substr($d18,2,4);
            //  $hours19 = substr($d19,2,4);
            //  $hours20 = substr($d20,2,4);
            //  $hours21 = substr($d21,2,4);
            //  $hours22 = substr($d22,2,4);
            //  $hours23 = substr($d23,2,4);
            //  $hours24 = substr($d24,2,4);
            //  $hours25 = substr($d25,2,4);
            //  $hours26 = substr($d26,2,4);
            //  $hours27 = substr($d27,2,4);
            //  $hours28 = substr($d28,2,4);
            //  $hours29 = substr($d29,2,4);
            //  $hours30 = substr($d30,2,4);
            //  $hours31 = substr($d31,2,4);

            //  $dayShift01 = substr($d01,0,2);
            //  $dayShift02 = substr($d02,0,2);
            //  $dayShift03 = substr($d03,0,2);
            //  $dayShift04 = substr($d04,0,2);
            //  $dayShift05 = substr($d05,0,2);
            //  $dayShift06 = substr($d06,0,2);
            //  $dayShift07 = substr($d07,0,2);
            //  $dayShift08 = substr($d08,0,2);
            //  $dayShift09 = substr($d09,0,2);
            //  $dayShift10 = substr($d10,0,2);
            //  $dayShift11 = substr($d11,0,2);
            //  $dayShift12 = substr($d12,0,2);
            //  $dayShift13 = substr($d13,0,2);
            //  $dayShift14 = substr($d14,0,2);
            //  $dayShift15 = substr($d15,0,2);
            //  $dayShift16 = substr($d16,0,2);
            //  $dayShift17 = substr($d17,0,2);
            //  $dayShift18 = substr($d18,0,2);
            //  $dayShift19 = substr($d19,0,2);
            //  $dayShift20 = substr($d20,0,2);
            //  $dayShift21 = substr($d21,0,2);
            //  $dayShift22 = substr($d22,0,2);
            //  $dayShift23 = substr($d23,0,2);
            //  $dayShift24 = substr($d24,0,2);
            //  $dayShift25 = substr($d25,0,2);
            //  $dayShift26 = substr($d26,0,2);
            //  $dayShift27 = substr($d27,0,2);
            //  $dayShift28 = substr($d28,0,2);
            //  $dayShift29 = substr($d29,0,2);
            //  $dayShift30 = substr($d30,0,2);
            //  $dayShift31 = substr($d31,0,2);

            // $timePh01 = substr($d01,0,2);
            // $timePh02 = substr($d02,0,2);
            // $timePh03 = substr($d03,0,2);
            // $timePh04 = substr($d04,0,2);
            // $timePh05 = substr($d05,0,2);
            // $timePh06 = substr($d06,0,2);
            // $timePh07 = substr($d07,0,2);
            // $timePh08 = substr($d08,0,2);
            // $timePh09 = substr($d09,0,2);
            // $timePh10 = substr($d10,0,2);
            // $timePh11 = substr($d11,0,2);
            // $timePh12 = substr($d12,0,2);
            // $timePh13 = substr($d13,0,2);
            // $timePh14 = substr($d14,0,2);
            // $timePh15 = substr($d15,0,2);
            // $timePh16 = substr($d16,0,2);
            // $timePh17 = substr($d17,0,2);
            // $timePh18 = substr($d18,0,2);
            // $timePh19 = substr($d19,0,2);
            // $timePh20 = substr($d20,0,2);
            // $timePh21 = substr($d21,0,2);
            // $timePh22 = substr($d22,0,2);
            // $timePh23 = substr($d23,0,2);
            // $timePh24 = substr($d24,0,2);
            // $timePh25 = substr($d25,0,2);
            // $timePh26 = substr($d26,0,2);
            // $timePh27 = substr($d27,0,2);
            // $timePh28 = substr($d28,0,2);
            // $timePh29 = substr($d29,0,2);
            // $timePh30 = substr($d30,0,2);
            // $timePh31 = substr($d31,0,2);

            




            //  = $row['work_total'];
            // $out1s = $row["in01"] ;
            // $out2s = $row["in02"] ;
            // $out3s = $row["in03"] ;
            // $out4s = $row["in04"] ;
            // $out5s = $row["in05"] ;
            // $out6s = $row["in06"] ;
            // $out7s = $row["in07"] ;
            // $out8s = $row["in08"] ;
            // $out9s = $row["in09"] ;
            // $out10s = $row["in10"] ;
            // $out11s = $row["in11"] ;
            // $out12s = $row["in12"] ;
            // $out13s = $row["in13"] ;
            // $out14s = $row["in14"] ;
            // $out15s = $row["in15"] ;
            // $out16s = $row["in16"] ;
            // $out17s = $row["in17"] ;
            // $out18s = $row["in18"] ;
            // $out19s = $row["in19"] ;
            // $out20s = $row["in20"] ;
            // $out21s = $row["in21"] ;
            // $out22s = $row["in22"] ;
            // $out23s = $row["in23"] ;
            // $out24s = $row["in24"] ;
            // $out25s = $row["in25"] ;
            // $out26s = $row["in26"] ;
            // $out27s = $row["in27"] ;
            // $out28s = $row["in28"] ;
            // $out29s = $row["in29"] ;
            // $out30s = $row["in30"] ;
            // $out31s = $row["in31"] ;


            // $out1= $out1s + $hours01 + 1;
            // $out2= $out2s + $hours02 + 1;
            // $out3= $out3s + $hours03 + 1;
            // $out4= $out4s + $hours04 + 1;
            // $out5= $out5s + $hours05 + 1;
            // $out6= $out6s + $hours06 + 1;
            // $out7= $out7s + $hours07 + 1;
            // $out8= $out8s + $hours08 + 1;
            // $out9= $out9s + $hours09 + 1;
            // $out10= $out10s + $hours10 + 1;
            // $out11= $out11s + $hours11 + 1;
            // $out12= $out12s + $hours12 + 1;
            // $out13= $out13s + $hours13 + 1;
            // $out14= $out14s + $hours14 + 1;
            // $out15= $out15s + $hours15 + 1;
            // $out16= $out16s + $hours16 + 1;
            // $out17= $out17s + $hours17 + 1;
            // $out18= $out18s + $hours18 + 1;
            // $out19= $out19s + $hours19 + 1;
            // $out20= $out20s + $hours20 + 1;
            // $out21= $out21s + $hours21 + 1;
            // $out22= $out22s + $hours22 + 1;
            // $out23= $out23s + $hours23 + 1;
            // $out24= $out24s + $hours24 + 1;
            // $out25= $out25s + $hours25 + 1;
            // $out26= $out26s + $hours26 + 1;
            // $out27= $out27s + $hours27 + 1;
            // $out28= $out28s + $hours28 + 1;
            // $out29= $out29s + $hours29 + 1;
            // $out30= $out30s + $hours30 + 1;
            // $out31= $out31s + $hours31 + 1;
            
           
        


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
                // ->setCellValue('N'.$rowIdx,$row["in01"])
                // ->setCellValue('S'.$rowIdx,$row["in02"])
                // ->setCellValue('X'.$rowIdx,$row["in03"])
                // ->setCellValue('AC'.$rowIdx,$row["in04"])
                // ->setCellValue('AH'.$rowIdx,$row["in05"])
                // ->setCellValue('AM'.$rowIdx,$row["in06"])
                // ->setCellValue('AR'.$rowIdx,$row["in07"])
                // ->setCellValue('AW'.$rowIdx,$row["in08"])
                // ->setCellValue('BB'.$rowIdx,$row["in09"])
                // ->setCellValue('BG'.$rowIdx,$row["in10"])
                // ->setCellValue('BL'.$rowIdx,$row["in11"])
                // ->setCellValue('BQ'.$rowIdx,$row["in12"])
                // ->setCellValue('BV'.$rowIdx,$row["in13"])
                // ->setCellValue('CA'.$rowIdx,$row["in14"])
                // ->setCellValue('CF'.$rowIdx,$row["in15"])
                // ->setCellValue('CK'.$rowIdx,$row["in16"])
                // ->setCellValue('CP'.$rowIdx,$row["in17"])
                // ->setCellValue('CU'.$rowIdx,$row["in18"])
                // ->setCellValue('CZ'.$rowIdx,$row["in19"])
                // ->setCellValue('DE'.$rowIdx,$row["in20"])
                // ->setCellValue('DJ'.$rowIdx,$row["in21"])
                // ->setCellValue('DO'.$rowIdx,$row["in22"])
                // ->setCellValue('DT'.$rowIdx,$row["in23"])
                // ->setCellValue('DY'.$rowIdx,$row["in24"])
                // ->setCellValue('ED'.$rowIdx,$row["in25"])
                // ->setCellValue('EI'.$rowIdx,$row["in26"])
                // ->setCellValue('EN'.$rowIdx,$row["in27"])
                // ->setCellValue('ES'.$rowIdx,$row["in28"])
                // ->setCellValue('EX'.$rowIdx,$row["in29"])
                // ->setCellValue('FC'.$rowIdx,$row["in30"])
                // ->setCellValue('FH'.$rowIdx,$row["in31"])
                ;


            // $spreadsheet->getActiveSheet()
            //     ->setCellValue('O'.$rowIdx,$out1)
            //     ->setCellValue('T'.$rowIdx,$out2)
            //     ->setCellValue('Y'.$rowIdx,$out3)
            //     ->setCellValue('AD'.$rowIdx,$out4)
            //     ->setCellValue('AI'.$rowIdx,$out5)
            //     ->setCellValue('AN'.$rowIdx,$out6)
            //     ->setCellValue('AS'.$rowIdx,$out7)
            //     ->setCellValue('AX'.$rowIdx,$out8)
            //     ->setCellValue('BC'.$rowIdx,$out9)
            //     ->setCellValue('BH'.$rowIdx,$out10)
            //     ->setCellValue('BM'.$rowIdx,$out11)
            //     ->setCellValue('BR'.$rowIdx,$out12)
            //     ->setCellValue('BW'.$rowIdx,$out13)
            //     ->setCellValue('CB'.$rowIdx,$out14)
            //     ->setCellValue('CG'.$rowIdx,$out15)
            //     ->setCellValue('CL'.$rowIdx,$out16)
            //     ->setCellValue('CQ'.$rowIdx,$out17)
            //     ->setCellValue('CV'.$rowIdx,$out18)
            //     ->setCellValue('DA'.$rowIdx,$out19)
            //     ->setCellValue('DF'.$rowIdx,$out20)
            //     ->setCellValue('DK'.$rowIdx,$out21)
            //     ->setCellValue('DP'.$rowIdx,$out22)
            //     ->setCellValue('DU'.$rowIdx,$out23)
            //     ->setCellValue('DZ'.$rowIdx,$out24)
            //     ->setCellValue('EE'.$rowIdx,$out25)
            //     ->setCellValue('EJ'.$rowIdx,$out26)
            //     ->setCellValue('EO'.$rowIdx,$out27)
            //     ->setCellValue('ET'.$rowIdx,$out28)
            //     ->setCellValue('EY'.$rowIdx,$out29)
            //     ->setCellValue('FD'.$rowIdx,$out30)
            //     ->setCellValue('FI'.$rowIdx,$out31)
            //     ->setCellValue("P".$rowIdx,$dayShift01)
            //     ->setCellValue("U".$rowIdx,$dayShift02)
            //     ->setCellValue("Z".$rowIdx,$dayShift03)
            //     ->setCellValue("AE".$rowIdx,$dayShift04)
            //     ->setCellValue("AJ".$rowIdx,$dayShift05)
            //     ->setCellValue("AO".$rowIdx,$dayShift06)
            //     ->setCellValue("AT".$rowIdx,$dayShift07)
            //     ->setCellValue("AY".$rowIdx,$dayShift08)
            //     ->setCellValue("BD".$rowIdx,$dayShift09)
            //     ->setCellValue("BI".$rowIdx,$dayShift10)
            //     ->setCellValue("BN".$rowIdx,$dayShift11)
            //     ->setCellValue("BS".$rowIdx,$dayShift12)
            //     ->setCellValue("BX".$rowIdx,$dayShift13)
            //     ->setCellValue("CC".$rowIdx,$dayShift14)
            //     ->setCellValue("CH".$rowIdx,$dayShift15)
            //     ->setCellValue("CM".$rowIdx,$dayShift16)
            //     ->setCellValue("CR".$rowIdx,$dayShift17)
            //     ->setCellValue("CW".$rowIdx,$dayShift18)
            //     ->setCellValue("DB".$rowIdx,$dayShift19)
            //     ->setCellValue("DG".$rowIdx,$dayShift20)
            //     ->setCellValue("DL".$rowIdx,$dayShift21)
            //     ->setCellValue("DQ".$rowIdx,$dayShift22)
            //     ->setCellValue("DV".$rowIdx,$dayShift23)
            //     ->setCellValue("EA".$rowIdx,$dayShift24)
            //     ->setCellValue("EF".$rowIdx,$dayShift25)
            //     ->setCellValue("EK".$rowIdx,$dayShift26)
            //     ->setCellValue("EP".$rowIdx,$dayShift27)
            //     ->setCellValue("EU".$rowIdx,$dayShift28)
            //     ->setCellValue("EZ".$rowIdx,$dayShift29)
            //     ->setCellValue("FE".$rowIdx,$dayShift30)
            //     ->setCellValue("FJ".$rowIdx,$dayShift31);

            // if ($timePh01 == "AS" || $timePh01 == "NS" || $timePh01 == "NA") {$roPh01 = ""; $workDays01 = 1; } else { $roPh01 = $timePh01;}
            // if ($timePh02 == "AS" || $timePh02 == "NS" || $timePh02 == "NA") {$roPh02 = ""; $workDays02 = 1; } else { $roPh02 = $timePh02;}
            // if ($timePh03 == "AS" || $timePh03 == "NS" || $timePh03 == "NA") {$roPh03 = ""; $workDays03 = 1; } else { $roPh03 = $timePh03;}
            // if ($timePh04 == "AS" || $timePh04 == "NS" || $timePh04 == "NA") {$roPh04 = ""; $workDays04 = 1; } else { $roPh04 = $timePh04;}
            // if ($timePh05 == "AS" || $timePh05 == "NS" || $timePh05 == "NA") {$roPh05 = ""; $workDays05 = 1; } else { $roPh05 = $timePh05;}
            // if ($timePh06 == "AS" || $timePh06 == "NS" || $timePh06 == "NA") {$roPh06 = ""; $workDays06 = 1; } else { $roPh06 = $timePh06;}
            // if ($timePh07 == "AS" || $timePh07 == "NS" || $timePh07 == "NA") {$roPh07 = ""; $workDays07 = 1; } else { $roPh07 = $timePh07;}
            // if ($timePh08 == "AS" || $timePh08 == "NS" || $timePh08 == "NA") {$roPh08 = ""; $workDays08 = 1; } else { $roPh08 = $timePh08;}
            // if ($timePh09 == "AS" || $timePh09 == "NS" || $timePh09 == "NA") {$roPh09 = ""; $workDays09 = 1; } else { $roPh09 = $timePh09;}
            // if ($timePh10 == "AS" || $timePh10 == "NS" || $timePh10 == "NA") {$roPh10 = ""; $workDays10 = 1; } else { $roPh10 = $timePh10;}
            // if ($timePh11 == "AS" || $timePh11 == "NS" || $timePh11 == "NA") {$roPh11 = ""; $workDays11 = 1; } else { $roPh11 = $timePh11;}
            // if ($timePh12 == "AS" || $timePh12 == "NS" || $timePh12 == "NA") {$roPh12 = ""; $workDays12 = 1; } else { $roPh12 = $timePh12;}
            // if ($timePh13 == "AS" || $timePh13 == "NS" || $timePh13 == "NA") {$roPh13 = ""; $workDays13 = 1; } else { $roPh13 = $timePh13;}
            // if ($timePh14 == "AS" || $timePh14 == "NS" || $timePh14 == "NA") {$roPh14 = ""; $workDays14 = 1; } else { $roPh14 = $timePh14;}
            // if ($timePh15 == "AS" || $timePh15 == "NS" || $timePh15 == "NA") {$roPh15 = ""; $workDays15 = 1; } else { $roPh15 = $timePh15;}
            // if ($timePh16 == "AS" || $timePh16 == "NS" || $timePh16 == "NA") {$roPh16 = ""; $workDays16 = 1; } else { $roPh16 = $timePh16;}
            // if ($timePh17 == "AS" || $timePh17 == "NS" || $timePh17 == "NA") {$roPh17 = ""; $workDays17 = 1; } else { $roPh17 = $timePh17;}
            // if ($timePh18 == "AS" || $timePh18 == "NS" || $timePh18 == "NA") {$roPh18 = ""; $workDays18 = 1; } else { $roPh18 = $timePh18;}
            // if ($timePh19 == "AS" || $timePh19 == "NS" || $timePh19 == "NA") {$roPh19 = ""; $workDays19 = 1; } else { $roPh19 = $timePh19;}
            // if ($timePh20 == "AS" || $timePh20 == "NS" || $timePh20 == "NA") {$roPh20 = ""; $workDays20 = 1; } else { $roPh20 = $timePh20;}
            // if ($timePh21 == "AS" || $timePh21 == "NS" || $timePh21 == "NA") {$roPh21 = ""; $workDays21 = 1; } else { $roPh21 = $timePh21;}
            // if ($timePh22 == "AS" || $timePh22 == "NS" || $timePh22 == "NA") {$roPh22 = ""; $workDays22 = 1; } else { $roPh22 = $timePh22;}
            // if ($timePh23 == "AS" || $timePh23 == "NS" || $timePh23 == "NA") {$roPh23 = ""; $workDays23 = 1; } else { $roPh23 = $timePh23;}
            // if ($timePh24 == "AS" || $timePh24 == "NS" || $timePh24 == "NA") {$roPh24 = ""; $workDays24 = 1; } else { $roPh24 = $timePh24;}
            // if ($timePh25 == "AS" || $timePh25 == "NS" || $timePh25 == "NA") {$roPh25 = ""; $workDays25 = 1; } else { $roPh25 = $timePh25;}
            // if ($timePh26 == "AS" || $timePh26 == "NS" || $timePh26 == "NA") {$roPh26 = ""; $workDays26 = 1; } else { $roPh26 = $timePh26;}
            // if ($timePh27 == "AS" || $timePh27 == "NS" || $timePh27 == "NA") {$roPh27 = ""; $workDays27 = 1; } else { $roPh27 = $timePh27;}
            // if ($timePh28 == "AS" || $timePh28 == "NS" || $timePh28 == "NA") {$roPh28 = ""; $workDays28 = 1; } else { $roPh28 = $timePh28;}
            // if ($timePh29 == "AS" || $timePh29 == "NS" || $timePh29 == "NA") {$roPh29 = ""; $workDays29 = 1; } else { $roPh29 = $timePh29;}
            // if ($timePh30 == "AS" || $timePh30 == "NS" || $timePh30 == "NA") {$roPh30 = ""; $workDays30 = 1; } else { $roPh30 = $timePh30;}
            // if ($timePh31 == "AS" || $timePh31 == "NS" || $timePh31 == "NA") {$roPh31 = ""; $workDays31 = 1; } else { $roPh31 = $timePh31;}
            $spreadsheet->getActiveSheet()
                // ->setCellValue("Q".$rowIdx,$roPh01)
                // ->setCellValue("V".$rowIdx,$roPh02)
                // ->setCellValue("AA".$rowIdx,$roPh03)
                // ->setCellValue("AF".$rowIdx,$roPh04)
                // ->setCellValue("AK".$rowIdx,$roPh05)
                // ->setCellValue("AP".$rowIdx,$roPh06)
                // ->setCellValue("AU".$rowIdx,$roPh07)
                // ->setCellValue("AZ".$rowIdx,$roPh08)
                // ->setCellValue("BE".$rowIdx,$roPh09)
                // ->setCellValue("BJ".$rowIdx,$roPh10)
                // ->setCellValue("BO".$rowIdx,$roPh11)
                // ->setCellValue("BT".$rowIdx,$roPh12)
                // ->setCellValue("BY".$rowIdx,$roPh13)
                // ->setCellValue("CD".$rowIdx,$roPh14)
                // ->setCellValue("CI".$rowIdx,$roPh15)
                // ->setCellValue("CN".$rowIdx,$roPh16)
                // ->setCellValue("CS".$rowIdx,$roPh17)
                // ->setCellValue("CX".$rowIdx,$roPh18)
                // ->setCellValue("DC".$rowIdx,$roPh19)
                // ->setCellValue("DH".$rowIdx,$roPh20)
                // ->setCellValue("DM".$rowIdx,$roPh21)
                // ->setCellValue("DR".$rowIdx,$roPh22)
                // ->setCellValue("DW".$rowIdx,$roPh23)
                // ->setCellValue("EB".$rowIdx,$roPh24)
                // ->setCellValue("EG".$rowIdx,$roPh25)
                // ->setCellValue("EL".$rowIdx,$roPh26)
                // ->setCellValue("EQ".$rowIdx,$roPh27)
                // ->setCellValue("EV".$rowIdx,$roPh28)
                // ->setCellValue("FA".$rowIdx,$roPh29)
                // ->setCellValue("FF".$rowIdx,$roPh30)
                // ->setCellValue("FK".$rowIdx,$roPh31)
                // ->setCellValue("R".$rowIdx,$workDays01)
                // ->setCellValue("W".$rowIdx,$workDays02)
                // ->setCellValue("AB".$rowIdx,$workDays03)
                // ->setCellValue("AG".$rowIdx,$workDays04)
                // ->setCellValue("AL".$rowIdx,$workDays05)
                // ->setCellValue("AQ".$rowIdx,$workDays06)
                // ->setCellValue("AV".$rowIdx,$workDays07)
                // ->setCellValue("BA".$rowIdx,$workDays08)
                // ->setCellValue("BF".$rowIdx,$workDays09)
                // ->setCellValue("BK".$rowIdx,$workDays10)
                // ->setCellValue("BP".$rowIdx,$workDays11)
                // ->setCellValue("BU".$rowIdx,$workDays12)
                // ->setCellValue("BZ".$rowIdx,$workDays13)
                // ->setCellValue("CE".$rowIdx,$workDays14)
                // ->setCellValue("CJ".$rowIdx,$workDays15)
                // ->setCellValue("CO".$rowIdx,$workDays16)
                // ->setCellValue("CT".$rowIdx,$workDays17)
                // ->setCellValue("CY".$rowIdx,$workDays18)
                // ->setCellValue("DD".$rowIdx,$workDays19)
                // ->setCellValue("DI".$rowIdx,$workDays20)
                // ->setCellValue("DN".$rowIdx,$workDays21)
                // ->setCellValue("DS".$rowIdx,$workDays22)
                // ->setCellValue("DX".$rowIdx,$workDays23)
                // ->setCellValue("EC".$rowIdx,$workDays24)
                // ->setCellValue("EH".$rowIdx,$workDays25)
                // ->setCellValue("EM".$rowIdx,$workDays26)
                // ->setCellValue("ER".$rowIdx,$workDays27)
                // ->setCellValue("EW".$rowIdx,$workDays28)
                // ->setCellValue("FB".$rowIdx,$workDays29)
                // ->setCellValue("FG".$rowIdx,$workDays30)
                // ->setCellValue("FL".$rowIdx,$workDays31)
                ->setCellValue("FM".$rowIdx,$row['dept'])
                ->setCellValue("FN".$rowIdx,$row['status_payroll'])
                ->setCellValue("FO".$rowIdx,$row['client_name'])
                ->setCellValue("FP".$rowIdx,$row['gaji'])
                ->setCellValue("GB".$rowIdx,$row['thr'])
                ->setCellValue("GC".$rowIdx,$row['bank_name'])
                ->setCellValue("GD".$rowIdx,$row['account_no'])
                
                ;
        

                // echo $row['account_no'];

       



            $spreadsheet->getActiveSheet()->getStyle("A".$rowIdx.":GH".$rowIdx)->applyFromArray($outlineBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("A".$rowIdx.":GH".$rowIdx)->applyFromArray($allBorderStyle);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rowIdx, $rowNo);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $rowIdx, $row['name']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $rowIdx, $row['job_desc']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $rowIdx, $row['basic_salary']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $rowIdx, $row['rate']);
            // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $rowIdx, $row['normal_d']);
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
        }
        // exit();
        // // exit();
        // /* SET NUMBERS FORMAT*/
        
        // unset($allBorderStyle);
        // unset($center);
        // unset($right);
        // unset($left);
        
        // $spreadsheet->setActiveSheetIndex(0);

        // $str = 'AGRSummary';
        // $fileName = preg_replace('/\s+/', '', $str);

        // // Redirect output to a client’s web browser (Xlsx)
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment;filename="'.$fileName.'.Xlsx"');
        // // header('Content-Disposition: attachment;filename="Report Excel.xlsx"');
        // header('Cache-Control: max-age=0');
        // // If you're serving to IE 9, then the following may be needed
        // header('Cache-Control: max-age=1');

        // // If you're serving to IE over SSL, then the following may be needed
        // header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        // header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        // header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        // header('Pragma: public'); // HTTP/1.0
        // /* BY COMPOSER */
        // // $writer = new Xlsx($spreadsheet);
        // /* OFFLINE/ BY COPY EXCEL FOLDER  */
        // $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        // $writer->save('php://output');
        // exit(0);   
    }

    
    
   


} 