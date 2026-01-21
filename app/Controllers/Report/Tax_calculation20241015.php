<?php namespace App\Controllers\Report;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Transaction\M_tr_timesheet;
use App\Models\Transaction\M_tr_slip;
use App\Models\Transaction\M_tax;
use App\Models\Master\M_dept;
use App\Models\Master\M_mt_biodata;
use App\Models\Report\M_tax_calculation;


class Tax_calculation extends BaseController
{
    public function index()
    {
        /* ***Using Valid Path */
        $data['actView'] = 'Report/v_tax_calculation';
        return view('home', $data);
    }

    public function mtbAr($year)
    {
    	$data['year'] = $year;
        return view('Report/mtbar_download',$data);
    }

    public function getMtbArList($tahun){
        $mTax       = new M_tax_calculation;
        $dataTax    = $mTax->getDataTableTax($tahun);

        $myData = array();
        foreach ($dataTax as $key => $row) 
        {
               $myData[] = array(
                $row['slip_id'],
                $row['full_name'],            
                $row['dept'],         
                $row['position']        
            );            
        }
          

        echo json_encode($myData);  
    }

    // https://sys.sangati.co/hr3s/reports/Tax/exportTaxCalculationLCPSumbawa/LCP_Sumbawa/2024/All

    public function exportTaxCalculation($yearPeriod)
    {   
        // Create new Spreadsheet object
        $summary = new M_tr_timesheet();
        $summary->setYearProcess($yearPeriod);
        // $summary->setMonthProcess($monthPeriod);
        $data = $summary->getDataBiodataTaxCalculation();

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
        $spreadsheet->getActiveSheet()->getStyle('A6:BE7')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B'); 

        
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1', 'TAX CALCULATION PT. SANGATI SOERYA SEJAHTERA ')
            ->setCellValue('A2', 'Employee Income Tax Calculation PT. Agincourt')
            ->setCellValue('A4', 'Periode : '.$yearPeriod);

        $spreadsheet->getActiveSheet()->mergeCells("A1:J1");
        $spreadsheet->getActiveSheet()->mergeCells("A2:J2");
        $spreadsheet->getActiveSheet()->mergeCells("A4:J4");

        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true)->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle("A2:D2")->getFont()->setBold(true)->setSize(14)->setUnderline(true);
        $spreadsheet->getActiveSheet()->getStyle("A4:H4")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("A6:BE7")->getFont()->setBold(true)->setSize(12);

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
        // $spreadsheet->getActiveSheet()->mergeCells("L6:L7");
        // $spreadsheet->getActiveSheet()->mergeCells("M6:M7");
        // $spreadsheet->getActiveSheet()->mergeCells("N6:N7");
        // $spreadsheet->getActiveSheet()->mergeCells("O6:O7");
        // $spreadsheet->getActiveSheet()->mergeCells("P6:P7");
        // $spreadsheet->getActiveSheet()->mergeCells("Q6:Q7");
        // $spreadsheet->getActiveSheet()->mergeCells("R6:R7");
        $spreadsheet->getActiveSheet()->mergeCells("S6:S7");
        $spreadsheet->getActiveSheet()->mergeCells("T6:T7");
        $spreadsheet->getActiveSheet()->mergeCells("U6:U7");
        // $spreadsheet->getActiveSheet()->mergeCells("V6:V7");
        // $spreadsheet->getActiveSheet()->mergeCells("W6:W7");
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
        $spreadsheet->getActiveSheet()->mergeCells("AL6:AL7");
        $spreadsheet->getActiveSheet()->mergeCells("AM6:AM7");
        $spreadsheet->getActiveSheet()->mergeCells("AN6:AN7");
        $spreadsheet->getActiveSheet()->mergeCells("AO6:AO7");
        $spreadsheet->getActiveSheet()->mergeCells("AP6:AP7");
        $spreadsheet->getActiveSheet()->mergeCells("AQ6:AQ7");
        $spreadsheet->getActiveSheet()->mergeCells("AR6:AR7");
        $spreadsheet->getActiveSheet()->mergeCells("AS6:AS7");
        $spreadsheet->getActiveSheet()->mergeCells("AT6:AT7");
        $spreadsheet->getActiveSheet()->mergeCells("AU6:AU7");
        $spreadsheet->getActiveSheet()->mergeCells("AV6:AV7");
        $spreadsheet->getActiveSheet()->mergeCells("AW6:AW7");
        $spreadsheet->getActiveSheet()->mergeCells("AX6:AX7");
        $spreadsheet->getActiveSheet()->mergeCells("AY6:AY7");
        $spreadsheet->getActiveSheet()->mergeCells("AZ6:AZ7");
        $spreadsheet->getActiveSheet()->mergeCells("BA6:BA7");
        $spreadsheet->getActiveSheet()->mergeCells("BB6:BB7");
        $spreadsheet->getActiveSheet()->mergeCells("BC6:BC7");
        $spreadsheet->getActiveSheet()->mergeCells("BD6:BD7");
        $spreadsheet->getActiveSheet()->mergeCells("BE6:BE7");

