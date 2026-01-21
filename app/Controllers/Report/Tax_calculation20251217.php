<?php

namespace App\Controllers\Report;

use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Helpers\ConfigurationHelper;
use App\Models\Master\Client;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Transaction\M_tr_timesheet;
use App\Models\Transaction\M_tr_slip;
use App\Models\Transaction\M_tax;
use App\Models\Master\M_dept;
use App\Models\Master\M_mt_biodata;
use App\Models\Master\MtAllowance;
use App\Models\Report\M_tax_calculation;
use App\Models\Transaction\SalarySlip;
use App\Models\Transaction\Tax;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Tax_calculation extends BaseController
{
    public function index()
    {
        /* ***Using Valid Path */
        $session = session();
        $clients = $session->get('userClients');

        $data['clients'] = $clients;
        $data['actView'] = 'Report/v_tax_calculation';
        return view('home', $data);
    }

    public function mtbAr($year)
    {
        $data['year'] = $year;
        return view('Report/mtbar_download', $data);
    }

    public function getMtbArList($tahun, $clientName)
    {
        $mTax       = new M_tax_calculation;
        if ($clientName == 'Promincon_Indonesia') {
            $dataTax    = $mTax->getDataTableTaxByClient($tahun, $clientName);
        } else {
            $dataTax    = $mTax->getDataTableTax($tahun);
        }

        $myData = array();
        foreach ($dataTax as $key => $row) {
            $myData[] = array(
                $row['slip_id'],
                $row['full_name'],
                $row['dept'],
                $row['position']
            );
        }


        echo json_encode($myData);
    }



    public function exportAllTaxCalculation($yearPeriod, $clientName)
    {
        if ($clientName == 'Promincon_Indonesia') {
            return $this->exportTaxCalculationPromincon($yearPeriod, $clientName);
        } else {
            return $this->exportTaxCalculation($yearPeriod);
        }
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
            ->setCellValue('A4', 'Periode : ' . $yearPeriod);

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
        $spreadsheet->getActiveSheet()->mergeCells("V6:V7");
        // $spreadsheet->getActiveSheet()->mergeCells("W6:W7");
        // $spreadsheet->getActiveSheet()->mergeCells("X6:X7");
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
        $spreadsheet->getActiveSheet()->mergeCells("W6:X6");

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

            ->setCellValue('W7', 'LAIN - LAIN')
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
        $rowIdxStart = 8;
        $rowIdx = 7;
        $rowNo  = 1;
        $no     = 0;

        $models                    = new M_tr_slip();
        $modelBio                  = new M_mt_biodata();
        $model                     = new M_tax();
        $count_bulan                = 0;

        $biodataId_new  = '';
        $loopID             = 0;
        // dd($data);
        foreach ($data as $row) {
            // dd($row['biodata_id']);

            $query_cek_end         = $summary->getDataBiodataTaxCalculationYearId($row['biodata_id'], $yearPeriod);
            $query_cek_nom         = $summary->getDataBiodataTaxCalculationYearIdNom($row['biodata_id'], $yearPeriod);
            // dd($query_cek_nom);
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
            if ($row['biodata_id'] != $loopID) {
                $no                 = $rowNo++;
                $count_bulan        = 0;
                $start_bulan        = $this->namabulan($row['monthprocess']) . ' ' . $row['yearprocess'];

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
            } else {
                $no                 = '';
            }

            $end_bulan              = $this->namabulan($row['monthprocess']) . ' ' . $row['yearprocess'];

            // $pengurangan        = 13;
            // $start_kerja        = (int) $start_kerja;
            // $numOfMonth         = $pengurang - $start_kerja;

            if ($query_cek_end >= 1) {
                // $nom                = (int)$row['month_period'];
                $nom                = (int)$query_cek_end['month_period'];
            } else {
                $nom                = $query_cek_nom['jml_data'];
            }

            $count_bulan++;

            $loopID         = $row['biodata_id'];

            $biodataId      = $row['biodata_id'];
            // $dataSum        = $summary->getSumDataSMTaxCalculation($biodataId,$row['year_period'],$row['month_period']);
            $dataDetail     = $summary->getSumDataDetailTaxCalculation($biodataId, $row['year_period'], $row['month_period']);
            $dataSMTotal    = '';
            $dueDateTotal   = '';

            foreach ($dataDetail as $daw) {
                $dept = isset($daw['dept']) ? $daw['dept'] : '';
                $sm   = isset($daw['payroll_group']) ? $daw['payroll_group'] : '';
                $dataSM = $sm . '/' . $dept . '; ';
                $dataSMTotal = $dataSMTotal . $dataSM;
                $dueDate = $daw['due_date'] . ';';
                $dueDateTotal = $dueDateTotal . ' ' . $dueDate;
            }
            $marital = $row['marital_status'];
            $npwp    = $row['tax_no'];

            if ($marital == "TK0") {
                $ptkpTotal = 54000000;
            } else if ($marital == "K0") {
                $ptkpTotal = 58500000;
            } else if ($marital == "K1") {
                $ptkpTotal = 63000000;
            } else if ($marital == "K2") {
                $ptkpTotal = 67500000;
            } else if ($marital == "K3") {
                $ptkpTotal = 72000000;
            } else {
                $ptkpTotal = 0;
            }

            $month_start_periode    = date('m', strtotime('-1 month', strtotime($row['yearprocess'] . '-' . $row['monthprocess'] . '-16')));
            $year_start_periode     = date('Y', strtotime('-1 month', strtotime($row['yearprocess'] . '-' . $row['monthprocess'] . '-16')));

            $start_periode          = '16 ' . $this->namabulan($month_start_periode) . ' ' . $year_start_periode;
            $end_periode            = '15 ' . $this->namabulan($row['monthprocess']) . ' ' . $row['yearprocess'];

            $cutPeriod = $start_periode . ' - ' . $end_periode;

            $total_reguler_income   = $row['basic_prorate'] + $row['total_overtime'] + $row['other_allowance'] + $row['thr'] + $row['night_shift']
                + $row['day_shift'] + $row['jkkjkm'] + $row['bpjs'] - $row['potongan_absensi'];

            $total_nonreguler_income = 0;

            $total_income_this_month = $total_reguler_income + $total_nonreguler_income;

            $total_income_ytd_idr   = $total_income_this_month + $total_income_ytd_idr;

            $tunjangan_jabatan = $row['tunjangan_jabatan'];
            $max_biaya_jabatan = $row['tunjangan_jabatan'];

            $emp_jp             = $row['emp_jp'];
            $emp_jp_ytd         = $emp_jp_ytd + $emp_jp;

            $emp_jht            = $row['emp_jht'];
            $emp_jht_ytd        = $emp_jht_ytd + $emp_jht;

            $taxable_income     = 0;
            $tax_imposition_base_reg = 0;
            $tax_due_income_per_annum = 0;
            $taxPinalty = 0;

            if ($row['month_period'] == 11 or $row['check_final'] == 'END') {

                $dataFinal             = $summary->getDataFinalTax($row['year_period'], $row['biodata_id']);
                // dd($dataFinal);
                $golongan              = '';
                $tarif                 = 0;

                // $data                  = $model->getDataTax($biodata_id, $bulan, $tahun);
                // $dataBPJS              = $model->getDataBpjs($biodata_id, $bulan, $tahun);
                // $dataBio               = $modelBio->getDataBio($biodata_id);
                $maxTJabatan           = 500000 * $count_bulan;

                $bruttoSetahuan        = $dataFinal['tbruto'];
                $tax                   = $dataFinal['tpph21'];
                $jpjht                 = $dataFinal['tjhtjp'];
                $tunjanganJabatan      = $dataFinal['ttunjangan'];
                // $npwp                  = isset($dataBio['npwp_no']) ? $dataBio['npwp_no'] : '';
                // $tunjanganJabatan      = $bruttoSetahuan * (5 / 100);

                if ($tunjanganJabatan >= $maxTJabatan) {
                    $tunjanganJabatan  = $maxTJabatan;
                }

                $nettoSetahun          = $bruttoSetahuan - $jpjht - $tunjanganJabatan;


                if ($nettoSetahun >= $ptkpTotal) {
                    $penghasilanPajak           = $nettoSetahun - $ptkpTotal;
                    $penghasilanPajakBulatTer   = round($penghasilanPajak, 2);
                    $penghasilanPajakBulat      = round($penghasilanPajak);
                    $strTerima                  = substr($penghasilanPajakBulat, -3, 6);

                    if ($strTerima > 500) {
                        $totalPembulatan = $penghasilanPajakBulat - $strTerima + 1000;
                        $bulat           = $totalPembulatan - $penghasilanPajakBulatTer;
                    } elseif ($strTerima < 500) {
                        $totalPembulatan = $penghasilanPajakBulat - $strTerima;
                        $bulat           = $totalPembulatan - $penghasilanPajakBulatTer;
                    } elseif ($strTerima == 500) {
                        $totalPembulatan = $penghasilanPajakBulatTer;
                        $bulat           = 0;
                    }

                    $taxVal1 = $penghasilanPajakBulat * 0.05;
                    $taxVal2 = 0;
                    $taxVal3 = 0;
                    $taxVal4 = 0;
                    $taxVal5 = 0;
                }

                $maxPajak1   = $penghasilanPajakBulat - 60000000;
                if ($penghasilanPajakBulat > 60000000) {
                    $taxVal2 = $maxPajak1 * 0.15;
                }

                if ($penghasilanPajakBulat >= 60000000) {
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
                if ($maxPajak3 > 4500000000) {
                    $taxVal4 = 1350000000;
                }

                if ($maxPajak4 > 4500000000) {
                    $taxVal5 = ($maxPajak3 - 4500000000) * 0.35;
                }

                $pajakGajiSthn  = $taxVal1 + $taxVal2 + $taxVal3 + $taxVal4 + $taxVal5;
                // dd($tax);
                $pajakSebulan   = $pajakGajiSthn - $tax;
                $taxPinalty     = 0;

                // if ($npwp == '') {
                //     $taxPinalty = $pajakSebulan * 0.20;
                // }

                $taxThisMonth = floor($pajakSebulan + $taxPinalty);
                $taxBorneByEmployee = $taxThisMonth;


                // $pajak = $pajak * -1;
                // }

                // // $golongan  = '';
                // $tarif     = ''; 

                // $max_biaya_jabatan  = 500000*$count_bulan;

                // $tunjangan_jabatan  = $total_income_ytd_idr * 0.05;
                // if($tunjangan_jabatan > $max_biaya_jabatan){
                //     $tunjangan_jabatan  = $max_biaya_jabatan;
                // }

                // $total_deduction        = $tunjangan_jabatan + $emp_jp_ytd + $emp_jht_ytd;
                // $annualisation          = $total_income_ytd_idr - $total_deduction;

                // $taxable_income         = $annualisation - $ptkpTotal;

                // if($taxable_income <= 0) {
                //     $taxable_income         = 0;
                // }else{
                //     $tax_imposition_base_reg   = round($taxable_income / 1000 - 0.5, 0) * 1000;
                // }

                // $maxPajak1 = $tax_imposition_base_reg - 60000000;
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

                // $tax_due_income_per_annum  = $taxVal1 + $taxVal2 + $taxVal3 + $taxVal4 + $taxVal5;

                // $taxThisMonth = $tax_due_income_per_annum - $totalTer;

                // if (strlen($row['tax_no']) <= 19) {
                //     $taxPinalty = $tax_due_income_per_annum * (20 / 100);
                //     $taxThisMonthEmployee = $taxThisMonth + $taxPinalty;
                // }
                // $taxBorneByEmployee = $taxThisMonthEmployee;

                $pajakTER   = 0;
            } else {
                $brutto                = $row['total_kotor'];
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
            $takeHomePay = $total_income_this_month - ($taxBorneByEmployee + $row['bpjs'] + $emp_bpjs + $row['jkkjkm'] + $emp_jp + $emp_jht) + $gajiSudahDiBayar;

            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . ($rowIdx), $no)
                ->setCellValue('B' . ($rowIdx), $cutPeriod)
                ->setCellValue('C' . ($rowIdx), $row['full_name'])
                ->setCellValue('D' . ($rowIdx), $row['biodata_id'])
                // ->setCellValue('E'.($rowIdx), $row['tax_no'])
                ->setCellValueExplicit('E' . $rowIdx, $row['id_card_no'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValue('F' . ($rowIdx), $row['marital_status'])
                ->setCellValue('G' . ($rowIdx), $start_bulan)
                ->setCellValue('H' . ($rowIdx), $end_bulan)
                ->setCellValue('I' . ($rowIdx), $nom)
                ->setCellValue('J' . ($rowIdx), $count_bulan)

                ->setCellValue('K' . ($rowIdx), $row['gajipokok'])
                ->setCellValue('L' . ($rowIdx), $row['basic_prorate'])
                ->setCellValue('M' . ($rowIdx), $row['total_overtime'])
                ->setCellValue('N' . ($rowIdx), $row['other_allowance'])
                ->setCellValue('O' . ($rowIdx), $row['thr'])
                ->setCellValue('P' . ($rowIdx), $row['night_shift'])
                ->setCellValue('Q' . ($rowIdx), $row['day_shift'])
                ->setCellValue('R' . ($rowIdx), '')

                ->setCellValue('S' . ($rowIdx), $row['jkkjkm'])
                ->setCellValue('T' . ($rowIdx), $row['bpjs'])
                ->setCellValue('U' . ($rowIdx), $row['potongan_absensi'])
                ->setCellValue('V' . ($rowIdx), $total_reguler_income)

                ->setCellValue('W' . ($rowIdx), '')
                ->setCellValue('X' . ($rowIdx), '')
                ->setCellValue('Y' . ($rowIdx), $total_nonreguler_income)

                ->setCellValue('Z' . ($rowIdx), $total_income_this_month)
                ->setCellValue('AA' . ($rowIdx), $gross_income_previously)
                ->setCellValue('AB' . ($rowIdx), $total_income_ytd_idr)
                ->setCellValue('AC' . ($rowIdx), $tunjangan_jabatan)
                ->setCellValue('AD' . ($rowIdx), $tunjangan_jabatan)
                ->setCellValue('AE' . ($rowIdx), $emp_jp)
                ->setCellValue('AF' . ($rowIdx), $emp_jp_prev)
                ->setCellValue('AG' . ($rowIdx), $emp_jp_ytd)
                ->setCellValue('AH' . ($rowIdx), $emp_jht)
                ->setCellValue('AI' . ($rowIdx), $emp_jht_prev)
                ->setCellValue('AJ' . ($rowIdx), $emp_jht_ytd)
                ->setCellValue('AK' . ($rowIdx), $total_deduction)
                ->setCellValue('AL' . ($rowIdx), $annualisation)
                ->setCellValue('AM' . ($rowIdx), $ptkpTotal)
                ->setCellValue('AN' . ($rowIdx), $taxable_income)
                ->setCellValue('AO' . ($rowIdx), $tax_imposition_base_reg)
                ->setCellValue('AP' . ($rowIdx), $tax_due_income_per_annum)
                ->setCellValue('AQ' . ($rowIdx), $golongan)
                ->setCellValue('AR' . ($rowIdx), $tarif . ' %')
                // ->setCellValueExplicit('AQ' . $rowIdx, $tarif.' ', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                ->setCellValue('AS' . ($rowIdx), $pajakTER)
                ->setCellValue('AT' . ($rowIdx), $taxAlreadyPaid)
                ->setCellValue('AU' . ($rowIdx), $taxDuePaidYTD)
                ->setCellValue('AV' . ($rowIdx), $taxThisMonth)
                ->setCellValue('AW' . ($rowIdx), $taxPinalty)
                ->setCellValue('AX' . ($rowIdx), $taxBorneByEmployee)

                ->setCellValue('AY' . ($rowIdx), $row['jkkjkm'])
                ->setCellValue('AZ' . ($rowIdx), $emp_bpjs)
                ->setCellValue('BA' . ($rowIdx), $emp_jht)
                ->setCellValue('BB' . ($rowIdx), $row['bpjs'])
                ->setCellValue('BC' . ($rowIdx), $emp_jp)
                ->setCellValue('BD' . ($rowIdx), $gajiSudahDiBayar)
                ->setCellValue('BE' . ($rowIdx), $takeHomePay)
            ;

            /* SET ROW COLOR */
            if ($rowIdx % 2 == 1) {
                $spreadsheet->getActiveSheet()->getStyle('A' . $rowIdx . ':BE' . $rowIdx)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('EAEBAF');
            }

            if ($row['month_period'] == 11 or $row['check_final'] == 'END') {
                $spreadsheet->getActiveSheet()->getStyle('A' . $rowIdx . ':BE' . $rowIdx)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('B1A0C7');
            }

            $gross_income_previously    = $total_income_this_month;
            $emp_jp_prev                = $emp_jp + $emp_jp_prev;
            $emp_jht_prev               = $emp_jht + $emp_jht_prev;

            // $biodataId_new      = $row['biodata_id'];

        }
        $rowIdxEnd     = $rowIdx;
        $rowIdx = $rowIdx + 2;

        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . ($rowIdx), 'TOTAL')
            ->setCellValue('Z' . ($rowIdx), '=SUM(Z' . $rowIdxStart . ':Z' . $rowIdxEnd . ')')
            ->setCellValue('AX' . ($rowIdx), '=SUM(AX' . $rowIdxStart . ':AX' . $rowIdxEnd . ')')
            ->setCellValue('AY' . ($rowIdx), '=SUM(AY' . $rowIdxStart . ':AY' . $rowIdxEnd . ')')
            ->setCellValue('AZ' . ($rowIdx), '=SUM(AZ' . $rowIdxStart . ':AZ' . $rowIdxEnd . ')')
            ->setCellValue('BA' . ($rowIdx), '=SUM(BA' . $rowIdxStart . ':BA' . $rowIdxEnd . ')')
            ->setCellValue('BB' . ($rowIdx), '=SUM(BB' . $rowIdxStart . ':BB' . $rowIdxEnd . ')')
            ->setCellValue('BC' . ($rowIdx), '=SUM(BC' . $rowIdxStart . ':BC' . $rowIdxEnd . ')')
            ->setCellValue('BD' . ($rowIdx), '=SUM(BD' . $rowIdxStart . ':BD' . $rowIdxEnd . ')')
            ->setCellValue('BE' . ($rowIdx), '=SUM(BE' . $rowIdxStart . ':BE' . $rowIdxEnd . ')')
        ;

        $spreadsheet->getActiveSheet()->getStyle('A' . $rowIdx . ':BE' . $rowIdx)
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('FABF8F');

        $spreadsheet->getActiveSheet()->getStyle("A7:BE" . $rowIdxEnd)->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A" . $rowIdx . ":BE" . $rowIdx)->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A" . $rowIdx . ":BE" . $rowIdx)->getFont()->setBold(true)->setSize(12);

        // exit();
        $spreadsheet->getActiveSheet()->setCellValue('A' . ($rowIdx + 2), date('Y-m-d H:i:s'));

        // $spreadsheet->getActiveSheet()->getStyle("A6:AJ".($rowIdx))->applyFromArray($allBorderStyle);
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Report Excel ' . date('d-m-Y H'));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        $str = 'Tax Calculation ' . $yearPeriod;
        $fileName = preg_replace('/\s+/', '', $str);
        // $str = 'PTLAgrInvoice';
        // $fileName = 'Summary Payroll PT.'.$ptName.'';
        // test($fileName,1);
        // Redirect output to a client�s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment;filename="Summary Payroll PT.'.$ptName.'.Xlsx"');
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

    public function exportTaxCalculationPromincon($yearPeriod, $clientName)
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '-1');
        $clientInput = $clientName;
        $pt             = $clientName;

        $year           = $yearPeriod;
        $nameGroup      = 'All';

        // === INIT EXCEL ===
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator('Maurice - Web - Android')
            ->setLastModifiedBy('Maurice - Web - Android')
            ->setTitle('Tax Calculation')
            ->setSubject('Tax Calculation')
            ->setDescription('Generated using PhpSpreadsheet')
            ->setKeywords('tax xlsx php')
            ->setCategory('Tax File');

        // === COMMON STYLE ===
        $boldFont = ['font' => ['bold' => true]];
        $allBorderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => '00000000'],
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

        $center = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ];

        // AUTO SIZE COLUMNS
        foreach (range('B', 'Q') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // LOAD CLIENT DISPLAY NAME
        $client = Client::clientDisplay($pt);
        $clientDisplay = $client->clientDisplay ?? '';

        // === HEADER TITLE ===
        $sheet->setCellValue('A1', 'TAX CALCULATION PT. SANGATI SOERYA SEJAHTERA');
        $sheet->setCellValue('A2', "Employee Income Tax Calculation $clientDisplay");
        $sheet->setCellValue('A3', "For the month of ($nameGroup)");

        $sheet->mergeCells("A1:F1");
        $sheet->mergeCells("A2:F2");

        $sheet->getStyle("A1")->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle("A2")->getFont()->setBold(true)->setSize(13);

        // === HEADER COLOR + STYLE===
        $sheet->getStyle('A6:BN7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F2BE6B');
        $sheet->getStyle("A6:BN7")->applyFromArray($allBorderStyle);
        $sheet->getStyle("A6:BN7")->applyFromArray($center);

        $sheet->getStyle("B6:BN7")->getAlignment()->setWrapText(true);
        $sheet->getStyle("A6:BN7")->getFont()->setBold(true)->setSize(12);

        // === MERGE HEADER ===
        $mergeCols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'Y', 'Z', 'AA', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN'];

        foreach ($mergeCols as $col) {
            $sheet->mergeCells("$col" . "6:$col" . "7");
        }

        // GROUP HEADER
        $sheet->mergeCells("N6:X6");
        $sheet->mergeCells("AB6:AF6");

        // === SET HEADER LABEL ===
        $sheet->setCellValue('A6', 'NO')
            ->setCellValue('B6', 'PERIODE')
            ->setCellValue('C6', 'NAME')
            ->setCellValue('D6', 'BADGE ID')
            ->setCellValue('E6', 'NPWP')
            ->setCellValue('F6', 'Status BPJS Kesehatan')
            ->setCellValue('G6', 'Status Marital')
            ->setCellValue('H6', 'Anak')
            ->setCellValue('I6', 'First Month')
            ->setCellValue('J6', 'Last Month')
            ->setCellValue('K6', 'Number Of Month')
            ->setCellValue('L6', 'Bulan Ke')
            ->setCellValue('M6', 'Basic Salary IDR');

        // SUB HEADER
        $sheet->setCellValue('N7', 'Salary This Month')
            ->setCellValue('N6', 'Reguler Income')
            ->setCellValue('O7', 'Adjustment Salary')
            ->setCellValue('P7', 'Absensi')
            ->setCellValue('Q7', 'Overtime')
            ->setCellValue('R7', 'Tunjangan Kehadiran')
            ->setCellValue('S7', 'Tunjangan Shift Malam')
            ->setCellValue('T7', 'Tunjangan Transport')
            ->setCellValue('U7', 'Other Allowance')
            ->setCellValue('V7', 'Tunjangan')
            ->setCellValue('W7', 'Adjust In')
            ->setCellValue('X7', 'Adjust Out')

            ->setCellValue('Y6', 'BPJS JKK & JKM 2,04%')
            ->setCellValue('Z6', 'BPJS Kesehatan 4%')
            ->setCellValue('AA6', 'Total Regular Income')
            ->setCellValue('AB6', 'Non-Reguler Income')
            ->setCellValue('AB7', 'Other')
            ->setCellValue('AC7', 'Other(Lain2)')
            ->setCellValue('AD7', 'Tax Allow')
            ->setCellValue('AE7', 'PKWT')
            ->setCellValue('AF7', 'THR')
            ->setCellValue('AG6', 'Total Non-Reguler Income')
            ->setCellValue('AH6', 'Total Income This Month IDR')
            ->setCellValue('AI6', 'Gross Income Previously IDR')
            ->setCellValue('AJ6', 'Total Income YTD IDR')
            ->setCellValue('AK6', 'Functional Cost')
            ->setCellValue('AL6', 'Functional Cost Deduction')
            ->setCellValue('AM6', 'BPJS PENSIUN 1%')
            ->setCellValue('AN6', 'BPJS Pensiun Previously')
            ->setCellValue('AO6', 'BPJS Pensiun YTD')
            ->setCellValue('AP6', 'BPJS JHT 2%')
            ->setCellValue('AQ6', 'BPJS JHT Previously')
            ->setCellValue('AR6', 'BPJS JHT YTD')
            ->setCellValue('AS6', 'Total Deduction')
            ->setCellValue('AT6', 'Annualisation')
            ->setCellValue('AU6', 'PTKP Deduction')
            ->setCellValue('AV6', 'Taxable Income')
            // ->setCellValue('AW6', 'Tax Imposition Base On Reg Income (Rounding)')
            ->setCellValue('AW6', 'Tax Due Income Per Annum')
            ->setCellValue('AX6', 'Kelompok TER')
            ->setCellValue('AY6', '% TER')
            ->setCellValue('AZ6', 'Total TER Tax Due this Month')
            ->setCellValue('BA6', 'Tax Already Paid')
            ->setCellValue('BB6', 'Tax Due Paid YTD')
            ->setCellValue('BC6', 'Tax This Month IDR')
            ->setCellValue('BD6', 'Tax Penalty')
            ->setCellValue('BE6', 'Tax Borne by Employee')


            // ->setCellValue('BF6', 'JKK & JKM')
            // ->setCellValue('BG6', 'BPJS - Kes 1%')
            // ->setCellValue('BH6', 'JHT 2%')
            // ->setCellValue('BI6', 'BPJS - Kes. 4%')
            // ->setCellValue('BJ6', 'JP 1%')

            ->setCellValue('BF6', 'JKK & JKM')
            ->setCellValue('BG6', 'EMP JHT')
            ->setCellValue('BH6', 'HEALTH BPJS')
            ->setCellValue('BI6', 'EMP HEALTH BPJS')
            ->setCellValue('BJ6', 'EMP JP')


            ->setCellValue('BK6', 'Gaji / Bonus yang sudah Dibayarkan')
            ->setCellValue('BL6', 'Payment')
            ->setCellValue('BM6', 'Rounded')
            ->setCellValue('BN6', 'Take Home Pay');

        $mSalarySlip = new SalarySlip();
        $mTax = new Tax();
        $mAllowance = new MtAllowance();
        $queryBio = $mSalarySlip->getListForTaxCalculation($yearPeriod, $clientName);
        // dd($queryBio);

        $rowNo = null;
        $loopID = null;

        $rowIdx             = 8;
        $startIdx           = $rowIdx;
        $rowNo              = 1;
        $no                 = 0;
        $bulanke            = 0;
        $loopID             = 0;
        $tmbhth             = 0;
        $taxAlreadyPaid     = 0;
        $taxDuePaidYTD      = 0;
        $rdData = '';
        $gol = '';
        $name = '';

        foreach ($queryBio as $rowBio) {
            $bioId = $rowBio->biodata_id;
            $clientName = $rowBio->client_name;

            $query = $mSalarySlip->getListBySlipTaxCalcPeriod($yearPeriod,  $bioId);
            foreach ($query as $row) {


                if ($row->month_period == 1 || $row->month_period == 01) {
                    $tahunDB = $row->year_period - 1;
                    $no                 = $rowNo++;
                } else {
                    $no                 = "";
                    $tahunDB = $row->year_period;
                }
                $tmbhth     = $row->year_period;
                $name     = $row->full_name;
                $cutPeriod  = 0;
                $tanggal    = $row->month_period;
                $clientName = $row->client_name;
                $checkFinal = $row->check_final;
                $namabulan  = $this->namabulan($tanggal);
                $bulan      = $this->balikbulan($tanggal);
                $tanggal1   = $row->month_period - 1;
                $namabulan1 = $this->namabulan($tanggal1);
                $status     = $row->marital_status;
                $biodataId  = $row->biodata_id;
                $dateToTest = '' . $row->year_period . '-' . $row->month_period . ' ';
                $lastday    = date('t', strtotime($dateToTest));
                $overtime = $row->ot_1 + $row->ot_2 + $row->ot_3 + $row->ot_4;
                $isEmpty = $row->is_empty ?? false;
                $bsProrate = $row->bs_prorate;


                if ($biodataId != $loopID) {
                    $taxAlreadyPaid     = 0;
                    $taxDuePaidYTD      = 0;
                    $cekNumEnd          = 0;
                    // $numOfMonth         = $bulan;
                    $rdData             = '';
                    $cutPeriod = '16 ' . $namabulan1 . ' ' . $tahunDB . ' - 15 ' . $namabulan . ' ' . $tmbhth . ' ';
                    if ($isEmpty) {
                        $bulanke = 0;
                    }
                } else {
                    // $numOfMonth         = $bulan;
                    $bulanke            = $bulanke + 1;
                    $cutPeriod = '16 ' . $namabulan1 . ' ' . $tahunDB . ' - 15 ' . $namabulan . ' ' . $tmbhth . ' ';
                    // Hitung Number Of Month Jika Sudah pernah END
                    if ($rdData == 'END') {
                        // $numOfMonth         = $bulan;
                        $rdData             = '';
                    }
                }

                $statstr        = substr($status, 1, 1);
                $statstr2       = substr($status, 0, 2);
                $anak           = 0;
                if ($statstr2 == 'TK') {
                    $anak = substr($status, 2, 1);
                } else {
                    $anak = $statstr;
                }

                /* Start Kerja by month Period */
                $pengurang   = 13;
                $monthPeriod = $row->month_period;
                // Hitung Number Of Month Jika Sudah pernah END
                $endWork = $mSalarySlip->getEndWork($biodataId, $yearPeriod, $monthPeriod) ?? 12;
                $start_kerja = $mSalarySlip->getStartWork($biodataId, $yearPeriod, $monthPeriod);

                $start_kerja = (int) $start_kerja;

                $numOfMonth  = $endWork - $start_kerja + 1;

                /* Start Cek Roster STR & END */
                // $queryRoster = $this->mSalarySlip->getRosterCodeEnd($clientName, $biodataId, $yearPeriod, $monthPeriod);
                $rdData = '';

                $is_end = $row->is_end;
                $check_final = $row->check_final;

                if ($bulanke == 12 || $is_end == 1 || $check_final == "END") {
                    // $rdData = 'END';
                    $rdData = null;
                } else {
                    $rdData = null;
                }

                /* End Start Kerja by month Period */

                $loopID = $row->biodata_id;
                if (isset($row->bpjs_no)) {
                    $nobpjskes = $row->bpjs_no;
                } else {
                    $nobpjskes = 0;
                }

                $rowIdx++;

                $isEnd = $mSalarySlip->getCheckFinalEnd($clientName, $biodataId, $yearPeriod, $monthPeriod);
                $taxData = $mTax->getTaxDataForTaxCalculation($clientName, $biodataId, $yearPeriod, $monthPeriod);
                $taxPinalty = $taxData->tax_pinalty ?? 0;
                $gol = $taxData->golongan ?? 0;

                $cekFinal = '';

                if ($isEnd == 1 || $checkFinal == 'END') {
                    $cekFinal = "END";
                }
                $rowCheckFinal = '';
                if ($isEnd == 1 || $gol == 'END') {
                    $rowCheckFinal = $cekFinal;
                    $rdData = $cekFinal;
                }

                //jika ada datanya
                $cekNumEnd = 0;
                if ($isEmpty) {
                    $firstMonth = '';
                    $lastMonth = '';
                }


                if ($start_kerja == $monthPeriod) {
                    $bulanke = 1;
                } else if ($rdData == 'END' || $rowCheckFinal == 'END' || $monthPeriod == 12) {
                    $numOfMonth     = $bulanke;
                } else if ($start_kerja > $monthPeriod) {
                    $bulanke = 0;
                    $numOfMonth = 0;
                    $terGolongan = '';
                    $terPersen = 0;
                }
                if (!$isEmpty) {
                    if ($bulanke != 0 || $numOfMonth != 0) {
                        $cekNumEnd = ($numOfMonth / $bulanke);
                    }
                }

                $lastMonth  = 0;
                $firstMonth = 0;
                $tmbhth     = substr($tmbhth, 2, 2);
                $lastMonth  = '' . $namabulan . ' ' . $tmbhth . '';
                $lastMonthDes = $namabulan;
                $firstMonth = '' . $this->namabulan($start_kerja) . ' ' . $tmbhth . '';

                $maritalStatus = preg_replace("/[^A-Z]+/", "", $row->marital_status);
                $anakBrp = preg_replace("/[^0-9]/", '', $row->marital_status);

                $pajakTER = 0;
                $terPersen = 0;
                $taxAlreadyPaid = $taxDuePaidYTD;
                $taxDuePaidYTD  = $pajakTER + $taxAlreadyPaid;
                $terGolongan = $taxData->golongan ?? 0;
                $terPersen = $taxData->tarif ?? 0;

                // if ($lastMonthDes == 'Des' || $cekFinal == 'END') {
                //     $terGolongan = '';
                //     $terPersen = 0;
                // }

                // BPJS jht
                $bpjsKes     = $row->bpjs;
                $bpjsKesEmp  = $row->emp_bpjs;
                $jp          = $row->emp_jp;
                $bpjsJht     = $row->emp_jht;

                if ($bpjsKes > 0) {
                    $nobpjskes = 'Ya';
                } else if ($isEmpty) {
                    $nobpjskes = '';
                    $anakBrp = '';
                    $firstMonth = '';
                    $lastMonth = '';
                    $numOfMonth = 0;
                    $bulanke = 0;
                } else {
                    $nobpjskes = 'Tidak';
                }


                $allowanceRaw = $mAllowance->selectAllowanceAll($biodataId, $clientName, $yearPeriod, $monthPeriod, '');
                $allowanceData = [];
                foreach ($allowanceRaw as $allowance) {
                    $allowanceData[$allowance['allowance_name']] = $allowance['allowance_amount'];
                }
                $thr = $allowanceData['thr'] ?? 0;
                $adjustIn = $allowanceData['adjustment_in'] ?? 0;
                $adjustOut = $allowanceData['adjustment_out'] ?? 0;
                $otherAllowance = $allowanceData['tunjangan'] ?? 0;
                $attendanceBonus = $allowanceData['attendance_bonus'] ?? 0;
                $transportBonus  = $allowanceData['transport_bonus'] ?? 0;
                $nightShiftBonus = $allowanceData['night_shift_bonus'] ?? 0;
                $otherAllowance = $allowanceData['tunjangan'] ?? 0;

                $workdayAdj = $allowanceData['workday_adjustment'] ?? 0;
                $debtBurden = $allowanceData['debt_burden'] ?? 0;
                $thrByUser = $allowanceData['thr_by_user'] ?? 0;
                $gajiYangSudahDibayarkan = $workdayAdj + $debtBurden + $thrByUser;

                $spreadsheet->getActiveSheet()
                    ->setCellValue('A' . $rowIdx,  $no)
                    ->setCellValue('B' . $rowIdx, $cutPeriod)
                    ->setCellValue('C' . $rowIdx, str_replace('\\', '', $name))
                    ->setCellValue('D' . $rowIdx, $row->biodata_id)
                    ->setCellValueExplicit('E' . $rowIdx, '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValue('F' . $rowIdx, $nobpjskes)
                    ->setCellValue('G' . $rowIdx, $maritalStatus)
                    ->setCellValue('H' . $rowIdx, $anakBrp)
                    ->setCellValue('I' . $rowIdx, $firstMonth)
                    ->setCellValue('J' . $rowIdx, $lastMonth)
                    // ->setCellValueExplicit('K' . $rowIdx, $numOfMonth, DataType::TYPE_NUMERIC)
                    // ->setCellValue('L' . $rowIdx, $bulanke)
                    ->setCellValue('K' . $rowIdx, 0)
                    ->setCellValue('L' . $rowIdx, 0)
                    ->setCellValue('M' . $rowIdx, $row->base_wage)
                    ->setCellValue('N' . $rowIdx, $bsProrate)
                    ->setCellValue('O' . $rowIdx, 0)
                    ->setCellValue('P' . $rowIdx, -$row->potongan_absensi)
                    ->setCellValue('Q' . $rowIdx,  $overtime)
                    ->setCellValue('R' . $rowIdx, $attendanceBonus)
                    ->setCellValue('S' . $rowIdx, $nightShiftBonus)
                    ->setCellValue('T' . $rowIdx, $transportBonus)
                    ->setCellValue('U' . $rowIdx, $otherAllowance)
                    ->setCellValue('V' . $rowIdx, '')
                    ->setCellValue('W' . $rowIdx, $adjustIn)
                    ->setCellValue('X' . $rowIdx, $adjustOut)
                    ->setCellValue('Y' . $rowIdx, $row->jkkjkm)
                    ->setCellValue('Z' . $rowIdx, $bpjsKes)
                    ->setCellValue('AA' . $rowIdx, '=SUM(N' . $rowIdx . ':Z' . $rowIdx . ')') //Total Reguler
                    ->setCellValue('AB' . $rowIdx, 0)
                    ->setCellValue('AC' . $rowIdx, 0)
                    ->setCellValue('AD' . $rowIdx, 0)
                    ->setCellValue('AE' . $rowIdx, 0)
                    ->setCellValue('AF' . $rowIdx, $thr)
                    ->setCellValue('AG' . $rowIdx, '=SUM(AB' . $rowIdx . ':AF' . $rowIdx . ')') //Total Non Reguler
                    ->setCellValue('AH' . $rowIdx, '=(AA' . $rowIdx . '+AG' . $rowIdx . ')'); //Brutto

                if ($start_kerja > $monthPeriod ||  $monthPeriod == '01') {
                    $bruttoPrevious = 0;
                } else if ($start_kerja < $monthPeriod && $isEmpty) {
                    $bruttoPrevious = 0;
                } else {
                    // $bruttoPrevious = '=AJ' . ($rowIdx - 1);
                    $bruttoPrevious = 0;
                }

                $spreadsheet->getActiveSheet()->setCellValue('AI' . $rowIdx, $bruttoPrevious) //Brutto Prev
                    ->setCellValue('AJ' . $rowIdx, '=SUM(AH' . $rowIdx . ':AI' . $rowIdx . ')') //Total Income
                    // ->setCellValue('AK' . $rowIdx, '=IF(K' . $rowIdx . '=L' . $rowIdx . ',AJ' . $rowIdx . '*5%,0)')
                    ->setCellValue('AK' . $rowIdx, 0)
                    //Functional Cost
                    // ->setCellValue('AL' . $rowIdx, '=IF(AK' . $rowIdx . '<500000*K' . $rowIdx . ',AK' . $rowIdx . ',500000*K' . $rowIdx . ')') //Functional Cost on Bonus
                    ->setCellValue('AL' . $rowIdx, 0) //Functional Cost on Bonus
                    ->setCellValue('AM' . $rowIdx, $row->emp_jp); //BPJS Pensiun
                if ($start_kerja > $monthPeriod ||  $monthPeriod == '01') {
                    $jpPrevious = 0;
                } else if ($start_kerja < $monthPeriod && $isEmpty) {
                    $jpPrevious = 0;
                } else {
                    // $jpPrevious = '=AO' . ($rowIdx - 1);
                    0;
                }

                $spreadsheet->getActiveSheet()->setCellValue('AN' . $rowIdx, $jpPrevious) //BPJS Pensiun Prev
                    ->setCellValue('AO' . $rowIdx, '=AM' . $rowIdx . '+AN' . $rowIdx . '') //BPJS Pensiun YTD
                    ->setCellValue('AP' . $rowIdx, $row->emp_jht); //BPJS JHT 2%

                if ($start_kerja > $monthPeriod ||  $monthPeriod == '01') {
                    $bpjsJhtPrevious = 0;
                } else if ($start_kerja < $monthPeriod && $isEmpty) {
                    $bpjsJhtPrevious = 0;
                } else {
                    $bpjsJhtPrevious = '=AR' . ($rowIdx - 1);
                }

                $spreadsheet->getActiveSheet()->setCellValue('AQ' . $rowIdx, $bpjsJhtPrevious) //BPJS JHT Prev
                    ->setCellValue('AR' . $rowIdx, '=AP' . $rowIdx . '+AQ' . $rowIdx . '') //BPJS JHT YTD
                    // ->setCellValue('AS' . $rowIdx, '=IF(K' . $rowIdx . '=L' . $rowIdx . ',AL' . $rowIdx . '+AO' . $rowIdx . '+AR' . $rowIdx . ',0)') //Total Deduction
                    ->setCellValue('AS' . $rowIdx, 0) //Total Deduction
                    // ->setCellValue('AT' . $rowIdx, '=IF(K' . $rowIdx . '=L' . $rowIdx . ',AJ' . $rowIdx . '-AS' . $rowIdx . ',0)') //Annualisation
                    ->setCellValue('AT' . $rowIdx, 0) //Annualisation
                    // ->setCellValue('AU' . $rowIdx, $row->ptkp_total)
                    ->setCellValue('AU' . $rowIdx, 0)
                    // ->setCellValue('AV' . $rowIdx, '=IF(AT' . $rowIdx . '<AU' . $rowIdx . ',0,AT' . $rowIdx . '-AU' . $rowIdx . ')') //Taxable Income
                    ->setCellValue('AV' . $rowIdx, 0) //Taxable Income
                    // ->setCellValue('AW' . $rowIdx, '=IF((AV' . $rowIdx . ')>5000000000,(0.35*(AV' . $rowIdx . '))-306000000,IF((AV' . $rowIdx . ')>500000000,(0.3*(AV' . $rowIdx . '))-56000000,IF((AV' . $rowIdx . ')>250000000,(0.25*(AV' . $rowIdx . '))-31000000,IF((AV' . $rowIdx . ')>60000000,(0.15*(AV' . $rowIdx . '))-6000000,AV' . $rowIdx . '*0.05))))')
                    ->setCellValue('AW' . $rowIdx, 0)
                    ->setCellValue('AX' . $rowIdx, $terGolongan)
                    ->setCellValue('AY' . ($rowIdx), ($terPersen / 100))
                    ->setCellValue('AZ' . $rowIdx, '=ROUNDDOWN(-(AY' . $rowIdx . '*AH' . $rowIdx . '),0)');
                // ->setCellValue('AZ' . $rowIdx,  0);

                $taxPrevious = 0;
                if ($start_kerja > $monthPeriod ||  $monthPeriod == '01') {
                    $taxPrevious = 0;
                } else if ($start_kerja < $monthPeriod && $isEmpty) {
                    $taxPrevious = 0;
                } else {
                    $taxPrevious = '=BB' . ($rowIdx - 1);
                }

                $spreadsheet->getActiveSheet()->setCellValue('BA' . $rowIdx, 0)
                    ->setCellValue('BB' . $rowIdx, 0)
                    ->setCellValue('BC' . $rowIdx, '=ROUNDDOWN(-(AY' . $rowIdx . '*AH' . $rowIdx . '),0)')
                    ->setCellValue('BD' . $rowIdx, 0)
                    ->setCellValue('BE' . $rowIdx, '=ROUNDDOWN(-(AY' . $rowIdx . '*AH' . $rowIdx . '),0)')
                    ->setCellValue('BF' . $rowIdx, -$row->jkkjkm)
                    ->setCellValue('BG' . $rowIdx, -$row->empJht)
                    ->setCellValue('BH' . $rowIdx, -$row->bpjs)
                    ->setCellValue('BI' . $rowIdx, -$row->emp_bpjs)
                    ->setCellValue('BJ' . $rowIdx, -$row->empJp)


                    ->setCellValue('BK' . $rowIdx, $gajiYangSudahDibayarkan)
                    ->setCellValue('BL' . $rowIdx, '=ROUND(AH' . $rowIdx . '+SUM(BE' . $rowIdx . ':BK' . $rowIdx . '),0)');
                $beforeRounded = $spreadsheet->getActiveSheet()->getCell('BL' . $rowIdx)->getCalculatedValue();
                // $roundedValue = round($beforeRounded);
                $rounded =  ConfigurationHelper::pembulatanTotal($beforeRounded);
                $spreadsheet->getActiveSheet()
                    ->setCellValue('BM' . $rowIdx, $rounded)
                    ->setCellValue('BN' . $rowIdx, '=SUM(BL' . $rowIdx . ':BM' . $rowIdx . ')');

                if ($rowIdx % 2 == 1) {
                    $spreadsheet->getActiveSheet()->getStyle('A' . $rowIdx . ':BN' . $rowIdx)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('EAEBAF');
                }

                if ($clientInput != $clientName && $clientName != '') {
                    $spreadsheet->getActiveSheet()->getStyle('A' . $rowIdx . ':BN' . $rowIdx)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('FFFF00');
                }

                $bulanSama = 0;
                if ($numOfMonth == $bulanke && $numOfMonth != 0) {
                    $bulanSama = 1;
                }

                /** START WARNA FINALISASI */
                // if ($rdData == 'END' ||  $cekNumEnd == 1 || $bulanSama == 1) {
                //     $spreadsheet->getActiveSheet()->getStyle('A' . $rowIdx . ':BN' . $rowIdx)
                //         ->getFill()
                //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                //         ->getStartColor()
                //         ->setRGB('B1A0C7');
                // }
            }
        }

        $spreadsheet->getActiveSheet()
            ->setCellValue('F' . ($rowIdx + 2), 'TOTAL')
            ->setCellValue('BN' . ($rowIdx + 2), '=SUM(BN' . $startIdx . ':BN' . $rowIdx . ')');

        $spreadsheet->getActiveSheet()->freezePane('D9');
        $spreadsheet->getActiveSheet()
            ->getStyle("A" . ($rowIdx + 2) . ":BN" . ($rowIdx + 2))
            ->getFont()
            ->setBold(true)
            ->setSize(12);

        $spreadsheet->getActiveSheet()
            ->getStyle("A" . ($rowIdx + 2) . ":BN" . ($rowIdx + 2))
            ->applyFromArray($outlineBorderStyle);

        $spreadsheet->getActiveSheet()
            ->getStyle('M8:BN' . ($rowIdx + 2))
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');

        $spreadsheet->getActiveSheet()
            ->getStyle('AY8:AY' . ($rowIdx + 2))
            ->getNumberFormat()
            ->setFormatCode('0.00%');

        $spreadsheet->getActiveSheet()
            ->getStyle("A" . ($rowIdx + 2) . ":BN" . ($rowIdx + 2))
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B');

        unset($allBorderStyle, $center, $right, $left);

        $spreadsheet->setActiveSheetIndex(0);


        // ========================
        //  File name generator
        // ========================
        $str = 'TaxCalculcation_' . $pt . '-' . '-' . $yearPeriod;

        $fileName = preg_replace('/\s+/', '', $str) . '.xlsx';


        // ========================
        //  Path for file storage
        // ========================
        $uploadDir = WRITEPATH . 'uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $tempFilePath = $uploadDir . $fileName;


        // ========================
        //  Save the Excel file
        // ========================
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempFilePath);


        // ========================
        //  Return file as download
        // ========================
        return $this->response->download($tempFilePath, null)->setFileName($fileName);
    }

    public function namabulan($tanggal)
    {
        switch ($tanggal) {
            case '1':
                return "Jan";
                break;
            case '2':
                return "Feb";
                break;
            case '3':
                return "Mar";
                break;
            case '4':
                return "Apr";
                break;
            case '5':
                return "Mei";
                break;
            case '6':
                return "Jun";
                break;
            case '7':
                return "Jul";
                break;
            case '8':
                return "Agu";
                break;
            case '9':
                return "Sep";
                break;
            case '10':
                return "Okt";
                break;
            case '11':
                return "Nov";
                break;
            case '12':
                return "Des";
                break;
            case '0':
                return "Des";
                break;
        };
    }

    public function balikbulan($bulan)
    {
        switch ($bulan) {
            case '1':
                return "12";
                break;
            case '2':
                return "11";
                break;
            case '3':
                return "10";
                break;
            case '4':
                return "9";
                break;
            case '5':
                return "8";
                break;
            case '6':
                return "7";
                break;
            case '7':
                return "6";
                break;
            case '8':
                return "5";
                break;
            case '9':
                return "4";
                break;
            case '10':
                return "3";
                break;
            case '11':
                return "2";
                break;
            case '12':
                return "1";
                break;
        };
    }
}
