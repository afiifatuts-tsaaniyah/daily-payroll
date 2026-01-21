<?php namespace App\Controllers\Report;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
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

    private function terbilang($angka)
    {
        $angka = floatval($angka);
        $satuan = array(
            '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan',
            'sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas', 'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas'
        );
        $puluhan = array(
            '', '', 'dua puluh', 'tiga puluh', 'empat puluh', 'lima puluh', 'enam puluh', 'tujuh puluh', 'delapan puluh', 'sembilan puluh'
        );
        $hasil = '';
        if ($angka < 20) {
            $hasil = $satuan[(int)$angka];
        } elseif ($angka < 100) {
            $hasil = $puluhan[(int)($angka / 10)] . ' ' . $satuan[$angka % 10];
        } elseif ($angka < 200) {
            $hasil = 'seratus ' . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $hasil = $satuan[(int)($angka / 100)] . ' ratus ' . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $hasil = 'seribu ' . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $hasil = $this->terbilang((int)($angka / 1000)) . ' ribu ' . $this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $hasil = $this->terbilang((int)($angka / 1000000)) . ' juta ' . $this->terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $hasil = $this->terbilang((int)($angka / 1000000000)) . ' miliar ' . $this->terbilang($angka % 1000000000);
        } elseif ($angka < 1000000000000000) {
            $hasil = $this->terbilang((int)($angka / 1000000000000)) . ' triliun ' . $this->terbilang($angka % 1000000000000);
        } else {
            $hasil = 'Angka terlalu besar';
        }
        return $hasil;
    }

    public function getPayrollListInvoice($tahun,$bulan)
    {
        $data_proses = new M_tr_timesheet();
        $data = $data_proses->SummaryInvoice($bulan, $tahun);
        // echo $this->db->last_query(); exit(0);
        // echo $this->db->last_query(); exit(0);
        /*return json_encode($query);*/
        $myData = array();
        foreach ($data as $key => $row) 
        {
               $myData[] = array(
                $row['dept'],
                $row['total_emp'],
                $row['total_work_day']
            );            
        }
          

        echo json_encode($myData);  
        // echo $this->db->last_query(); 
    } 

    public function getPayrollListInvoiceDept($tahun,$bulan,$dept)
    {
        $dept = str_replace("%20"," ",$dept);
        $data_proses = new M_tr_timesheet();
        $data = $data_proses->getDataInvoiceAllDeptView($tahun, $bulan, $dept);
        // var_dump($data);
        // exit();
        $myData = array();
        foreach ($data as $key => $row) 
        {
            $rowBio = $data_proses->getBiodataNameInvoice($row['biodata_id']);
            $rowSlr = $data_proses->getSalaryNameInvoice($row['biodata_id']);
            if(!empty($rowSlr['status_payroll'])) {
                $myData[] = array(
                    $rowBio['full_name'],
                    $row['dept'],         
                    $rowBio['emp_position'], 
                    $rowSlr['status_payroll']
                );
            }            
        }
          

        echo json_encode($myData);  
    }

    
    public function exportinvoicePayrollMartabeAgr($yearPeriod, $monthPeriod)
    {
        $summary = new M_tr_timesheet();
        $data = $summary->SummaryInvoice($monthPeriod,$yearPeriod);
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

        // foreach(range('B','R') as $columnID)
        // {
        //     $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        // }
        
        // Add some data
        $spreadsheet->getActiveSheet()->getStyle('A6:R10')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('ffcc00'); 
        $endMonthPeriod = $monthPeriod + 1;
        $endYearPeriod = $yearPeriod;
        if ($endMonthPeriod > 12) {
            $endMonthPeriod = $endMonthPeriod - 12;
            $endYearPeriod = $yearPeriod+1;
        }
        
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1', 'PT. SANGATI SOERYA SEJAHTERA')
            ->setCellValue('A2', 'INVOICES OF OUTSOURCING LABOUR SUPPLY SERVICES IN PT. AGINCOURT RESOURCES')
            ->setCellValue('A3', 'TIMESHEET PERIOD : 16 '.$monthPeriod.' '.$yearPeriod.' - 15 '.$endMonthPeriod.' '.$endYearPeriod  );

        $spreadsheet->getActiveSheet()->mergeCells("A1:R1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:R2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:R3");

        $spreadsheet->getActiveSheet()->getStyle("A1:R1")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A2:R2")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A3:R3")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A1:R1")->getFont()->setBold(true)->setSize(24);
        $spreadsheet->getActiveSheet()->getStyle("A2:R2")->getFont()->setBold(true)->setSize(16)->setUnderline(true);
        $spreadsheet->getActiveSheet()->getStyle("A3:R3")->getFont()->setBold(true)->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle("A6:R10")->getFont()->setBold(true)->setSize(12);

        $spreadsheet->getActiveSheet()->mergeCells("A6:A10");
        $spreadsheet->getActiveSheet()->mergeCells("B6:B10");
        $spreadsheet->getActiveSheet()->mergeCells("C6:C10");
        $spreadsheet->getActiveSheet()->mergeCells("D6:D10");
        $spreadsheet->getActiveSheet()->mergeCells("E6:E10");
        $spreadsheet->getActiveSheet()->mergeCells("F6:L7");
        $spreadsheet->getActiveSheet()->mergeCells("F8:F9");
        $spreadsheet->getActiveSheet()->mergeCells("G8:G9");
        $spreadsheet->getActiveSheet()->mergeCells("H8:H9");
        $spreadsheet->getActiveSheet()->mergeCells("I8:I9");
        $spreadsheet->getActiveSheet()->mergeCells("J8:J9");
        $spreadsheet->getActiveSheet()->mergeCells("K8:K9");
        $spreadsheet->getActiveSheet()->mergeCells("L8:L9");
        $spreadsheet->getActiveSheet()->mergeCells("M6:M9");
        $spreadsheet->getActiveSheet()->mergeCells("N6:N9");
        $spreadsheet->getActiveSheet()->mergeCells("O6:P7");
        $spreadsheet->getActiveSheet()->mergeCells("O8:O10");
        $spreadsheet->getActiveSheet()->mergeCells("P8:P10");
        $spreadsheet->getActiveSheet()->mergeCells("Q6:Q9");
        $spreadsheet->getActiveSheet()->mergeCells("R6:R9");

        $spreadsheet->getActiveSheet()->getStyle("B6:B10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("C6:C10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("D6:D10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("E6:E10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("F6:L7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("F8:F10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("G8:G10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("H8:H10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("I8:I10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("J8:J10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("K8:K10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("L8:L10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("M6:M10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("N6:N10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("O6:P7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("O8:O10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("P8:P10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("Q6:Q10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("R6:R10")->getAlignment()->setWrapText(true);
            
        $spreadsheet->getActiveSheet()->getStyle("A6:R10")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A6:R10")->applyFromArray($center);
        $spreadsheet->getActiveSheet()
            ->setCellValue('A6', 'No.')
            ->setCellValue('B6', 'Department / User')
            ->setCellValue('C6', 'Cost Code')
            ->setCellValue('D6', 'Total Empl.')
            ->setCellValue('E6', 'Total Work Day(s)')
            ->setCellValue('F6', 'TUNJANGAN DAN ALLOWANCE')
            ->setCellValue('F8', 'Gaji Pokok Perbulan')
            ->setCellValue('F10', '(Rp)')
            ->setCellValue('G8', 'Over Time dalam 1 (Satu) Bulan')
            ->setCellValue('G10', '(Rp)')
            ->setCellValue('H8', 'Tunjangan Shift')
            ->setCellValue('H10', '(Rp)')
            ->setCellValue('I8', 'TOTAL Gaji, Overtime Dan Tunjangan Shift')
            ->setCellValue('I10', '(Rp)')
            ->setCellValue('J8', 'BPJS TENAGA KERJA')
            ->setCellValue('J10', '(Rp)')
            ->setCellValue('K8', 'BPJS KESEHATAN')
            ->setCellValue('K10', '(Rp)')
            ->setCellValue('L8', 'BPJS PENSIUN')
            ->setCellValue('L10', '(Rp)')
            ->setCellValue('M6', 'SUB TOTAL INVOICE')
            ->setCellValue('M10', '(Rp)')
            ->setCellValue('N6', 'MANAGAMENT FEE Kontraktor')
            ->setCellValue('N10', '(Rp)')
            ->setCellValue('O6', 'PAJAK')
            ->setCellValue('O8', 'PPN 11% (WAPU)')
            ->setCellValue('P8', 'PPH 23 (2%)')
            ->setCellValue('Q6', 'TOTAL INVOICE')
            ->setCellValue('Q10', '(Rp)')
            ->setCellValue('R6', 'PAID BY PTAR')
            ->setCellValue('R10', '(Rp)')
            ;
            
            

        /* START GET DAYS TOTAL BY ROSTER */
        $rowIdx = 11;
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
        $totalBasicSalary = 0;
        $previousDept = null;
        $ot1 = 1.5;
        $ot2 = 2.0;
        $ot3 = 3.0;
        $ot4 = 4.0;

        foreach ($data as $row) {                      
            $rowIdx++;
            $rowNo++;  
            $dept = $row['dept'];
            $basicSalary = $row['monthly'];
            $totalBasicSalary = 0;
            $ot01Val = $row['total_ot__1'];
            $ot02Val = $row['total_ot__2'];
            $ot03Val = $row['total_ot__3'];
            $ot04Val = $row['total_ot__4'];
            $salaryHourly = $basicSalary / 173;

            $valueOt1 = $ot01Val * $ot1 * $salaryHourly;
            $valueOt2 = $ot02Val * $ot2 * $salaryHourly;
            $valueOt3 = $ot03Val * $ot3 * $salaryHourly;
            $valueOt4 = $ot04Val * $ot4 * $salaryHourly;

            $totalOtValue = $valueOt1 + $valueOt2 + $valueOt3 + $valueOt4;
            // echo $totalOtValue;
            $dataByDept = $summary->getDataInvoiceByDept($monthPeriod, $yearPeriod, $dept);
            foreach ($dataByDept as $rowDept) {
                $basicSalary = $rowDept['monthly'];
                // Periksa apakah departemen berbeda dari iterasi sebelumnya\
                
                $totalBasicSalary += $basicSalary;
                if ($dept !== $previousDept) {
                    $totalBasicSalary = 0;
                    $totalBasicSalary += $basicSalary; // Tambahkan nilai $basicSalary ke totalBasicSalary
                    $previousDept = $dept; // Update nilai departemen sebelumnya
                }
            }
            // exit();
            // $totalempl = $row['totalempl'];
            // $totalwork = $row['totalwork'];
            // $gajipokok = $row['gajipokok'];
            // $overtime = $row['overtime'];
            // // $tunjangan = $row['tunjangan'];
            // $bpjsTNK = $totalempl * $gajipokok * 0.0574;
            // $bpjsKSH = $totalempl * $gajipokok * 0.04;
            // $bpjsPNS = $totalempl * $gajipokok * 0.02;
            // $total = $gajipokok + $overtime + $tunjangan;
            // $subTOINV = $total + $bpjsTNK + $bpjsKSH + $bpjsPNS;
            // $managamentFEE = $total * 0.15;
            // $ppn11 = $managamentFEE * 0.11;
            // $pph23 = $managamentFEE * 0.02;
            // $totalIVC = $subTOINV + $managamentFEE + $ppn11 - $pph23;
            // $paidBPTR = $totalIVC - $ppn11; 

            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.($rowIdx), $rowNo)
                ->setCellValue('B'.($rowIdx), $row['dept'])
                ->setCellValue('C'.($rowIdx), '')
                ->setCellValue('D'.($rowIdx), $row['total_emp'])
                ->setCellValue('E'.($rowIdx), $row['total_work_day'])
                ->setCellValue('F'.($rowIdx), $totalBasicSalary)
                ->setCellValue('G'.($rowIdx), $totalOtValue)
                ->setCellValue('H'.($rowIdx), $row['total_tunjangan'])
                ->setCellValue('I'.($rowIdx), "=SUM(F$rowIdx:H$rowIdx)")
                ->setCellValue('J'.($rowIdx), "=F$rowIdx*5.74%")
                ->setCellValue('K'.($rowIdx), "=F$rowIdx*4%")
                ->setCellValue('L'.($rowIdx), "=F$rowIdx*2%")
                ->setCellValue('M'.($rowIdx), "=SUM(I$rowIdx:L$rowIdx)")
                ->setCellValue('N'.($rowIdx), "=I$rowIdx*9.3%")
                ->setCellValue('O'.($rowIdx), "=N$rowIdx*11%")
                ->setCellValue('P'.($rowIdx), "=-N$rowIdx*2%")
                ->setCellValue('Q'.($rowIdx), "=SUM(M$rowIdx:P$rowIdx)")
                ->setCellValue('R'.($rowIdx), "=Q$rowIdx-O$rowIdx")                
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
            // $totalemplall = $totalemplall + $totalempl;
            // $totalworkall = $totalworkall + $totalwork;
            // $gajipokokall = $gajipokokall + $gajipokok;
            // $overtimeall = $overtimeall + $overtime;
            // $tunjanganall = $tunjanganall + $tunjangan;
            // $bpjsTNKall = $bpjsTNKall + $bpjsTNK;
            // $bpjsPNSall = $bpjsPNSall + $bpjsPNS;
            // $bpjsKSHall = $bpjsKSHall + $bpjsKSH;
            // $totalall = $totalall + $total;
            // $subTOINVall = $subTOINVall + $subTOINV;
            // $managamentFEEall = $managamentFEEall + $managamentFEE;
            // $ppn11all = $ppn11all + $ppn11;
            // $pph23all = $pph23all + $pph23;
            // $totalIVCall = $totalIVCall + $totalIVC;
            // $paidBPTRall = $paidBPTRall + $paidBPTR;
        }

        $firstRow = $rowIdx - 1;
        
        $spreadsheet->getActiveSheet()
            ->setCellValue('A'.($rowIdx+2), "TOTAL")
            ->setCellValue('D'.($rowIdx+2), "=SUM(D8:D$firstRow)")
            ->setCellValue('E'.($rowIdx+2), "=SUM(E8:E$firstRow)")
            ->setCellValue('F'.($rowIdx+2), "=SUM(F8:F$firstRow)")
            ->setCellValue('G'.($rowIdx+2), "=SUM(G8:G$firstRow)")
            ->setCellValue('H'.($rowIdx+2), "=SUM(H8:H$firstRow)")
            ->setCellValue('I'.($rowIdx+2), "=SUM(I8:I$firstRow)")
            ->setCellValue('J'.($rowIdx+2), "=SUM(J8:J$firstRow)")
            ->setCellValue('K'.($rowIdx+2), "=SUM(K8:K$firstRow)")
            ->setCellValue('L'.($rowIdx+2), "=SUM(L8:L$firstRow)")
            ->setCellValue('M'.($rowIdx+2), "=SUM(M8:M$firstRow)")
            ->setCellValue('N'.($rowIdx+2), "=SUM(N8:N$firstRow)")
            ->setCellValue('O'.($rowIdx+2), "=SUM(O8:O$firstRow)")
            ->setCellValue('P'.($rowIdx+2), "=SUM(P8:P$firstRow)")
            ->setCellValue('Q'.($rowIdx+2), "=SUM(Q8:Q$firstRow)")
            ->setCellValue('R'.($rowIdx+2), "=SUM(R8:R$firstRow)")  
            ;
        $spreadsheet->getActiveSheet()->mergeCells("A11:R11");
        $spreadsheet->getActiveSheet()->mergeCells("A".($rowIdx+1).":R".($rowIdx+1));
        $spreadsheet->getActiveSheet()->mergeCells("A".($rowIdx+2).":C".($rowIdx+2));
        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+2))->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+2).":C".($rowIdx+2))->getFont()->setBold(true)->setSize(12);

        $spreadsheet->getActiveSheet()->getStyle('D8:R'.($rowIdx+2))->getNumberFormat()->setFormatCode('#,##0.00');       
        
        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+2).":R".($rowIdx+2))
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B');

        $spreadsheet->getActiveSheet()->getStyle("A6:R".($rowIdx+2))->applyFromArray($allBorderStyle);
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle($monthPeriod.'-'.$yearPeriod.'Invoice');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        $str = $monthPeriod.'-'.$yearPeriod.'AllDeptSummary';
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

    public function exportinvoiceAllDept($yearPeriod, $monthPeriod, $dept)
    {
        $summary = new M_tr_timesheet();
        $depart = str_replace("%20"," ",$dept);
        // echo $dept;
        // exit();
        $data = $summary->getDataInvoiceAllDept($yearPeriod, $monthPeriod, $depart);
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

        // foreach(range('B','R') as $columnID)
        // {
        //     $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        // }
        
        // Add some data
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(10);
        $spreadsheet->getActiveSheet()->getStyle('A6:V10')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('ffcc00'); 
        $endMonthPeriod = $monthPeriod + 1;
        $endYearPeriod = $yearPeriod;
        if ($endMonthPeriod > 12) {
            $endMonthPeriod = $endMonthPeriod - 12;
            $endYearPeriod = $yearPeriod+1;
        }
        
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1', 'PT. SANGATI SOERYA SEJAHTERA')
            ->setCellValue('A2', 'INVOICES OF OUTSOURCING LABOUR SUPPLY SERVICES IN PT. AGINCOURT RESOURCES')
            ->setCellValue('A3', 'TIMESHEET PERIOD : 16 '.$monthPeriod.' '.$yearPeriod.' - 15 '.$endMonthPeriod.' '.$endYearPeriod  )
            ->setCellValue('A4', 'DEPARTEMENT : '.$depart  );

        $spreadsheet->getActiveSheet()->mergeCells("A1:V1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:V2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:V3");
        $spreadsheet->getActiveSheet()->mergeCells("A4:E4");

        $spreadsheet->getActiveSheet()->getStyle("A1:V1")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A2:V2")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A3:V3")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A1:V1")->getFont()->setBold(true)->setSize(24);
        $spreadsheet->getActiveSheet()->getStyle("A2:V2")->getFont()->setBold(true)->setSize(16)->setUnderline(true);
        $spreadsheet->getActiveSheet()->getStyle("A3:V3")->getFont()->setBold(true)->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle("A4:E4")->getFont()->setBold(true)->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle("A6:V10")->getFont()->setBold(true)->setSize(12);

        $spreadsheet->getActiveSheet()->mergeCells("A6:A10");
        $spreadsheet->getActiveSheet()->mergeCells("B6:B10");
        $spreadsheet->getActiveSheet()->mergeCells("C6:C10");
        $spreadsheet->getActiveSheet()->mergeCells("D6:D10");
        $spreadsheet->getActiveSheet()->mergeCells("E6:E10");
        $spreadsheet->getActiveSheet()->mergeCells("F6:F10");
        $spreadsheet->getActiveSheet()->mergeCells("G6:G10");
        $spreadsheet->getActiveSheet()->mergeCells("H6:H10");
        $spreadsheet->getActiveSheet()->mergeCells("I6:I10");
        $spreadsheet->getActiveSheet()->mergeCells("J6:J10");
        $spreadsheet->getActiveSheet()->mergeCells("K6:K10");
        $spreadsheet->getActiveSheet()->mergeCells("L6:S7");
        $spreadsheet->getActiveSheet()->mergeCells("L8:L9");
        $spreadsheet->getActiveSheet()->mergeCells("M8:M9");
        $spreadsheet->getActiveSheet()->mergeCells("N8:N9");
        $spreadsheet->getActiveSheet()->mergeCells("O8:O9");
        $spreadsheet->getActiveSheet()->mergeCells("P8:P9");
        $spreadsheet->getActiveSheet()->mergeCells("Q8:Q9");
        $spreadsheet->getActiveSheet()->mergeCells("R8:R9");
        $spreadsheet->getActiveSheet()->mergeCells("S8:S9");
        $spreadsheet->getActiveSheet()->mergeCells("T6:U7");
        $spreadsheet->getActiveSheet()->mergeCells("T8:T10");
        $spreadsheet->getActiveSheet()->mergeCells("U8:U10");
        $spreadsheet->getActiveSheet()->mergeCells("V6:V10");

        $spreadsheet->getActiveSheet()->getStyle("B6:B10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("C6:C10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("D6:D10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("E6:E10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("F6:F10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("G6:G10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("H6:H10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("I6:I10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("J6:J10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("K6:K10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("L6:S7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("L8:L10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("M8:M10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("N8:N10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("O8:O10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("P8:P10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("Q8:Q10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("R8:R10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("S8:S10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("T6:U7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("T8:T10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("U8:U10")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("V6:V10")->getAlignment()->setWrapText(true);
            
        $spreadsheet->getActiveSheet()->getStyle("A6:V10")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A6:V10")->applyFromArray($center);
        $spreadsheet->getActiveSheet()
            ->setCellValue('A6', 'No.')
            ->setCellValue('B6', 'No. ID Badge')
            ->setCellValue('C6', 'NAMA')
            ->setCellValue('D6', 'POSISI')
            ->setCellValue('E6', 'DEPARTMENT')
            ->setCellValue('F6', 'STATUS KARYAWAN')
            ->setCellValue('G6', 'Gaji Pokok Monthly / Daily')
            ->setCellValue('H6', 'TOTAL HARI KERJA')
            ->setCellValue('I6', 'TOTAL JAM KERJA LEMBUR (JAM)')
            ->setCellValue('J6', 'TOTAL TUNJANGAN AN SHIFT Siang 10%')
            ->setCellValue('K6', 'TOTAL TUNJANGAN AN SHIFT Malam (HARI) 15%')
            ->setCellValue('L6', 'TUNJANGAN DAN ALLOWANCE')
            ->setCellValue('L8', 'TOTAL GAJI POKOK')
            ->setCellValue('L10', '(Rp)')
            ->setCellValue('M8', 'TOTAL JAM KERJA LEMBUR')
            ->setCellValue('M10', '(Rp)')
            ->setCellValue('N8', 'TOTAL TUNJANGAN SHIFT')
            ->setCellValue('N10', '(Rp)')
            ->setCellValue('O8', 'TOTAL GAJI OVERTIME DAN TUNJANGAN SHIFT')
            ->setCellValue('O10', '(Rp)')
            ->setCellValue('P8', 'MANAGAMENT FEE')
            ->setCellValue('P10', '(Rp)')
            ->setCellValue('Q8', 'BPJS Tenaga Kerja')
            ->setCellValue('Q10', '(Rp)')
            ->setCellValue('R8', 'BPJS Kesehatan')
            ->setCellValue('R10', '(Rp)')
            ->setCellValue('S8', 'BPJS Pensiun')
            ->setCellValue('S10', '(Rp)')
            ->setCellValue('T6', 'PAJAK')
            ->setCellValue('T8', 'PPh 23')
            ->setCellValue('U8', 'PPN')
            ->setCellValue('V6', 'TOTAL INVOICE')
            ->setCellValue('V10', '(Rp)')
            ;
            
            

        /* START GET DAYS TOTAL BY ROSTER */
        $rowIdx = 11;
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
        $totalBasicSalary = 0;
        $totalOtValue = 0;
        $monthly = 0;
        $ot01Val = 0;
        $ot02Val = 0;
        $ot03Val = 0;
        $ot04Val = 0;
        // dd($data);
        foreach ($data as $row) {         
            // dd($row);
            $dataBiodata = $summary->getBiodataNameInvoicePrint($row['biodata_id']);
            $dataSalary  = $summary->getSalaryNameInvoicePrint($row['biodata_id']);             
            $rowIdx++;
            $rowNo++;
            $totalWorkDay = $row['total_work_day'];
            $statusPayroll = $dataSalary['status_payroll'];
            $ot01Val = $row['total_ot1'];
            $ot02Val = $row['total_ot2'];
            $ot03Val = $row['total_ot3'];
            $ot04Val = $row['total_ot4'];
            $ot1 = 1.5;
            $ot2 = 2.0;
            $ot3 = 3.0;
            $ot4 = 4.0;
            $monthly = $dataSalary['monthly'];
            if ($statusPayroll != 'monthly') {
                $basicSalary = $dataSalary['daily'];
            } else {
                $basicSalary = $dataSalary['monthly'];
            }
            if ($statusPayroll == 'daily' || $statusPayroll == 'daily p') {
                $gajiPokok = $totalWorkDay * $basicSalary;
                $bpjsTk = $monthly * 20 * 0.0574;
                $bpjsKes = $monthly * 20 * 0.04; 
                $bpjsPen = $monthly * 20 * 0.02; 
            } else {
                $gajiPokok = $dataSalary['monthly'];
                $bpjsTk = $monthly * 0.0574;
                $bpjsKes = $monthly * 0.04;
                $bpjsPen = $monthly * 0.02; 
            }

            $salaryHourly = $monthly / 173;

            $valueOt1 = $ot01Val * $ot1 * $salaryHourly;
            $valueOt2 = $ot02Val * $ot2 * $salaryHourly;
            $valueOt3 = $ot03Val * $ot3 * $salaryHourly;
            $valueOt4 = $ot04Val * $ot4 * $salaryHourly;

            $totalOtValue = $valueOt1 + $valueOt2 + $valueOt3 + $valueOt4;
            // echo $totalOtValue;


            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.($rowIdx), $rowNo)
                ->setCellValue('B'.($rowIdx), '')
                ->setCellValue('C'.($rowIdx), $dataBiodata['full_name'])
                ->setCellValue('D'.($rowIdx), $dataBiodata['emp_position'])
                ->setCellValue('E'.($rowIdx), $row['dept'])
                ->setCellValue('F'.($rowIdx), $dataSalary['status_payroll'])
                ->setCellValue('G'.($rowIdx), $basicSalary)
                ->setCellValue('H'.($rowIdx), $row['total_work_day'])
                ->setCellValue('I'.($rowIdx), $row['total_ot'])
                ->setCellValue('J'.($rowIdx), $row['total_tunjangan_siang'])
                ->setCellValue('K'.($rowIdx), $row['total_tunjangan_malam'])
                ->setCellValue('L'.($rowIdx), $gajiPokok)
                ->setCellValue('M'.($rowIdx), $totalOtValue)
                ->setCellValue('N'.($rowIdx), $row['total_tunjangan'])
                ->setCellValue('O'.($rowIdx), "=SUM(L$rowIdx:N$rowIdx)")
                ->setCellValue('P'.($rowIdx), "=O$rowIdx*9.3%")
                ->setCellValue('Q'.($rowIdx), $bpjsTk)
                ->setCellValue('R'.($rowIdx), $bpjsKes) 
                ->setCellValue('S'.($rowIdx), $bpjsPen) 
                ->setCellValue('T'.($rowIdx), "=-P$rowIdx*2%") 
                ->setCellValue('U'.($rowIdx), "=P$rowIdx*11%") 
                ->setCellValue('V'.($rowIdx), "=SUM(O$rowIdx:U$rowIdx)")               
                ;   
            



            /* SET ROW COLOR */
            if($rowIdx % 2 == 1)
            {
                $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':V' .$rowIdx)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('EAEBAF');             
            } 
            // $totalemplall = $totalemplall + $totalempl;
            // $totalworkall = $totalworkall + $totalwork;
            // $gajipokokall = $gajipokokall + $gajipokok;
            // $overtimeall = $overtimeall + $overtime;
            // $tunjanganall = $tunjanganall + $tunjangan;
            // $bpjsTNKall = $bpjsTNKall + $bpjsTNK;
            // $bpjsPNSall = $bpjsPNSall + $bpjsPNS;
            // $bpjsKSHall = $bpjsKSHall + $bpjsKSH;
            // $totalall = $totalall + $total;
            // $subTOINVall = $subTOINVall + $subTOINV;
            // $managamentFEEall = $managamentFEEall + $managamentFEE;
            // $ppn11all = $ppn11all + $ppn11;
            // $pph23all = $pph23all + $pph23;
            // $totalIVCall = $totalIVCall + $totalIVC;
            // $paidBPTRall = $paidBPTRall + $paidBPTR;
        }
// exit();
        $lastrow = $rowIdx + 1;
        
        $spreadsheet->getActiveSheet()
            ->setCellValue('A'.($rowIdx+2), "TOTAL OF INVOICE")
            ->setCellValue('G'.($rowIdx+2), "=SUM(G11:G$lastrow)")
            ->setCellValue('H'.($rowIdx+2), "=SUM(H11:H$lastrow)")
            ->setCellValue('I'.($rowIdx+2), "=SUM(I11:I$lastrow)")
            ->setCellValue('J'.($rowIdx+2), "=SUM(J11:J$lastrow)")
            ->setCellValue('K'.($rowIdx+2), "=SUM(K11:K$lastrow)")
            ->setCellValue('L'.($rowIdx+2), "=SUM(L11:L$lastrow)")
            ->setCellValue('M'.($rowIdx+2), "=SUM(M11:M$lastrow)")
            ->setCellValue('N'.($rowIdx+2), "=SUM(N11:N$lastrow)")
            ->setCellValue('O'.($rowIdx+2), "=SUM(O11:O$lastrow)")
            ->setCellValue('P'.($rowIdx+2), "=SUM(P11:P$lastrow)")
            ->setCellValue('Q'.($rowIdx+2), "=SUM(Q11:Q$lastrow)")
            ->setCellValue('R'.($rowIdx+2), "=SUM(R11:R$lastrow)")
            ->setCellValue('S'.($rowIdx+2), "=SUM(S11:S$lastrow)")
            ->setCellValue('T'.($rowIdx+2), "=SUM(T11:T$lastrow)")
            ->setCellValue('U'.($rowIdx+2), "=SUM(U11:U$lastrow)")  
            ->setCellValue('V'.($rowIdx+2), "=SUM(V11:V$lastrow)")  
            ;
        $spreadsheet->getActiveSheet()->mergeCells("A11:V11");
        $spreadsheet->getActiveSheet()->mergeCells("A".($rowIdx+1).":V".($rowIdx+1));
        $spreadsheet->getActiveSheet()->mergeCells("A".($rowIdx+2).":F".($rowIdx+2));
        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+2))->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+2).":F".($rowIdx+2))->getFont()->setBold(true)->setSize(12);

        $spreadsheet->getActiveSheet()->getStyle('F8:V'.($rowIdx+2))->getNumberFormat()->setFormatCode('#,##0.00');       
        
        $spreadsheet->getActiveSheet()->getStyle("A".($rowIdx+2).":V".($rowIdx+2))
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B');

        $spreadsheet->getActiveSheet()->getStyle("A6:V".($rowIdx+2))->applyFromArray($allBorderStyle);
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle($monthPeriod.'-'.$yearPeriod.'Invoice');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        $str = $dept.$monthPeriod.$yearPeriod.'Summary';
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

    public function summaryTimesheet($yearPeriod, $monthPeriod, $dept)
    {
        $depart = str_replace("%20"," ",$dept);
        $model = new M_tr_timesheet();
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
        // $dataId = $rpDb->getSlipIdByDate(); 
        $tgl3 = strtotime($startDate); 
        $tgl4 = strtotime($endDate); 
        $jarak = $tgl3 - $tgl4;
        $hari = $jarak / 60 / 60 / 24;
        $hari = str_replace("-","",$hari);
        $daycount =  $hari+16;
        $formattedDate = date('Y M d', strtotime($startDate));
        $formattedEndDate = date('Y M d', strtotime($endDate));
        // echo $endDate;
        // echo $daycount;
        // exit();
        // foreach ($dataId as $row) {
            // $slipId = $row['slip_id'];
            $data['yearPeriod'] = $yearPeriod;
            $data['monthPeriod'] = $monthPeriod;
            $data['startDate'] = $startDate;
            $data['endDate'] = $endDate;
            $data['daycount'] = $daycount;
            $data['dept'] = $depart;
            $data['dateHeader'] = 'Periode : '.$formattedDate.' - '.$formattedEndDate;
            // $data['dataBySlipId'] = $rpDb->getDataBySlipId($slipId);
            // $data['dataDbByDate'] = $rpDb->getDataDbByDate($slipId);
            return view('Report/summary_timesheet',$data);
        // }
    }


    public function exportNap($yearPeriod, $monthPeriod)
    {
        $summary = new M_tr_timesheet();
        $data = $summary->NapData($yearPeriod, $monthPeriod);
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

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath('./assets/images/logo.png');
        $drawing->setCoordinates('F1');
        $drawing->setHeight(72);
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        // foreach(range('B','R') as $columnID)
        // {
        //     $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        // }
        // Add some data
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5.14);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12.29);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(4.29);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40.43);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(39.29);
        // $spreadsheet->getActiveSheet()->getStyle('A6:V10')
        //     ->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()
        //     ->setRGB('ffcc00'); 
        $endMonthPeriod = $monthPeriod + 1;
        $endYearPeriod = $yearPeriod;
        if ($endMonthPeriod > 12) {
            $endMonthPeriod = $endMonthPeriod - 12;
            $endYearPeriod = $yearPeriod+1;
        }
        
        $spreadsheet->getActiveSheet()
            ->setCellValue('A2', 'INVOICE')
            ->setCellValue('A3', 'To:')
        ;

        // $spreadsheet->getActiveSheet()->mergeCells("A1:V1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:F2");
        $spreadsheet->getActiveSheet()->mergeCells("A3:F3");

        // $spreadsheet->getActiveSheet()->getStyle("A1:F1")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A2:F2")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A31:B32")->applyFromArray($left);
        $spreadsheet->getActiveSheet()->getStyle("C31:F32")->applyFromArray($left);
        // $spreadsheet->getActiveSheet()->getStyle("A1:V1")->getFont()->setBold(true)->setSize(24);
        $spreadsheet->getActiveSheet()->getStyle("A2:F2")->getFont()->setBold(true)->setSize(36)->setUnderline(true);
        $spreadsheet->getActiveSheet()->getStyle("A3:F3")->getFont()->setBold(true)->setSize(11);
        $spreadsheet->getActiveSheet()->getStyle("A2")->getFont()->getColor()->setARGB('0779D1');
        // $spreadsheet->getActiveSheet()->getStyle("A4:E4")->getFont()->setBold(true)->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle("B5:F7")->getFont()->setSize(11);
        $spreadsheet->getActiveSheet()->getStyle("E4:F4")->getFont()->setSize(11);
        $spreadsheet->getActiveSheet()->getStyle("B4")->getFont()->setBold(true)->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle("B8")->getFont()->setUnderline(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("E9")->getFont()->setBold(true)->setSize(11);
        $spreadsheet->getActiveSheet()->getStyle("E10")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("A12:F12")->getFont()->setBold(true)->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle("A13:F16")->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("A28:A31")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("A34:F43")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("C31:F32")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("A48")->getFont()->setBold(true)->setSize(12);

        $spreadsheet->getActiveSheet()->getStyle('A12:F12')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('FFDE8E'); 

        $spreadsheet->getActiveSheet()
            ->setCellValue('B4', 'PT.AGINCOURT RESOURCES')
            ->setCellValue('B5', 'Jl. Merdeka Barat Km 2,5')
            ->setCellValue('B6', 'Desa Aek Pining, Batangtoru')
            ->setCellValue('B7', 'Tapanuli Selatan')
            ->setCellValue('B8', 'Sumatera Utara - Indonesia (22738)')
            ->setCellValue('E4', 'Invoice No.')
            ->setCellValue('E5', 'Date')
            ->setCellValue('E6', 'Service No.')
            ->setCellValue('E9', 'Attn.  Ibu Sandra V. Makadada')
            ->setCellValue('E10', '          (Sr. Manager- Human Capital Development)')
        ;

        $spreadsheet->getActiveSheet()->getStyle("C31:F32")->getAlignment()->setWrapText(true);
            
        $spreadsheet->getActiveSheet()->getStyle("A12:F30")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A31:F32")->applyFromArray($outlineBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A12:F12")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A28:F30")->applyFromArray($right);
        $spreadsheet->getActiveSheet()->mergeCells("B12:E12");
        $spreadsheet->getActiveSheet()->mergeCells("B13:E13");
        $spreadsheet->getActiveSheet()->mergeCells("B14:E14");
        $spreadsheet->getActiveSheet()->mergeCells("B15:E15");
        $spreadsheet->getActiveSheet()->mergeCells("B16:E16");
        $spreadsheet->getActiveSheet()->mergeCells("B17:E17");
        $spreadsheet->getActiveSheet()->mergeCells("B18:E18");
        $spreadsheet->getActiveSheet()->mergeCells("B19:E19");
        $spreadsheet->getActiveSheet()->mergeCells("B20:E20");
        $spreadsheet->getActiveSheet()->mergeCells("B21:E21");
        $spreadsheet->getActiveSheet()->mergeCells("B22:E22");
        $spreadsheet->getActiveSheet()->mergeCells("B23:E23");
        $spreadsheet->getActiveSheet()->mergeCells("B24:E24");
        $spreadsheet->getActiveSheet()->mergeCells("B25:E25");
        $spreadsheet->getActiveSheet()->mergeCells("B26:E26");
        $spreadsheet->getActiveSheet()->mergeCells("B27:E27");
        $spreadsheet->getActiveSheet()->mergeCells("A28:E28");
        $spreadsheet->getActiveSheet()->mergeCells("A29:E29");
        $spreadsheet->getActiveSheet()->mergeCells("A30:E30");
        $spreadsheet->getActiveSheet()->mergeCells("A31:B32");
        $spreadsheet->getActiveSheet()->mergeCells("C31:F32");
        $totalBasicSalary = 0;
        $dataByDept = $summary->NapDataBsalary($monthPeriod, $yearPeriod);
        foreach ($dataByDept as $rowDept) {
            $basicSalary = $rowDept['monthly'];
            // $spreadsheet->getActiveSheet()->setCellValue('E'.($rowIdx), $row['total_work_day']);
            // echo $basicSalary.'<br>';
            $totalBasicSalary = $totalBasicSalary + $basicSalary;
        }
        // dd($data);
        $totalTunjangan = $data['total_tunjangan'];
        $totalOtValue = $data['total_ot_value'];
        $totalEmp = $data['total_emp'];
        $bpjsTenaga = $totalBasicSalary * 0.0574;
        $bpjsKes = $totalBasicSalary * 0.04;
        $bpjsPen = $totalBasicSalary * 0.02;
        $subTotalInv = $totalBasicSalary + $totalTunjangan + $totalOtValue + $bpjsTenaga+ $bpjsKes + $bpjsPen;
        $managementFee = ($totalBasicSalary + $totalTunjangan + $totalOtValue) * 0.093;
        $angka = $subTotalInv + $managementFee + ($managementFee * 0.011);
        // echo $totalBasicSalary.'<br>'.$totalOtValue.'<br>'.$totalEmp.'<br>'.$bpjsTenaga.'<br>'.$bpjsKes.'<br>'.$bpjsPen.'<br>'.$subTotalInv.'<br>'.$managementFee;
        $terbilang = ucwords(strtolower($this->terbilang($angka)));

        $spreadsheet->getActiveSheet()
            ->setCellValue('A12', 'NO')
            ->setCellValue('A13', '1')
            ->setCellValue('A15', '2')
            ->setCellValue('A28', 'SUB TOTAL')
            ->setCellValue('A29', 'VAT 11%')
            ->setCellValue('A30', 'TOTAL')
            ->setCellValue('A31', 'Terbilang')
            ->setCellValue('B12', 'DESKRIPSI')
            ->setCellValue('B13', 'Gaji Tenaga Kerja di PT AGINCOURT RESOURCES - Martabe Mine Site')
            ->setCellValue('B14', 'Periode 16 Feb 2023 - 15 Mar 2023')
            ->setCellValue('B15', 'Management Fee Gaji Tenaga Kerja di PT AGINCOURT RESOURCES - Martabe ')
            ->setCellValue('B16', 'Periode 16 Feb 2023 - 15 Mar 2023')
            ->setCellValue('C31', $terbilang)
            ->setCellValue('F12', 'NILAI')
            ->setCellValue('F13', $subTotalInv)
            ->setCellValue('F15', $managementFee)
            ->setCellValue('F28', '=SUM(F13:F27)')
            ->setCellValue('F29', '=F15*11%')
            ->setCellValue('F30', '=SUM(F28:F29)')
            ;


        $spreadsheet->getActiveSheet()
            ->setCellValue('A34', 'Daftar Rekapitulasi Invoice Terlampir')
            ->setCellValue('A36', 'Mohon Pembayaran dapat di Transfer ke Rekening dibawah ini :')
            ->setCellValue('A38', 'PT. SANGATI SOERYA SEJAHTERA')
            ->setCellValue('A39', 'PT. Bank Negara Indonesia (Persero) Tbk.')
            ->setCellValue('A40', 'KCP Grand Indonesia')
            ->setCellValue('A41', 'Shopping Town, East Mall 5th Flooor')
            ->setCellValue('A42', 'Jln. M.H. Thamrin No. 1 - Jakarta Pusat (10310)')
            ->setCellValue('A43', 'Nomor Rekening  :  7 3 7 3 7 8 8 7 8 8 (IDR)')
            ->setCellValue('A47', 'Hormat kami,')
            ->setCellValue('A48', 'PT. SANGATI SOERYA SEJAHTERA')
            ->setCellValue('F40', 'Swift Code: BNINIDJA')
            ;

        $spreadsheet->getActiveSheet()->getStyle('A31:F32')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('FFDE8E'); 
            
            

        $spreadsheet->getActiveSheet()->getStyle('F13:F30')->getNumberFormat()->setFormatCode('#,##0.00'); 
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle($monthPeriod.'-'.$yearPeriod.'NAP');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        $str = $monthPeriod.'-'.$yearPeriod.'NAP';
        $fileName = preg_replace('/\s+/', '', $str);
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

    public function dbInvoice($yearPeriod, $monthPeriod)
    {
        $rpDb = new M_rp_db();
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
        $rpDb->setstartDate($startDate);
        $rpDb->setendDate($endDate);
        // $dataId = $rpDb->getSlipIdByDate(); 
        $tgl3 = strtotime($startDate); 
        $tgl4 = strtotime($endDate); 
        $jarak = $tgl3 - $tgl4;
        $hari = $jarak / 60 / 60 / 24;
        $hari = str_replace("-","",$hari);
        $daycount =  $hari+16;
        // echo $endDate;
        // echo $daycount;
        // exit();
        // foreach ($dataId as $row) {
            // $slipId = $row['slip_id'];
            $data['start'] = 16;
            $data['dataId'] = $rpDb->getSlipIdByDate(); 
            $data['yearPeriod'] = $yearPeriod;
            $data['SM'] = '';
            $data['monthPeriod'] = $monthPeriod;
            $data['startDate'] = $startDate;
            $data['endDate'] = $endDate;
            $data['daycount'] = $daycount;
            // $data['dataBySlipId'] = $rpDb->getDataBySlipId($slipId);
            // $data['dataDbByDate'] = $rpDb->getDataDbByDate($slipId);
            return view('Report/db_download',$data);
        // }
    }


}