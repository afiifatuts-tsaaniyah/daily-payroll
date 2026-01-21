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
use App\Models\Transaction\M_tax;
use App\Models\Master\M_mt_biodata;



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

        $data = $data_proses->getDataBiodataSM();
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
                $row['monthly']         
                // $row['totalot'],         
                // $row['thr'],         
                // $row['tax_non_reg'],        
                // $row['gaji'],         
                // $row['adjustment'],         
                // $row['totalgaji']
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
        $data = $summary->getDataBiodataSM();

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
        $spreadsheet->getActiveSheet()->getStyle('A6:AL7')
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
        $spreadsheet->getActiveSheet()->getStyle("A4:H4")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("A6:AL7")->getFont()->setBold(true)->setSize(12);

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
        $spreadsheet->getActiveSheet()->mergeCells("AH6:AH7");
        $spreadsheet->getActiveSheet()->mergeCells("AI6:AI7");
        $spreadsheet->getActiveSheet()->mergeCells("AJ6:AJ7");
        $spreadsheet->getActiveSheet()->mergeCells("AK6:AK7");
        $spreadsheet->getActiveSheet()->mergeCells("AK6:AK7");
        $spreadsheet->getActiveSheet()->mergeCells("AL6:AL7");
            
        $spreadsheet->getActiveSheet()->getStyle("A6:AL7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A6:AL7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()
            ->setCellValue('A6', 'NO')
            ->setCellValue('B6', 'YEAR')
            ->setCellValue('C6', 'MONTH')
            ->setCellValue('D6', 'START DATE')
            ->setCellValue('E6', 'SM')
            ->setCellValue('F6', 'DEPARTMENT')
            ->setCellValue('G6', 'EXTERNAL ID')
            ->setCellValue('H6', 'NAMA')
            ->setCellValue('I6', 'GENDER')
            ->setCellValue('J6', 'MARITAL STATUS')
            ->setCellValue('K6', 'NPWP')
            ->setCellValue('L6', 'POSITION')
            ->setCellValue('M6', 'ADDRESS')
            ->setCellValue('N6', 'BASIC SALARY')
            ->setCellValue('O6', 'BASIC SALARY PRORATE')
            ->setCellValue('P6', 'OVERTIME TOTAL')
            ->setCellValue('Q6', 'OTHER ALLOWANCE')
            ->setCellValue('R6', 'THR')
            ->setCellValue('S6', 'NIGHT SHIFT')
            ->setCellValue('T6', 'DAY SHIFT')
            ->setCellValue('U6', 'JKK & JKM')
            ->setCellValue('V6', 'HEALTH BPJS')
            ->setCellValue('W6', 'POTONGAN ABSENSI')
            ->setCellValue('X6', 'GROSS')
            ->setCellValue('Y6', 'GOLONGAN')
            ->setCellValue('Z6', 'PERSENTASE')
            ->setCellValue('AA6', 'TAX TER THIS MONTH')
            ->setCellValue('AB6', 'JKK & JKM')
            // ->setCellValue('AC6', 'HEALTH BPJS')
            ->setCellValue('AC6', 'EMP HEALTH BPJS')
            ->setCellValue('AD6', 'EMP JHT')
            ->setCellValue('AE6', 'EMP JP')
            ->setCellValue('AF6', 'ADJUSTMENT / DEDUCTION')
            ->setCellValue('AG6', 'TOTAL')
            ->setCellValue('AH6', 'ROUNDED')
            ->setCellValue('AI6', 'NET PAYMENT')
            ->setCellValue('AJ6', 'DUE DATE')
            ->setCellValue('AK6', 'STATUS EMP')
            ->setCellValue('AL6', 'NIK');
            

        /* START GET DAYS TOTAL BY ROSTER */
        $rowIdx = 7;
        $rowNo = 0;

        $models                    = new M_tr_slip(); 
        $modelBio                  = new M_mt_biodata();
        $model                     = new M_tax(); 

        

        foreach ($data as $row) {        
            $penghasilanPajak      = 0;
            $penghasilanPajakBulat = 0;
            $taxVal1               = 0;
            $taxVal2               = 0;
            $taxVal3               = 0;
            $taxVal4               = 0;
            $taxVal5               = 0;

            $taxPercent1           = "5%";
            $taxPercent2           = "15%";
            $taxPercent3           = "25%";
            $taxPercent4           = "30%";
            $taxPercent5           = "35%";

            $rowIdx++;
            $rowNo++;
            $biodataId      = $row['biodata_id'];
            $dataSum        = $summary->getSumDataSM($biodataId);
            // dd($dataSum);
            $dataDetail     = $summary->getSumDataDetail($biodataId);
            $dataSMTotal    = '';
            $dueDateTotal   = '';
            
            foreach ($dataDetail as $daw) {
                $dept = isset($daw['dept']) ? $daw['dept'] : '';
                $sm   = isset($daw['payroll_group']) ? $daw['payroll_group'] : '';
                $dataSM = $sm.'/'.$dept.'; ';
                $dataSMTotal = $dataSMTotal.$dataSM; 
                $dueDate = $daw['due_date'].';';
                $dueDateTotal = $dueDateTotal.' '.$dueDate;
            }
            $marital = $row['marital_status'];
            $npwp    = $row['tax_no'];

            if ($marital == "TK0") {
                $ptkpTotal = 54000000;
            }else if ($marital == "K0") {
                $ptkpTotal = 58500000;
            }else if ($marital == "K1") {
                $ptkpTotal = 63000000;
            }else if ($marital == "K2") {
                $ptkpTotal = 67500000;
            }else if ($marital == "K3") {
                $ptkpTotal = 72000000;
            }else{
                $ptkpTotal = 0;
            }

            $start_date         = $yearPeriod.'-'.$monthPeriod.'-16';
            $end_date           = date('Y-m-15', strtotime('+1 month', strtotime($start_date)));
            $pajak_final        = $models->getTotalPajak($row['biodata_id'],$start_date,$end_date)['pph21'];

            $end = '';
            if ($row['month_period'] == 11 OR $row['check_final'] == 'END') {
                // $golongan  = '';
                $tarif     = '0'; 
                

                // $data                  = $models->getDataTax($biodataId, $monthPeriod, $yearPeriod);
                // $dataBPJS              = $models->getDataBpjs($biodataId, $monthPeriod, $yearPeriod);
                // $dataBio               = $modelBio->getDataBio($biodataId);

                // $bruttoSetahuan        = $data->total_kotor;
                // $tax                   = $data->total_pph21;
                // $jp                    = $dataBPJS->total_jp;
                // $jht                   = $dataBPJS->total_jht;
                // $tunjanganJabatan      = $bruttoSetahuan * (5/100);

                // if ($tunjanganJabatan >= 6000000) {
                //     $tunjanganJabatan = 6000000;
                // }

                // $nettoSetahun     = $bruttoSetahuan - $jp - $jht - $tunjanganJabatan;


                // if ($nettoSetahun >= $ptkpTotal) {
                //     $penghasilanPajak = $nettoSetahun - $ptkpTotal;
                //     $penghasilanPajakBulat = round($penghasilanPajak,2);
                //     $penghasilanPajakBulat = round($penghasilanPajakBulat);
                //     $strTerima = substr($penghasilanPajakBulat,-3,6);
                //     // $thp = 0;
                //     if ($strTerima > 500) {
                //         $totalPembulatan = $penghasilanPajakBulat - $strTerima +1000;
                //         $bulat = $totalPembulatan - $ter;
                //     } 
                //     else if ($strTerima < 500){
                //         $totalPembulatan = $penghasilanPajakBulat - $strTerima;
                //         $bulat = $totalPembulatan - $ter;
                //     }
                //     else if ($strTerima == 500){
                //         $totalPembulatan = $penghasilanPajakBulat;
                //         $bulat = 0;
                //     }
                //     $taxVal1 = $penghasilanPajakBulat * 0.05;
                //     $taxVal2 = 0;
                //     $taxVal3 = 0;
                //     $taxVal4 = 0;
                //     $taxVal5 = 0;
                // }

                // $maxPajak1 = $penghasilanPajakBulat - 60000000;
                // if ($penghasilanPajakBulat > 60000000) {
                //     $taxVal2 = $maxPajak1 * 0.15 ;
                // }
                // if ($penghasilanPajakBulat >= 60000000 ) {
                //     $taxVal1 = 3000000;     
                // }

                // $maxPajak2 = $maxPajak1 - 190000000;
                // if ($maxPajak1 >= 190000000) {
                //     $taxVal2 = 28500000;
                // }

                // if ($maxPajak1 > 190000000) {
                //     $taxVal3 = ($maxPajak1 - 190000000) * 0.25;
                // }

                // $maxPajak3 = $maxPajak2 - 250000000;
                // if ($maxPajak2 >= 250000000) {
                //     $taxVal3 = 62500000;
                // }       
                // if ($maxPajak2 > 250000000) {
                //     $taxVal4 = ($maxPajak2 - 250000000) * 0.30;
                // }
                // $maxPajak4 = $maxPajak3 - 4500000000;
                // if ($maxPajak3 > 4500000000 ) {
                //     $taxVal4 = 1350000000;
                // }
                // if ($maxPajak4 > 4500000000) {
                //     $taxVal5 = ($maxPajak3 - 4500000000) * 0.35;
                // }

                // $pajakGajiSthn  = $taxVal1 + $taxVal2 + $taxVal3 + $taxVal4 + $taxVal5;

                // $pajakSebulan   = $pajakGajiSthn - $tax;
                // $taxPinalty     = 0;
                // if ($npwp == '') {
                //     $taxPinalty = $pajakSebulan * 0.20;
                // }

                // $pajak          = floor($pajakSebulan);

                $pajak              = $dataSum['pph21'];
                $end                = '(END)';

                // dd($total_pajak['pph21']);
                // exit();

                $spreadsheet->getActiveSheet()->setCellValue('Z'.($rowIdx), '');
            } else {
                $brutto                = $dataSum['total_kotor'];
                $marital               = $row['marital_status'];
                $data                  = $model->getDataTer($brutto, $marital);
                $pajak                 = floor($brutto * ($data->tarif / 100));
                $tarif                 = $data->tarif;
                $golongan              = $data->golongan;
                // echo $tarif;
                // exit();
                $spreadsheet->getActiveSheet()->setCellValueExplicit('Z'.($rowIdx), ($tarif / 100), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $spreadsheet->getActiveSheet()->getStyle('Z'.($rowIdx))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
            }

            $total_bersih   = number_format($dataSum['total_kotor'] - ($pajak+$dataSum['jkkjkm']+$dataSum['emp_bpjs']+$dataSum['emp_jht']+$dataSum['emp_jp']-($dataSum['adjustment'])),2,'.','');
            $tiga_digit     = substr($total_bersih,-6);
            // echo $tiga_digit;
            // exit();

            if($tiga_digit>500){
                $rounded        = 1000 - $tiga_digit;
                $t_rounded      = $rounded;
                $net_payment    = $total_bersih + $rounded;
            }else{
                $rounded        = $tiga_digit;
                $t_rounded      = $rounded*-1;
                $net_payment    = $total_bersih - $rounded;
            }

            // echo $dataSum['adjustment']."<br/>";
            
            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.($rowIdx), $rowNo)
                ->setCellValue('B'.($rowIdx), $row['year_period'])
                ->setCellValue('C'.($rowIdx), $row['month_period'])
                ->setCellValue('D'.($rowIdx), $row['slip_period'])
                ->setCellValue('E'.($rowIdx), $dataSMTotal)
                ->setCellValue('F'.($rowIdx), $row['dept'])
                ->setCellValue('G'.($rowIdx), $row['biodata_id'])
                ->setCellValue('H'.($rowIdx), $row['full_name'])
                ->setCellValue('I'.($rowIdx), $row['gender'])
                ->setCellValue('J'.($rowIdx), $row['marital_status'])
                ->setCellValue('K'.($rowIdx), $row['tax_no'])
                ->setCellValue('L'.($rowIdx), $row['position'])
                ->setCellValue('M'.($rowIdx), $row['id_card_address'])
                ->setCellValue('N'.($rowIdx), $row['gajipokok'])
                ->setCellValue('O'.($rowIdx), $dataSum['basic_prorate'])
                ->setCellValue('P'.($rowIdx), $dataSum['total_overtime'])
                ->setCellValue('Q'.($rowIdx), $dataSum['other_allowance'])
                ->setCellValue('R'.($rowIdx), $dataSum['thr'])
                ->setCellValue('S'.($rowIdx), $dataSum['night_shift'])
                ->setCellValue('T'.($rowIdx), $dataSum['day_shift'])
                ->setCellValue('U'.($rowIdx), $dataSum['jkkjkm'])
                ->setCellValue('V'.($rowIdx), $dataSum['bpjs'])
                ->setCellValue('W'.($rowIdx), -$dataSum['potongan_absensi'])       
                ->setCellValue('X'.($rowIdx), $dataSum['total_kotor'])
                ->setCellValue('Y'.($rowIdx), $golongan.''.$end.'')
                // ->setCellValue('Z'.($rowIdx), number_format($tarif,2,",",".").'%')
                ->setCellValue('AA'.($rowIdx), -$pajak)
                ->setCellValue('AB'.($rowIdx), -$dataSum['jkkjkm'])
                // ->setCellValue('AC'.($rowIdx), $dataSum['bpjs'])
                ->setCellValue('AC'.($rowIdx), -$dataSum['emp_bpjs'])
                ->setCellValue('AD'.($rowIdx), -$dataSum['emp_jht'])
                ->setCellValue('AE'.($rowIdx), -$dataSum['emp_jp'])
                ->setCellValue('AF'.($rowIdx), $dataSum['adjustment'])
                ->setCellValue('AG'.($rowIdx), $total_bersih)
                ->setCellValue('AH'.($rowIdx), $t_rounded)
                ->setCellValue('AI'.($rowIdx), $net_payment)     
                ->setCellValue('AJ'.($rowIdx), $dueDateTotal)     
                ->setCellValue('AK'.($rowIdx), $row['emp_status'])      
                ;     
                $id_card = '';
                if($row['id_card_no']){
                    $id_card = $row['id_card_no'];
                }
            $spreadsheet->getActiveSheet()->setCellValueExplicit('AL'.($rowIdx), $id_card, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            /* SET ROW COLOR */
            if($rowIdx % 2 == 1)
            {
                $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':AL'.$rowIdx)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('EAEBAF');             
            } 
        }
        // exit();
        $spreadsheet->getActiveSheet()->setCellValue('AL'.($rowIdx+2), date('Y-m-d H:i:s'));

        $spreadsheet->getActiveSheet()->getStyle("A6:AL".($rowIdx))->applyFromArray($allBorderStyle);
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Report Excel '.date('d-m-Y H'));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        $str = 'Summary Payroll'.$yearPeriod.$monthPeriod;
        $fileName = preg_replace('/\s+/', '', $str);
        // $str = 'PTLAgrInvoice';
        // $fileName = 'Summary Payroll PT.'.$ptName.'';
        // test($fileName,1);
        // Redirect output to a client�s web browser (Xlsx)
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