        $spreadsheet->getActiveSheet()->mergeCells("L6:R6");
        $spreadsheet->getActiveSheet()->mergeCells("V6:W6");
            
        $spreadsheet->getActiveSheet()->getStyle("A6:BE7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A6:BE7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()
            ->setCellValue('A6', 'NO')
            ->setCellValue('B6', 'Periode Cut Off Timesheet')
            ->setCellValue('C6', 'NAMA')
            ->setCellValue('D6', 'EMPL Code')
            ->setCellValue('E6', 'Nomor KTP')
            ->setCellValue('F6', 'STATUS')
            ->setCellValue('G6', 'FIRST MONTH')
            ->setCellValue('H6', 'LAST MONTH')
            ->setCellValue('I6', 'Number Of Month')
            ->setCellValue('J6', 'Bulan Ke')
            ->setCellValue('K6', 'BASIC SALARY')

            ->setCellValue('L6', 'REGULER INCOME')

            ->setCellValue('L7', 'BASIC SALARY PRORATE')
            ->setCellValue('M7', 'OVERTIME TOTAL')
            ->setCellValue('N7', 'OTHER ALLOWANCE')
            ->setCellValue('O7', 'THR')
            ->setCellValue('P7', 'NIGHT SHIFT')
            ->setCellValue('Q7', 'DAY SHIFT')
            ->setCellValue('R7', 'LAIN - LAIN')
            ->setCellValue('S6', 'JKK & JKM')
            ->setCellValue('T6', 'HEALTH BPJS')
            ->setCellValue('U6', 'POT ABSENSI')
            ->setCellValue('V6', 'TOTAL REGULER INCOME')

            ->setCellValue('W6', 'NON-REGULER INCOME')

            ->setCellValue('W7', 'THR')
            ->setCellValue('X7', 'LAIN - LAIN')
            ->setCellValue('Y6', 'TOTAL NON-REGULER INCOME')
            ->setCellValue('Z6', 'TOTAL INCOME THIS MONTH')
            ->setCellValue('AA6', 'GROSS INCOME PREVIOUSLY IDR')
            ->setCellValue('AB6', 'TOTAL INCOME YTD IDR')
            ->setCellValue('AC6', 'FUNCTIONAL COST')
            ->setCellValue('AD6', 'FUNCTIONAL COST DEDUCTION')
            ->setCellValue('AE6', 'BPJS PENSIUN 1%')
            ->setCellValue('AF6', 'BPJS PENSIUN PREVIOUSLY')
            ->setCellValue('AG6', 'BPJS PENSIUN YTD')
            ->setCellValue('AH6', 'BPJS JHT 2%')
            ->setCellValue('AI6', 'BPJS JHT PREVIOUSLY')
            ->setCellValue('AJ6', 'BPJS JHT YTD')
            ->setCellValue('AK6', 'TOTAL DEDUCTION')
            ->setCellValue('AL6', 'ANNUALISATION')
            ->setCellValue('AM6', 'PTKP DEDUCTION')
            ->setCellValue('AN6', 'TAXABLE INCOME')
            ->setCellValue('AO6', 'Tax Imposition Base On Reg Income (Rounding)')
            ->setCellValue('AP6', 'Tax Due Income Per Annum')
            ->setCellValue('AQ6', 'Kelompok TER')
            ->setCellValue('AR6', '% TER')
            ->setCellValue('AS6', 'Total TER Tax Due this Month')
            ->setCellValue('AT6', 'Tax Already Paid')
            ->setCellValue('AU6', 'Tax Due Paid YTD')
            ->setCellValue('AV6', 'Tax This Month IDR')
            ->setCellValue('AW6', 'Tax Penalty')
            ->setCellValue('AX6', 'Tax Borne by Employee')
            ->setCellValue('AY6', 'JKK & JKM')
            ->setCellValue('AZ6', 'BPJS - Kes 1%')
            ->setCellValue('BA6', 'JHT 2%')
            ->setCellValue('BB6', 'BPJS - Kes. 4%')
            ->setCellValue('BC6', 'JP 1%')
            ->setCellValue('BD6', 'Gaji / Bonus yang sudah Dibayarkan')
            ->setCellValue('BE6', 'Take Home Pay')

            // ->setCellValue('W6', 'POTONGAN ABSENSI')
            // ->setCellValue('X6', 'GROSS')
            // ->setCellValue('Y6', 'GOLONGAN')
            // ->setCellValue('Z6', 'PERSENTASE')
            // ->setCellValue('AA6', 'TAX TER THIS MONTH')
            // ->setCellValue('AB6', 'JKK & JKM')
            // ->setCellValue('AC6', 'EMP HEALTH BPJS')
            // ->setCellValue('AD6', 'EMP JHT')
            // ->setCellValue('AE6', 'EMP JP')
            // ->setCellValue('AF6', 'ADJUSTMENT / DEDUCTION')
            // ->setCellValue('AG6', 'TOTAL')
            // ->setCellValue('AH6', 'ROUNDED')
            // ->setCellValue('AI6', 'NET PAYMENT')
            // ->setCellValue('AJ6', 'DUE DATE')
        ;
            

        /* START GET DAYS TOTAL BY ROSTER */
        $rowIdx = 7;
        $rowNo  = 1;
        $no     = 0;

        $models                    = new M_tr_slip(); 
        $modelBio                  = new M_mt_biodata();
        $model                     = new M_tax(); 
        $count_bulan                = 0;
        
        $biodataId_new  = '';
        $loopID             = 0;

        foreach ($data as $row) {        
            // dd($row);
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
            if($row['biodata_id'] != $loopID){
                $no                 = $rowNo++;
                $count_bulan        = 0;
                $start_bulan        = $this->namabulan($row['month_period']).' '.$row['year_period'];

                $gross_income_previously    = 0;
                $total_income_ytd_idr       = 0;
                $emp_jp_prev                = 0;
                $emp_jp_ytd                 = 0;
                $emp_jht_prev               = 0;
                $emp_jht_ytd                = 0;
                $total_deduction            = 0;
                $annualisation              = 0;
                $totalTer                   = 0;
                $taxDuePaidYTD              = 0;

            }else{
                $no                 = '';
            }

            $end_bulan              = $this->namabulan($row['month_period']).' '.$row['year_period'];

            if($row['check_final']=='END'){
                $nom                = (int)$row['month_period'];
            }else{
                $nom                = 12;    
            }

            $count_bulan++;

            $loopID         = $row['biodata_id'];

            $biodataId      = $row['biodata_id'];
            $dataSum        = $summary->getSumDataSMTaxCalculation($biodataId,$row['year_period'],$row['month_period']);
            $dataDetail     = $summary->getSumDataDetailTaxCalculation($biodataId,$row['year_period'],$row['month_period']);
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

            $month_start_periode    = date('m', strtotime('-1 month', strtotime($row['year_period'].'-'.$row['month_period'].'-16')));
            $year_start_periode     = date('Y', strtotime('-1 month', strtotime($row['year_period'].'-'.$row['month_period'].'-16')));

            $start_periode          = '16 '.$this->namabulan($month_start_periode).' '.$year_start_periode;
            $end_periode            = '15 '.$this->namabulan($row['month_period']).' '.$row['year_period'];

            $cutPeriod = $start_periode.' - '.$end_periode;
            
            $total_reguler_income   = $dataSum['basic_prorate']+$dataSum['total_overtime']+$dataSum['other_allowance']+$dataSum['thr']+$dataSum['night_shift']
                                        +$dataSum['day_shift']+$dataSum['jkkjkm']+$dataSum['bpjs']- $dataSum['unpaid'];

            $total_nonreguler_income= $dataSum['thr'];

            $total_income_this_month= $total_reguler_income+$total_nonreguler_income;

            $total_income_ytd_idr   = $total_income_this_month+$total_income_ytd_idr;

            $tunjangan_jabatan = 0;
            $max_biaya_jabatan = 0;

            $emp_jp             = $dataSum['emp_jp'];
            $emp_jp_ytd         = $emp_jp_ytd+$emp_jp;

            $emp_jht            = $dataSum['emp_jht'];
            $emp_jht_ytd        = $emp_jht_ytd+$emp_jht;
            
            $taxable_income     = 0;
            $tax_imposition_base_reg = 0;
            $tax_due_income_per_annum= 0;
            $taxPinalty = 0;

            if ($row['month_period'] >= 12 OR $row['check_final']=='END'){

                // $golongan  = '';
                $tarif     = ''; 

                $max_biaya_jabatan  = 500000*$count_bulan;

                $tunjangan_jabatan  = $total_income_ytd_idr * 0.05;
                if($tunjangan_jabatan > $max_biaya_jabatan){
                    $tunjangan_jabatan  = $max_biaya_jabatan;
                }

                $total_deduction        = $tunjangan_jabatan + $emp_jp_ytd + $emp_jht_ytd;
                $annualisation          = $total_income_ytd_idr - $total_deduction;

                $taxable_income         = $annualisation - $ptkpTotal;

                if($taxable_income <= 0) {
                    $taxable_income         = 0;
                }else{
                    $tax_imposition_base_reg   = round($taxable_income / 1000 - 0.5, 0) * 1000;
                }

                $maxPajak1 = $tax_imposition_base_reg - 60000000;
                if ($penghasilanPajakBulat > 60000000) {
                    $taxVal2 = $maxPajak1 * 0.15 ;
                }
                if ($penghasilanPajakBulat >= 60000000 ) {
                    $taxVal1 = 3000000;     
                }

                $maxPajak2 = $maxPajak1 - 190000000;
                if ($maxPajak1 >= 190000000) {
                    $taxVal2 = 28500000;
                }

                if ($maxPajak1 > 190000000) {
                    $taxVal3 = ($maxPajak1 - 190000000) * 0.25;
                }

                $maxPajak3 = $maxPajak2 - 250000000;
                if ($maxPajak2 >= 250000000) {
                    $taxVal3 = 62500000;
                }       
                if ($maxPajak2 > 250000000) {
                    $taxVal4 = ($maxPajak2 - 250000000) * 0.30;
                }
                $maxPajak4 = $maxPajak3 - 4500000000;
                if ($maxPajak3 > 4500000000 ) {
                    $taxVal4 = 1350000000;
                }
                if ($maxPajak4 > 4500000000) {
                    $taxVal5 = ($maxPajak3 - 4500000000) * 0.35;
                }

                $tax_due_income_per_annum  = $taxVal1 + $taxVal2 + $taxVal3 + $taxVal4 + $taxVal5;
            
                $taxThisMonth = $tax_due_income_per_annum - $totalTer;

                if (strlen($row['tax_no']) <= 19) {
                    $taxPinalty = $tax_due_income_per_annum * (20 / 100);
                    $taxThisMonthEmployee = $taxThisMonth + $taxPinalty;
                }
                $taxBorneByEmployee = $taxThisMonthEmployee;

            } else {
                $brutto                = $dataSum['total_kotor'];
                $marital               = $row['marital_status'];
                $data                  = $model->getDataTer($brutto, $marital);
                $pajakTER              = floor($brutto * ($data->tarif / 100));
                $taxThisMonth          = $pajakTER;
                $tarif                 = $data->tarif;
                $golongan              = $data->golongan;
                $taxBorneByEmployee = $taxThisMonth;
                $totalTer += $pajakTER;
            }

            $taxAlreadyPaid = $taxDuePaidYTD;
            $taxDuePaidYTD  = $pajakTER + $taxAlreadyPaid;

            $gajiSudahDiBayar = 0;
            $emp_bpjs       = $emp_jp;
            // Take Home Pay
            $takeHomePay = $total_income_this_month-($taxBorneByEmployee+$dataSum['bpjs']+$emp_bpjs+$dataSum['jkkjkm']+$emp_jp+$emp_jht)+$gajiSudahDiBayar;

            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.($rowIdx), $no)
                ->setCellValue('B'.($rowIdx), $cutPeriod)
                ->setCellValue('C'.($rowIdx), $row['full_name'])
                ->setCellValue('D'.($rowIdx), $row['biodata_id'])
                // ->setCellValue('E'.($rowIdx), $row['tax_no'])
                ->setCellValueExplicit('E' . $rowIdx, $row['id_card_no'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValue('F'.($rowIdx), $row['marital_status'])
                ->setCellValue('G'.($rowIdx), $start_bulan)
                ->setCellValue('H'.($rowIdx), $end_bulan)
                ->setCellValue('I'.($rowIdx), $nom)
                ->setCellValue('J'.($rowIdx), $count_bulan)
                
                ->setCellValue('K'.($rowIdx), $row['monthly'])
                ->setCellValue('L'.($rowIdx), $dataSum['basic_prorate'])
                ->setCellValue('M'.($rowIdx), $dataSum['total_overtime'])
                ->setCellValue('N'.($rowIdx), $dataSum['other_allowance'])
                ->setCellValue('O'.($rowIdx), $dataSum['thr'])
                ->setCellValue('P'.($rowIdx), $dataSum['night_shift'])
                ->setCellValue('Q'.($rowIdx), $dataSum['day_shift'])
                ->setCellValue('R'.($rowIdx), '')

                ->setCellValue('S'.($rowIdx), $dataSum['jkkjkm'])
                ->setCellValue('T'.($rowIdx), $dataSum['bpjs'])
                ->setCellValue('U'.($rowIdx), $dataSum['unpaid'])
                ->setCellValue('V'.($rowIdx), $total_reguler_income)

                ->setCellValue('W'.($rowIdx), $dataSum['thr'])
                ->setCellValue('X'.($rowIdx), '')
                ->setCellValue('Y'.($rowIdx), $total_nonreguler_income)

                ->setCellValue('Z'.($rowIdx), $total_income_this_month)
                ->setCellValue('AA'.($rowIdx), $gross_income_previously)
                ->setCellValue('AB'.($rowIdx), $total_income_ytd_idr)
                ->setCellValue('AC'.($rowIdx), $tunjangan_jabatan)
                ->setCellValue('AD'.($rowIdx), $tunjangan_jabatan)
                ->setCellValue('AE'.($rowIdx), $emp_jp)
                ->setCellValue('AF'.($rowIdx), $emp_jp_prev)
                ->setCellValue('AG'.($rowIdx), $emp_jp_ytd)
                ->setCellValue('AH'.($rowIdx), $emp_jht)
                ->setCellValue('AI'.($rowIdx), $emp_jht_prev)
                ->setCellValue('AJ'.($rowIdx), $emp_jht_ytd)
                ->setCellValue('AK'.($rowIdx), $total_deduction)
                ->setCellValue('AL'.($rowIdx), $annualisation)
                ->setCellValue('AM'.($rowIdx), $ptkpTotal)
                ->setCellValue('AN'.($rowIdx), $taxable_income)
                ->setCellValue('AO'.($rowIdx), $tax_imposition_base_reg)
                ->setCellValue('AP'.($rowIdx), $tax_due_income_per_annum)
                ->setCellValue('AQ'.($rowIdx), $golongan)
                ->setCellValue('AR'.($rowIdx), $tarif)
                // ->setCellValueExplicit('AQ' . $rowIdx, $tarif.' ', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                ->setCellValue('AS'.($rowIdx), $pajakTER)
                ->setCellValue('AT'.($rowIdx), $taxAlreadyPaid)
                ->setCellValue('AU'.($rowIdx), $taxDuePaidYTD)
                ->setCellValue('AV'.($rowIdx), $taxThisMonth)
                ->setCellValue('AW'.($rowIdx), $taxPinalty)
                ->setCellValue('AX'.($rowIdx), $taxBorneByEmployee)

                ->setCellValue('AY'.($rowIdx), $dataSum['jkkjkm'] )
                ->setCellValue('AZ'.($rowIdx), $emp_bpjs)
                ->setCellValue('BA'.($rowIdx), $emp_jht)
                ->setCellValue('BB'.($rowIdx), $dataSum['bpjs'])
                ->setCellValue('BC'.($rowIdx), $emp_jp)
                ->setCellValue('BD'.($rowIdx), $gajiSudahDiBayar)
                ->setCellValue('BE'.($rowIdx), $takeHomePay)  
                ;     

            /* SET ROW COLOR */
            if($rowIdx % 2 == 1)
            {
                $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':BE'.$rowIdx)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('EAEBAF');             
            } 

            $gross_income_previously    = $total_income_this_month;
            $emp_jp_prev                = $emp_jp+$emp_jp_prev;
            $emp_jht_prev               = $emp_jht+$emp_jht_prev;

            // $biodataId_new      = $row['biodata_id'];

        }
        // exit();
        $spreadsheet->getActiveSheet()->setCellValue('AI'.($rowIdx+2), date('Y-m-d H:i:s'));

        // $spreadsheet->getActiveSheet()->getStyle("A6:AJ".($rowIdx))->applyFromArray($allBorderStyle);
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Report Excel '.date('d-m-Y H'));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        $str = 'Tax Calculation '.$yearPeriod;
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

    public function namabulan($tanggal){
        switch($tanggal) {
            case '1': return "Jan";
                break;
            case '2': return "Feb";
                break;
            case '3': return "Mar";
                break;
            case '4': return "Apr";
                break;
            case '5': return "Mei";
                break;
            case '6': return "Jun";
                break;
            case '7': return "Jul";
                break;
            case '8': return "Agu";
                break;
            case '9': return "Sep";
                break;
            case '10': return "Okt";
                break;
            case '11': return "Nov";
                break;
            case '12': return "Des";
                break;
            case '0': return "Des";
                break;
        };
    }

    public function balikbulan($bulan){
        switch($bulan) {
            case '1': return "12";
                break;
            case '2': return "11";
                break;
            case '3': return "10";
                break;
            case '4': return "9";
                break;
            case '5': return "8";
                break;
            case '6': return "7";
                break;
            case '7': return "6";
                break;
            case '8': return "5";
                break;
            case '9': return "4";
                break;
            case '10': return "3";
                break;
            case '11': return "2";
                break;
            case '12': return "1";
                break;
        };
    }

}