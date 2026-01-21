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


class Mtb_report extends BaseController
{
    public function index()
     {
         /* ***Using Valid Path */
         $mtDept = new M_dept();
         $data['data_dept'] = $mtDept->get_dept();
         $data['actView'] = 'Report/v_mtb_report';
         return view('home', $data);
     }

    public function exportSummaryPayrollMartabeAgr($yearPeriod, $sm, $monthPeriod)
    {
        // Create new Spreadsheet object
        $MultiSM = explode(',',$sm);
        $summary = new M_tr_timesheet();
        // $depart = str_replace("%20"," ",$dept);
        
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
        
        foreach(range('B','Y') as $columnID)
        {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        

        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'SUMMARY INVOICE PT SANGATI SOERYA SEJAHTERA - PT. Agincourt Resources Martabe Gold Mine');
        $spreadsheet->getActiveSheet()->mergeCells("A1:Y1");
        $spreadsheet->getActiveSheet()->getStyle("A1:Y1")->applyFromArray($center);
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'PT. PONTIL');
        // $spreadsheet->getActiveSheet()->mergeCells("A2:U2");
        // $spreadsheet->getActiveSheet()->getStyle("A2:U2")->applyFromArray($center);
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'PERIOD : '.$monthPeriod.'-'.$yearPeriod);
        $spreadsheet->getActiveSheet()->mergeCells("A2:Y2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:Y3");
        $spreadsheet->getActiveSheet()->getStyle("A2:X2")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A3:X3")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true)->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle("A2:D2")->getFont()->setBold(true)->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle("A3:D3")->getFont()->setBold(true)->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle("A3:G5")->getFont()->setBold(true)->setSize(12);

        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'DATE        : ');
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'INVOICE NO  : ');
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 5, 'CONTRACT NO : ');        

        /* SET HEADER BG COLOR*/
        $spreadsheet->getActiveSheet()->getStyle('A6:Y6')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B'); 

        /* START INVOICE TITLE */
        $spreadsheet->getActiveSheet()->getStyle("A6:X6")->getFont()->setSize(12);

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
        $spreadsheet->getActiveSheet()->getStyle("X6:X6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("Y6:Y6")->applyFromArray($allBorderStyle);


        $spreadsheet->getActiveSheet()->getStyle("A6:A6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("B6:B6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("C6:C6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("D6:D6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("E6:E6")->applyFromArray($center);
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
        $spreadsheet->getActiveSheet()->getStyle("X6:X6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("Y6:Y6")->applyFromArray($center);

        $spreadsheet->getActiveSheet()->getStyle("A6:A6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("B6:B6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("C6:C6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("D6:D6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("E6:E6")->getAlignment()->setWrapText(true);
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
        $spreadsheet->getActiveSheet()->getStyle("R6:R6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("S6:S6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("T6:T6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("U6:U6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("V6:V6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("W6:W6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("X6:X6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("Y6:Y6")->getAlignment()->setWrapText(true);

        // $spreadsheet->getActiveSheet()->getStyle("A6:H6")->applyFromArray($outlineBorderStyle);

        $spreadsheet->getActiveSheet()->mergeCells("A6:A6");
        $spreadsheet->getActiveSheet()->mergeCells("B6:B6");
        $spreadsheet->getActiveSheet()->mergeCells("C6:C6");
        $spreadsheet->getActiveSheet()->mergeCells("D6:D6");
        $spreadsheet->getActiveSheet()->mergeCells("E6:E6");
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
        $spreadsheet->getActiveSheet()->mergeCells("R6:R6");
        $spreadsheet->getActiveSheet()->mergeCells("S6:S6");
        $spreadsheet->getActiveSheet()->mergeCells("T6:T6");
        $spreadsheet->getActiveSheet()->mergeCells("U6:U6");
        $spreadsheet->getActiveSheet()->mergeCells("W6:W6");
        $spreadsheet->getActiveSheet()->mergeCells("X6:X6");
        $spreadsheet->getActiveSheet()->mergeCells("Y6:Y6");

        $total = 0;
        $rounded = 0;
        $gaji = 0;
        $dana = 0;
        

        $spreadsheet->getActiveSheet()
                ->setCellValue('A6', 'NO')
                ->setCellValue('B6', 'Periode')
                ->setCellValue('C6', 'SM Number')
                ->setCellValue('D6', 'NO KODE PAYROLL')
                ->setCellValue('E6', 'NAMA')
                ->setCellValue('F6', 'JABATAN DAN LOKASI KERJA')
                ->setCellValue('G6', 'BASIC')
                ->setCellValue('H6', 'Salary This Month')
                ->setCellValue('I6', 'Pot. AB')
                ->setCellValue('J6', 'OT')
                ->setCellValue('K6', 'N SHIFT')
                ->setCellValue('L6', 'JKK/JKM')
                ->setCellValue('M6', 'SHIFT')
                ->setCellValue('N6', 'BPJS KES 4%')
                ->setCellValue('O6', 'THR')
                ->setCellValue('P6', 'DANA KOMPENSASI')
                ->setCellValue('Q6', 'JKK/JKM')
                ->setCellValue('R6', 'BPJS KES 5%')
                ->setCellValue('S6', 'JHT & JP')
                ->setCellValue('T6', 'PPH 21')
                ->setCellValue('U6', 'ADJ')
                ->setCellValue('V6', 'TOTAL')
                ->setCellValue('W6', 'ROUNDED')
                ->setCellValue('X6', 'GAJI')
                ->setCellValue('Y6', 'KETERANGAN');

        /* START TOTAL WORK HOUR */     

        $rowIdx = 7;
        $startIdx = $rowIdx; 
        $rowNo = 0;

        foreach ($MultiSM as $key => $value) {
            $summary->setYearProcess($yearPeriod);
            $summary->setMonthProcess($monthPeriod);
            $summary->setpayrollGroup($value);
            // $summary->setdept($depart);
            $data = $summary->summary();
            // Mengubah format bulan menggunakan fungsi date()
            $nama_bulan = date('M', strtotime("2023-$monthPeriod-01"));
            // dd($data);
            
            foreach ($data as $row) {
                // dd($data);
                $jkkjkm1 = 0;
                $empbpjs1 = 0;
                $jhtjp1 = 0;
                $jkkjkm = 0;
                $empbpjs = 0;
                $jhtjp = 0;
                $pph21 = 0;
                $absensi = 0;
                $pph211 = 0;
                $totalsementara = $row['totalgaji'];
                $rowIdx++;
                $rowNo++;
                // $total = $totalsementara + $row['thr'] + $row['allowance_03'] + $row['adjustment'] - $row['pph21'] - $row['emp_bpjs'] - $row['jhtjp'] - $row['jkkjkm'];
                $jkkjkm = $row['jkkjkm'];
                $empbpjs = $row['emp_bpjs'];
                // echo $empbpjs;
                // exit();
                $jhtjp = $row['jhtjp'];
                $pph21 = $row['pph21'];
                $absensi = $row['potongan_absensi'];
                if ($row['jkkjkm'] != 0){
                    $jkkjkm1 = '-'.$row['jkkjkm'];
                }
                if ($row['emp_bpjs'] != 0){
                    $empbpjs1 = '-'.$row['emp_bpjs'];
                }
                if ($row['jhtjp'] != 0){
                    $jhtjp1 = '-'.$row['jhtjp'];
                }
                if ($pph21 != 0){
                    $pph211= '-'.$row['pph21'];
                }
                if ($absensi != 0){
                    $absensi= '-'.$row['potongan_absensi'];
                }

            


                $spreadsheet->getActiveSheet()
                    ->setCellValue('A'.$rowIdx, $rowNo)
                    ->setCellValue('C'.$rowIdx, $row['payroll_group'].'/'.$nama_bulan.'/'.$yearPeriod.'/'.$row['dept']) 
                    ->setCellValue('D'.$rowIdx, $row['biodata_id']) 
                    ->setCellValue('E'.$rowIdx, $row['full_name'])
                    ->setCellValue('F'.$rowIdx, $row['position'])
                    ->setCellValue('G'.$rowIdx, $row['monthly'])
                    ->setCellValue('H'.$rowIdx, $row['gajipokok'])
                    ->setCellValue('I'.$rowIdx, $absensi)
                    ->setCellValue('J'.$rowIdx, $row['totalot'])
                    ->setCellValue('K'.$rowIdx, $row['allowance_02'])
                    ->setCellValue('L'.$rowIdx, $row['jkkjkm'])
                    ->setCellValue('M'.$rowIdx, $row['allowance_01'])
                    ->setCellValue('N'.$rowIdx, $row['bpjs'])
                    ->setCellValue('O'.$rowIdx, $row['thr'])
                    ->setCellValue('P'.$rowIdx, $row['allowance_03'])
                    ->setCellValue('Q'.$rowIdx, $jkkjkm1)
                    ->setCellValue('R'.$rowIdx, $empbpjs1)
                    ->setCellValue('S'.$rowIdx, $jhtjp1)
                    ->setCellValue('T'.$rowIdx, $pph211)
                    ->setCellValue('U'.$rowIdx, $row['adjustment'])
                    ->setCellValue('V'.$rowIdx, $row['totalgaji']+$row['round'])
                    ->setCellValue('W'.$rowIdx, $row['round'])
                    ->setCellValue('X'.$rowIdx, $row['totalgaji'])
                    ->setCellValue('Y'.$rowIdx, '');

                $spreadsheet->getActiveSheet()->getStyle("A".$rowIdx.":Y".$rowIdx)->applyFromArray($outlineBorderStyle);
                $spreadsheet->getActiveSheet()->getStyle("A".$rowIdx.":Y".$rowIdx)->applyFromArray($allBorderStyle);
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
                    $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':Y'.$rowIdx)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('EAEBAF');             
                } 
                
                $firstRow = $rowIdx - 1;
                /* END UPDATE TAX */
                // $totalbasic = $totalbasic + $row['gaji'];
                // $totalott = $totalott + $row['totalot'];
                // $totalnshift = $totalnshift + $row['allowance_02'];
                // $totaljkkjkm  = $totaljkkjkm + $row['jkkjkm'];
                // $totalshift = $totalshift + $row['allowance_01'];
                // $totalbpjs1 = $totalbpjs1 + $row['bpjs'];
                // $totalthr = $totalthr + $row['thr'];
                // $totaldanakonpen = $totaldanakonpen + $row['allowance_03'];
                // $totaljkkjkm1 = $totaljkkjkm1 + $row['jkkjkm'];
                // $totalbpjs = $totalbpjs + $row['emp_bpjs'];
                // $totaljhtjp = $totaljhtjp + $row['jhtjp'];
                // $totalpph21 = $totalpph21 + $row['pph21'];
                // $totaladjustmen = $totaladjustmen + $row['adjustment'];
                // $totaltotal = $totaltotal + $row['gaji'];
                // $totalround = $totalround + $row['round'];
                // $totalgaaji = $totalgaaji + $row['totalgaji'];
                $payrollGroup = $row['payroll_group'];

            // echo $payrollGroup;
            } /* end foreach ($query as $row) */
            $rowIdx = $rowIdx+1;
        }
        if(isset($row['due_date'])) {
            $timestamp  = strtotime($row['due_date']);
            $dueDate    = date('d F Y', $timestamp);
        } else {
            $dueDate    = '';
        }
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1', $monthPeriod.' - '.$yearPeriod) 
            ->setCellValue('A2', 'SUMMARY PAYMENT PT SANGATI SOERYA SEJAHTERA - PT '.'AGINCOURT MARTABE RESOURCES')
            // ->setCellValue('A3', 'DEPARTEMENT : '.$row['dept'])
            ->setCellValue('A5', 'DUE DATE : '. $dueDate);

        $spreadsheet->getActiveSheet()
            ->setCellValue('A'.($rowIdx+1), 'TOTAL')
            ->setCellValue('G'.($rowIdx+1), "=SUM(G8:G".$rowIdx.")")
            ->setCellValue('H'.($rowIdx+1), "=SUM(H8:H".$rowIdx.")")
            ->setCellValue('I'.($rowIdx+1), "=SUM(I8:I".$rowIdx.")")
            ->setCellValue('J'.($rowIdx+1), "=SUM(J8:J".$rowIdx.")")
            ->setCellValue('K'.($rowIdx+1), "=SUM(K8:K".$rowIdx.")")
            ->setCellValue('L'.($rowIdx+1), "=SUM(L8:L".$rowIdx.")")
            ->setCellValue('M'.($rowIdx+1), "=SUM(M8:M".$rowIdx.")")
            ->setCellValue('N'.($rowIdx+1), "=SUM(N8:N".$rowIdx.")")
            ->setCellValue('O'.($rowIdx+1), "=SUM(O8:O".$rowIdx.")")
            ->setCellValue('P'.($rowIdx+1), "=SUM(P8:P".$rowIdx.")")
            ->setCellValue('Q'.($rowIdx+1), "=SUM(Q8:Q".$rowIdx.")")
            ->setCellValue('R'.($rowIdx+1), "=SUM(R8:R".$rowIdx.")")
            ->setCellValue('S'.($rowIdx+1), "=SUM(S8:S".$rowIdx.")")
            ->setCellValue('T'.($rowIdx+1), "=SUM(T8:T".$rowIdx.")")
            ->setCellValue('U'.($rowIdx+1), "=SUM(U8:U".$rowIdx.")")
            ->setCellValue('V'.($rowIdx+1), "=SUM(V8:V".$rowIdx.")")
            ->setCellValue('W'.($rowIdx+1), "=SUM(W8:W".$rowIdx.")")
            ->setCellValue('X'.($rowIdx+1), "=SUM(X8:X".$rowIdx.")")
            ;
       $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+1).":Y".($rowIdx+1))->getFont()->setBold(true)->setSize(12);

        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, ($rowIdx+1), "TOTAL");
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(16, ($rowIdx+1), "=SUM(Q".$startIdx.":Q".$rowIdx.")");
        $spreadsheet->getActiveSheet()->getStyle('E8:X'.($rowIdx+1))->getNumberFormat()->setFormatCode('#,##0.00');       
        $spreadsheet->getActiveSheet()->mergeCells("A".($rowIdx+1).":F".($rowIdx+1));
        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+1).":Y".($rowIdx+1))->applyFromArray($outlineBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+1).":Y".($rowIdx+1))->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+1).":Y".($rowIdx+1))->applyFromArray($center);


        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+1).":Y".($rowIdx+1))
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B');

        /* SET NUMBERS FORMAT*/
        
        unset($allBorderStyle);
        unset($center);
        unset($right);
        unset($left);
       
        $spreadsheet->getActiveSheet()->setTitle($monthPeriod.'-'.$yearPeriod.'PaymentSummary');
       

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

    public function exportPaymentPayrollMartabeAgr($yearPeriod, $monthPeriod, $sm)
    {   

        // $depart = str_replace("%20"," ",$dept);
        // echo $depart;
        // exit();
        $MultiSM = explode(',',$sm);
        $spreadsheet = new Spreadsheet(); 
        $summary = new M_tr_timesheet();


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
        foreach(range('B','J') as $columnID)
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
            ->mergeCells("A1:J1")
            ->mergeCells("A2:J2")
            ->mergeCells("A3:J3");

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
        $spreadsheet->getActiveSheet()->getStyle("A6:J7")
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B'); 

        $spreadsheet->getActiveSheet()
            ->setCellValue('A6', 'NO')
            ->setCellValue('B6', 'SM NUMBER')  
            ->setCellValue('C6', 'NAMA')  
            ->setCellValue('D6', 'ID')  
            ->setCellValue('E6', 'POSISI')  
            ->setCellValue('F6', 'NO REKENING')  
            ->setCellValue('G6', 'NAMA REKENING')  
            ->setCellValue('H6', 'JUMLAH') 
            ->setCellValue('I6', 'BANK CODE')  
            ->setCellValue('J6', 'BANK'); 

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
        $spreadsheet->getActiveSheet()->getStyle("J6:J7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A6:A7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("B6:B7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("C6:C7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("D6:D7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("E6:E7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("F6:F7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("G6:G7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("H6:H7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("I6:I7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("J6:J7")->applyFromArray($center);

        $spreadsheet->getActiveSheet()->getStyle("A1:J4")->applyFromArray($center);

        $spreadsheet->getActiveSheet()
            ->mergeCells("A6:A7")
            ->mergeCells("B6:B7")
            ->mergeCells("C6:C7")
            ->mergeCells("D6:D7")
            ->mergeCells("E6:E7")
            ->mergeCells("F6:F7")
            ->mergeCells("G6:G7")
            ->mergeCells("H6:H7")
            ->mergeCells("I6:I7")
            ->mergeCells("J6:J7")
            ;

        $spreadsheet->getActiveSheet()->getStyle("B6:B7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("C6:C7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("D6:D7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("E6:E7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("F6:F7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("G6:G7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("H6:H7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("I6:I7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("J6:J7")->getAlignment()->setWrapText(true);


        $rowIdx = 8;
        foreach ($MultiSM as $key => $value) {
            $rowNo = 0;
            $summary->setYearProcess($yearPeriod);
            $summary->setMonthProcess($monthPeriod);
            $summary->setpayrollGroup($value);
            $data = $summary->summary(); 
            foreach ($data as $row) {
                // echo var_dump($row).'<br>'; 
                $rowIdx++;
                $rowNo++;
                $biodataId = $row['biodata_id'];
                $totalTerima = $row['totalgaji'];
                $spreadsheet->getActiveSheet()
                    ->setCellValue('A'.$rowIdx, $rowNo)
                    ->setCellValue('B'.$rowIdx, $row['payroll_group'])
                    ->setCellValue('C'.$rowIdx, $row['full_name'])
                    ->setCellValueExplicit('D'.$rowIdx, $row['biodata_id'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValue('E'.$rowIdx, $row['position'])
                    ->setCellValueExplicit('F'.$rowIdx, $row['account_no'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValue('G'.$rowIdx, $row['account_name'])
                    ->setCellValue('H'.$rowIdx, $totalTerima)
                    ->setCellValue('I'.$rowIdx, $row['bank_code'])
                    ->setCellValue('J'.$rowIdx, $row['bank_name'])
                    ;
                   
                    
                /* END UPDATE TAX */
                /* SET ROW COLOR */
                if($rowIdx % 2 == 1)
                {
                    $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':J'.$rowIdx)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('EAEBAF');             
                } 

            } /* end foreach ($query as $row) */   
            // exit();
            $rowIdx = $rowIdx+1;
        }    
        

        $spreadsheet->getActiveSheet()
                ->setCellValue('G'.($rowIdx+2), 'JUMLAH')
                ->setCellValue('H'.($rowIdx+2), '=SUM(H9:H'.$rowIdx.')');

        // $spreadsheet->getActiveSheet()->getStyle("I6:I7")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("H6:H7")->applyFromArray($totalStyle);
        $totalBorder = $rowIdx+2;
        $spreadsheet->getActiveSheet()->getStyle("A".$totalBorder.":J".$totalBorder)->applyFromArray($outlineBorderStyle);

        /* SET NUMBERS FORMAT*/
        $spreadsheet->getActiveSheet()->getStyle('H8:H'.($rowIdx+2))->getNumberFormat()->setFormatCode('#,##0.00');
        /* COLOURING FOOTER */
        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+2).":J".($rowIdx+2))
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B');

        unset($allBorderStyle);
        unset($center);
        unset($right);
        unset($left);
        
        // Rename worksheet

        $spreadsheet->getActiveSheet()->setTitle($monthPeriod.'-'.$yearPeriod.'PaymentList');

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
        $quotedString = "'" . str_replace(',', "','", $sm) . "'";
        $data_proses = new M_tr_timesheet();
        $data_proses->setMonthProcess($bulan);
        $data_proses->setYearProcess($tahun);
        $data_proses->setpayrollGroup($quotedString);
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

    public function getPayrolldeptList($tahun,$bulan,$sm)
    {
    // $depart = str_replace("%20"," ",$dept);
        $data_proses = new M_tr_timesheet();
        $data_proses->setMonthProcess($bulan);
        $data_proses->setYearProcess($tahun);
        $data_proses->setpayrollGroup($sm);
        $data = $data_proses->getAlldeptData();
        // echo $this->db->last_query(); exit(0);
        // echo $this->db->last_query(); exit(0);
        /*return json_encode($query);*/
        $myData = array();

        $MultiSM = explode(',',$sm);

        foreach ($MultiSM as $key => $value) {
            $data_proses->setMonthProcess($bulan);
            $data_proses->setYearProcess($tahun);
            $data_proses->setpayrollGroup($value);
            $data = $data_proses->getAlldeptData();

            foreach ($data as $key => $row){
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
        }

          

        echo json_encode($myData);  
        // echo $this->db->last_query(); 
    }
    public function exportinvoicePayrollMartabeAgr($yearPeriod, $sm,$monthPeriod)
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
        $spreadsheet->getActiveSheet()->setTitle($sm.'-'.$monthPeriod.'-'.$yearPeriod.'Invoice');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        $str = $sm.'-'.$monthPeriod.'-'.$yearPeriod.'PaymentList';
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
        $quotedString = "'" . str_replace(',', "','", $sm) . "'";
        $summary = new M_tr_timesheet();
        $summary->setYearProcess($yearPeriod);
        $summary->setMonthProcess($monthPeriod);
        $summary->setpayrollGroup($quotedString);
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

        // $data = $summary->formlist();
        
        // Add some data
        $spreadsheet->getActiveSheet()->getStyle('A7:F8')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('8BAFCE');
        $rowIdx = 9;
        $rowNo = 1;
        

         $spreadsheet->getActiveSheet()
	    ->setCellValue('D1', 'No. : ______________________________________')
            ->setCellValue('A4', 'PENGAJUAN PEMBAYARAN')
            ->setCellValue('A5', 'PT AGINCOURT RESOURCES');

        $spreadsheet->getActiveSheet()->getStyle("A4:F4")->getFont()->setBold(true)->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle("A4:F4")->getFont()->setBold(true)->setSize(14)->setUnderline(true);
        $spreadsheet->getActiveSheet()->getStyle("A5:F5")->getFont()->setBold(true)->setSize(12);
	$spreadsheet->getActiveSheet()->getStyle("A7:F7")->getFont()->setBold(true)->setSize(12);
	
    $spreadsheet->getActiveSheet()
            ->mergeCells("A4:F4")
            ->mergeCells("A5:F5")
;
    $spreadsheet->getActiveSheet()->getStyle("A4:F4")->applyFromArray($center);
    $spreadsheet->getActiveSheet()->getStyle("A5:F5")->applyFromArray($center);


        $spreadsheet->getActiveSheet()->mergeCells("A7:A8");
        $spreadsheet->getActiveSheet()->mergeCells("B7:B8");
        $spreadsheet->getActiveSheet()->mergeCells("C7:D7");
        $spreadsheet->getActiveSheet()->mergeCells("D8:D8");
        // $spreadsheet->getActiveSheet()->mergeCells("C8:D8");
        $spreadsheet->getActiveSheet()->mergeCells("E7:E8");
        $spreadsheet->getActiveSheet()->mergeCells("F7:F8");

        $spreadsheet->getActiveSheet()->getStyle("A7:F8")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A7:F8")->applyFromArray($center);
        $spreadsheet->getActiveSheet()   
            ->setCellValue('A7', 'No.')
            ->setCellValue('B7', 'Uraian')
            ->setCellValue('C7', 'Invoicable')
            ->setCellValue('C8', 'YA')
            ->setCellValue('D8', 'TIDAK')
            ->setCellValue('E7', 'COST CENTER')
            ->setCellValue('F7', 'Total Pembayaran');  
        $newDate = date('M', strtotime("2023-$monthPeriod-01")); 

        /* START GET DAYS TOTAL BY ROSTER */
        foreach ($data as $row) {
            $rowIdx++;
            $totalKaryawan = $row['totalempl'];
            $totalgaji  = $row['totalgaji'];
            $SMgroup = $row['payroll_group'];

        $spreadsheet->getActiveSheet()
                ->setCellValue('A'.($rowIdx), $rowNo)
                ->setCellValue('B9', 'Pembayaran Gaji Karyawan PT Agincourt Resources')
                ->setCellValue('B'.($rowIdx), $SMgroup.'/'.$newDate.'/'.$yearPeriod.'/'.$row['dept'].'('.$totalKaryawan.')')
                ->setCellValue('F'.($rowIdx), $totalgaji);
        // $spreadsheet->getActiveSheet()->getStyle("B8:D8")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("B".($rowIdx).":F".($rowIdx))->getFont()->setBold(true)->setSize(12);


            /* SET ROW COLOR */
            // if($rowIdx % 2 == 1)
            // {
            //     $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':F' .$rowIdx)
            //     ->getFill()
            //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            //     ->getStartColor()
            //     ->setRGB('EAEBAF');             
            // } 
                $rowNo++;
        
        }
        
        if(isset($row['due_date'])) {
            $timestamp  = strtotime($row['due_date']);
            $dueDate    = date('d F Y', $timestamp);
        } else {
            $dueDate    = '';
        }

        // Memformat tanggal menjadi "d F Y"
        
        $spreadsheet->getActiveSheet()
            ->setCellValue('B'.($rowIdx+1), 'Periode :')
            ->setCellValue('B'.($rowIdx+3), 'Ket : Terlampir')
            ->setCellValue('B'.($rowIdx+4), 'Due Date: '.$dueDate)
            ->setCellValue('C9', '')
            ->setCellValue('D9', '')
            ->setCellValue('E9', '')
            ;  
        $spreadsheet->getActiveSheet()
            ->setCellValue('A'.($rowIdx+8), "")
            ->setCellValue('B'.($rowIdx+8), "Total Pengajuan Pembayaran")
            ->setCellValue('C'.($rowIdx+8), "")
            ->setCellValue('D'.($rowIdx+8), "")
            ->setCellValue('E'.($rowIdx+8), "")
            ->setCellValue('F'.($rowIdx+8), '=SUM(F10:F'.($rowIdx+1).')');
            $datenow = date("Y/m/d");
        $spreadsheet->getActiveSheet()->getStyle('A9'.':F'.($rowIdx+8))->getFont()->setBold(true)->setSize(12);

                
            $spreadsheet->getActiveSheet()
                ->setCellValue('B'.($rowIdx+10), "Penarikan Cheque/BG No. : ...................
")											     
                ->setCellValue('B'.($rowIdx+11), "Tanggal                                       : ...................
")
                ->setCellValue('B'.($rowIdx+13), "Disetujui,                                                                     Checked,
")
                ->setCellValue('B'.($rowIdx+18), "(  Julius Jiwanggono  )                                                      (                           )
")
                ->setCellValue('B'.($rowIdx+20), "Controller,                                                                     Finance,
")
                ->setCellValue('B'.($rowIdx+26), "( Etty Aryati )                                                                   (                            )
")
                ->setCellValue('F'.($rowIdx+12), 'JAKARTA  '.$datenow)
                ->setCellValue('F'.($rowIdx+13), "Diajukan,
")
                ->setCellValue('F'.($rowIdx+18), "(                                 )
")
                ->setCellValue('F'.($rowIdx+20), "Accounting,
")
                ->setCellValue('F'.($rowIdx+26), "(                                        )
");
        $spreadsheet->getActiveSheet()->getStyle('F10:F'.($rowIdx+8))->getNumberFormat()->setFormatCode('#,##0.00');       
        
        // $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+9).":F".($rowIdx+9))
           // ->getFill()
           // ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
           // ->getStartColor()
         //   ->setRGB('F2BE6B');

        $spreadsheet->getActiveSheet()->getStyle("A7:F".($rowIdx+8))->applyFromArray($allBorderStyle);
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle($monthPeriod.'-'.$yearPeriod.'Payment Form');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        $str = $sm.$monthPeriod.$yearPeriod.'PaymentForm';
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