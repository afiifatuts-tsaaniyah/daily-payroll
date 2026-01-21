<?php

namespace App\Controllers\Transaction;

use CodeIgniter\I18n\Time;
use App\Controllers\BaseController;
use App\Helpers\ConfigurationHelper;
use App\Helpers\ResponseFormatter;
use App\Models\Master\Client;
use App\Models\Master\Config;
use App\Models\Master\ConfigAllowance;
use App\Models\Master\M_mt_salary;
use App\Models\Master\MtBiodata01;
use App\Models\Master\ProcessClossing;
use App\Models\Master\MtAllowance;
use App\Models\Master\RosterHist;
use App\Models\Master\TaxGolongan;
use App\Models\Transaction\M_tr_overtime;
use App\Models\Transaction\M_tr_timesheet;
use App\Models\Transaction\SalarySlip;
use App\Models\Transaction\Tax;

class Payroll_controller extends BaseController
{
    protected $trSalarySlip;
    protected $trOvertime;
    protected $mSalary;
    protected $mtProcessClossing;
    protected $mTimesheet;
    protected $mPayrollConfig;
    protected $govController;
    protected $mAllowanceConfig;
    protected $mAllowance;
    protected $mSalarySlip;
    protected $mRosterHist;
    protected $mTax;
    protected $cTaxProcess;

    public function __construct()
    {
        $this->mPayrollConfig = new Config();
        $this->trSalarySlip = new SalarySlip();
        $this->mSalary = new M_mt_salary();
        $this->trOvertime = new M_tr_overtime();
        $this->mtProcessClossing = new ProcessClossing();
        $this->mTimesheet = new M_tr_timesheet();
        $this->govController = new Gov_regulation();
        $this->mAllowanceConfig = new ConfigAllowance();
        $this->mAllowance = new MtAllowance();
        $this->mSalarySlip = new SalarySlip();
        $this->mTax = new Tax();
        $this->mRosterHist = new RosterHist();
        $this->cTaxProcess = new Timesheet_process();
    }

    public function excelDept($ptName, $group, $monthPeriod, $yearPeriod, $isEnd, $isHealthBPJS, $isJHT, $isJP, $isJKKM)
    {
        helper('filesystem');
        // Batas eksekusi
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300);

        $calendarStart = 16;

        $mSalarySlip = new SalarySlip();
        $mRoster     = new M_tr_timesheet();

        $results = $mSalarySlip->getSalaryDetails($ptName, $monthPeriod, $yearPeriod, $group);

        if (empty($results)) {
            return $this->response->setJSON([
                'message' => 'Data tidak ditemukan'
            ])->setStatusCode(404);
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // hapus sheet default

        $batchSize = 50;
        $totalResults = count($results);
        $noNewSheet = 0;

        $spreadsheet->createSheet();

        for ($i = 0; $i < $totalResults; $i++) {
            $row = $results[$i];

            $slipId     = $row->slip_id;
            $clientName = $row->client_name;
            $biodataId  = $row->biodata_id;
            $isEnd      = $row->is_end;
            $name       = $row->full_name;

            // Cek NIK/NIE
            $cekNie = $mRoster->checkNie($biodataId, $clientName, $monthPeriod, $yearPeriod);

            $spreadsheet->setActiveSheetIndex($noNewSheet);

            if (!empty($cekNie)) {
                $this->printPayroll($slipId, $clientName, $calendarStart, $spreadsheet, $noNewSheet, $isEnd, $isHealthBPJS, $isJHT, $isJP, $isJKKM);
            } else {
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'SLIP TIDAK DAPAT DICETAK KARENA (BADGE NO) TIDAK SESUAI');
                $sheet->getTabColor()->setRGB('FF0000');
            }

            $sheetTitle = substr($name, 0, 30);
            $spreadsheet->getActiveSheet()->setTitle($sheetTitle);
            $noNewSheet++;

            // Delay tiap 50 sheet (biar server gak sesak)
            if ($noNewSheet % $batchSize === 0) {
                usleep(500000);
            }
        }

        $fileName = "PAYROLL_{$ptName}_{$group}_({$noNewSheet})_{$monthPeriod}_{$yearPeriod}.xlsx";
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Simpan sementara ke file lokal
        $tempPath = WRITEPATH . 'exports/' . $fileName;
        if (!is_dir(WRITEPATH . 'exports')) {
            mkdir(WRITEPATH . 'exports', 0777, true);
        }
        $writer->save($tempPath);

        // Download response CI4
        return $this->response->download($tempPath, null)->setFileName($fileName);
    }

    public function toexcel($slipId, $clientName, $calendarStart, $isEnd, $isHealthBPJS, $isJHT, $isJP, $isJKKM)
    {
        // Set konfigurasi eksekusi
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300);

        $calendarStart = 16;
        // Inisialisasi Spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Jalankan fungsi print sesuai client
        $fileName = $this->printPayroll(
            $slipId,
            $clientName,
            $calendarStart,
            $spreadsheet,
            0,
            $isEnd,
            $isHealthBPJS,
            $isJHT,
            $isJP,
            $isJKKM
        );

        // Hapus sheet default jika ada
        $defaultSheet = $spreadsheet->getSheetByName('Worksheet');
        if ($defaultSheet) {
            $sheetIndex = $spreadsheet->getIndex($defaultSheet);
            $spreadsheet->removeSheetByIndex($sheetIndex);
        }

        // Simpan file sementara
        $exportPath = WRITEPATH . 'exports/';
        if (!is_dir($exportPath)) {
            mkdir($exportPath, 0777, true);
        }

        $tempPath = $exportPath . $fileName . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempPath);

        // Auto delete setelah download
        register_shutdown_function(static function () use ($tempPath) {
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
        });

        // Kirim response untuk diunduh
        return $this->response
            ->download($tempPath, null)
            ->setFileName($fileName . '.xlsx');
    }

    public function printPayslipBySelectedId($salarySlipIds)
    {
        helper('filesystem'); // optional

        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300);

        $salarySlipIdsArray = explode(',', $salarySlipIds);

        $calendarStart = 11;
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->createSheet();

        $noNewSheet   = 1;
        $clientName   = '';
        $dept         = '';
        $monthPeriod  = '';
        $yearPeriod   = '';

        // Load model
        $salarySlipModel = new SalarySlip();

        foreach ($salarySlipIdsArray as $slipId) {

            $salarySlip = $salarySlipModel->find($slipId);

            if (!$salarySlip) {
                continue;
            }

            $clientName  = $salarySlip['client_name'];
            $monthPeriod = $salarySlip['month_period'];
            $yearPeriod  = $salarySlip['year_period'];
            $dept        = $salarySlip['dept'];

            if ($clientName == "BSI_Banyuwangi") {
                // $this->printPayroll($slipId, $clientName, $calendarStart, $spreadsheet, $noNewSheet, 0, 1, 1, 1, 1);
            } elseif ($clientName == "Mintex_Kalsel") {
                // $this->printPayrollMintex($slipId, $clientName, $spreadsheet, $noNewSheet, 0, 1, 1, 1, 1);
            } else {
                $this->printPayroll($slipId, $clientName, $calendarStart, $spreadsheet, $noNewSheet, 0, 1, 1, 1, 1);
            }

            $noNewSheet++;
        }

        // Remove default sheets created by Spreadsheet
        $defaultSheet = $spreadsheet->getSheetByName('Worksheet');
        if ($defaultSheet) {
            $spreadsheet->removeSheetByIndex($spreadsheet->getIndex($defaultSheet));
        }

        $defaultSheet2 = $spreadsheet->getSheetByName('Worksheet 1');
        if ($defaultSheet2) {
            $spreadsheet->removeSheetByIndex($spreadsheet->getIndex($defaultSheet2));
        }

        // File name
        $fileName = "PAYROLL_{$clientName}_{$dept}_(" . ($noNewSheet - 1) . ")_{$monthPeriod}_{$yearPeriod}.xlsx";

        // Temp file path (WRITEPATH = writable/)
        $tempFile = WRITEPATH . "uploads/" . $fileName;

        // Ensure directory exists
        if (!is_dir(WRITEPATH . "uploads")) {
            mkdir(WRITEPATH . "uploads", 0777, true);
        }

        // Save file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempFile);

        // Delete after download
        register_shutdown_function(function () use ($tempFile) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        });

        // Download to browser
        return $this->response->download($tempFile, null)->setFileName($fileName);
    }


    public function printPayroll($slipId, $clientName, $calendarStart, $spreadsheet, $noNewSheet, $isEnd, $isHealthBPJS, $isJHT, $isJP, $isJKKM)
    {
        set_time_limit(0); // Hindari batas waktu eksekusi
        ini_set('memory_limit', '1024M'); // Tambahkan RAM jika perlu
        ini_set('max_execution_time', 300); // Maksimal 5 menit

        $spreadsheet->getProperties()->setCreator('Maurice - Web - Android')
            ->setLastModifiedBy('Maurice - Web - Android')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');
        Spreadsheet_template_controller::addLogo($spreadsheet, 'M1');
        $styles = Spreadsheet_template_controller::getStyles();
        $outlineBorderStyle = $styles['outlineBorderStyle'];
        $allBorderStyle = $styles['allBorderStyle'];
        $center = $styles['center'];
        $right = $styles['right'];
        $left = $styles['left'];
        $boldFont = $styles['boldFont'];
        $totalStyle = $styles['totalStyle'];
        $topBorderStyle = $styles['topBorderStyle'];
        $bottomBorderStyle = $styles['bottomBorderStyle'];
        $italic = $styles['italicFont'];
        /* CALENDAR SETUP */
        $dateCal = $calendarStart;
        /* START VALIDATE & UPDATE GOVERNMENT REGULATION */
        $payrollConfig = $this->mPayrollConfig->loadByClient($clientName);


        $salarySlip = $this->mSalarySlip->getObjectById($slipId);


        $basicSalary   = $salarySlip->base_wage;
        /* Closing Payroll */
        /** Row Check Bulan Dan Tahun */
        // $cekClient = $this->mSalarySlip->cekClient($slipId, $clientName);

        $rowData = $this->mSalarySlip->getSlipDataWithoutTax($clientName, $slipId);
        // $dataCountClosing = $this->mProcessClossing->getCountByClientPeriod($clientName, $yearPeriod, $monthPeriod);
        $monthPeriod = $rowData->month_period;
        $yearPeriod = $rowData->year_period;

        $healthBpjs = $rowData->bpjs;
        $jkkJkm = $rowData->jkkjkm;
        $jht = $rowData->jht;
        $empJht = $rowData->emp_jht;
        $jp = $rowData->jp;
        $empJp = $rowData->emp_jp;
        $empHealthBpjs = $rowData->emp_bpjs;

        $marital = $rowData->marital_status;

        $marital = $rowData->marital_status;
        $ptName         = $rowData->client_name;
        $bioRecId       = $rowData->biodata_id;

        $bioName        = $rowData->full_name;
        $position       = $rowData->position;
        $maritalStatus  = $rowData->marital_status;
        $monthPeriod    = $rowData->month_period;
        $yearPeriod     = $rowData->year_period;
        $biodataId      = $rowData->biodata_id;
        $nie            = $rowData->biodata_id;
        $dept           = $rowData->dept;
        $rosterFormat   = $rowData->roster_format;
        $rosterBase     = $rowData->roster_base;
        $npwpNo         = $rowData->biodata_id;
        $tMonth         = (int) $monthPeriod;
        $tYear          = (int) $yearPeriod;
        $inShift        = $rowData->day_shift_count;
        $inOff          = $rowData->in_ph_total;
        $inPh           = $rowData->in_ph_total;
        $inNightShift = $rowData->night_shift_count;
        $rosterFormat   = $rowData->roster_format;
        $basicSalary    = $rowData->salary_basic;
        // $bsProrate      = $rowData->bs_prorate;
        $bsProrate = $rowData->bs_prorate;


        //Overtime
        $otTotal1       = $rowData->ot_1;
        $otTotal2       = $rowData->ot_2;
        $otTotal3       = $rowData->ot_3;
        $otTotal4       = $rowData->ot_4;
        //Overtime Count
        $otCount1       = $rowData->ot_count1;
        $otCount2       = $rowData->ot_count2;
        $otCount3       = $rowData->ot_count3;
        $otCount4       = $rowData->ot_count4;
        $tUnpaidCount = (int) $rowData->unpaid;
        $unpaidTotal   = $rowData->potongan_absensi;
        $tNightShiftCount = strval($rowData->night_shift_count);
        $isEnd   = $rowData->is_end;

        $this->updatePicPrint($slipId, $clientName, $biodataId);
        $bonusTotal = 0;

        $allowanceData = $this->getAllowanceData($bioRecId, $clientName, $yearPeriod, $monthPeriod);
        /**  START TUNJANGAN NON REGULAR  */
        $thr                = $allowanceData['thr'];
        $tunjangan    = $allowanceData['tunjangan'];
        $nightShiftBonus    = $allowanceData['nightShiftBonus'];
        $transportBonus    = $allowanceData['transportBonus'];
        $attendanceBonus    = $allowanceData['attendanceBonus'];
        $adjustmentIn    = $allowanceData['adjustmentIn'];
        $adjustmentOut    = $allowanceData['adjustmentOut'];
        $workDayAdjustment    = $allowanceData['workDayAdjustment'];
        $thrByUser    = $allowanceData['thrByUser'];
        $debtBurden    = $allowanceData['debtBurden'];
        $amountAll    = $allowanceData['amountAll'];
        /**  END TUNJANGAN NON REGULAR  */

        /**  START TOTAL TUNJANGAN REGULAR + NON REG ( BONUS TOTAL )  */
        $bonusTotal =   $thr + $attendanceBonus + $tunjangan + $nightShiftBonus + $transportBonus;

        //Total Overtime
        $allOt = $otTotal1 + $otTotal2 + $otTotal3 + $otTotal4;
        $wdAttendCount = 0;
        $wdAttendCount = $rowData->attend_total;
        $tBurden    = $allowanceData['debtBurden'];
        // $tBurden = $tBurden * (-1);

        $taxResults =  $this->mTax->getTaxDataByBiodata($clientName, $biodataId, $yearPeriod, $monthPeriod);
        $terGolongan = $taxResults->golongan ?? '';
        $terPersentase = $taxResults->tarif ?? 0;
        $taxFinal = $taxResults->tax_val ?? 0;
        $bruttoTotal = $taxResults->brutto ?? 0;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(6);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(9);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(7);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(8);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(3);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(8);

        $query_client = Client::clientDisplay($clientName);
        $client_display         = $query_client['clientDisplay'];

        //Display Period di Timesheet
        $tDate1 = $calendarStart . '-' . $monthPeriod . '-' . $yearPeriod;
        $tStr = $yearPeriod . '-' . $monthPeriod . '-' . ($calendarStart);
        $dStr1 = strtotime($tStr);
        $tDate3 = date("t-m-Y", strtotime($tDate1));
        $tDate3 = date("d-m-Y", strtotime("-1 month", $dStr1));
        $tDate4 = date("d-m-Y", strtotime("-1 day", $dStr1));
        $spreadsheet->getActiveSheet()->setCellValue('A5', 'Periode : ' . $tDate3 . ' to ' . $tDate4);


        $spreadsheet->getActiveSheet()
            ->setCellValue('A1', 'PT. SANGATI SOERYA SEJAHTERA')
            ->setCellValue('A2', 'Slip Gaji Karyawan')
            ->setCellValue('A4', 'PT : ' . $client_display)
            ->setCellValue('S6', 'Base   : ' . $rosterBase)
            ->setCellValue('S7', 'Format : ' . $rosterFormat);

        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true)->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle("A2:D2")->getFont()->setBold(true)->setSize(14)->setUnderline(true);
        $spreadsheet->getActiveSheet()->getStyle("A4:G4")->getFont()->setBold(true)->setSize(12);

        $spreadsheet->getActiveSheet()->mergeCells("A6:A7");
        $spreadsheet->getActiveSheet()->mergeCells("B6:B7");
        $spreadsheet->getActiveSheet()->mergeCells("C6:C7");
        $spreadsheet->getActiveSheet()->mergeCells("D6:D7");
        $spreadsheet->getActiveSheet()->mergeCells("E6:E7");
        $spreadsheet->getActiveSheet()->mergeCells("F6:I6");

        $spreadsheet->getActiveSheet()->getStyle("A6:E7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("F6:I7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A6:I7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A6:I7")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()
            ->setCellValue('A6', 'DATE')
            ->setCellValue('B6', 'ROSTER DAY')
            ->setCellValue('C6', 'SHIFT DAY')
            ->setCellValue('D6', 'HOURS TOTAL')
            ->setCellValue('E6', 'NT')
            ->setCellValue('F6', 'OVERTIME')
            ->setCellValue('F7', '1')
            ->setCellValue('G7', '2')
            ->setCellValue('H7', '3')
            ->setCellValue('I7', '4');

        $spreadsheet->getActiveSheet()->getStyle("A6:I7")->applyFromArray($boldFont);
        $ot01Count = 0;
        $ot02Count = 0;
        $ot03Count = 0;
        $ot04Count = 0;

        $normalTotal = 0;
        $allTimeTotal = 0;
        /* START SUMMARY ROSTER */
        $rowIdxStart = 8;
        $rowIdx = 8;
        /* START CALENDAR VALUE */

        /* Get Last Day Number */
        $strDate = $yearPeriod . '-' . $monthPeriod . '-01';
        $tData = RosterHist::getTData($biodataId, $strDate);
        $tLastDay = 0;
        if (isset($tData)) {
            $tLastDay = $tData->last_day;
        }
        $rosterIdx = $tLastDay;
        $shiftCount = 0;
        $timestamp = strtotime($strDate);
        $getNameofDayNumber = date('w', $timestamp);

        // penanda Roster mulai
        $rosterDay =   $getNameofDayNumber - 1;
        $attendStatus = 0;


        if ($rosterBase == '131') {
            $maxDay = 14;
        } else {
            $maxDay = 7;
        }

        //after
        // // tanggal awal: 16 bulan ini
        // $startDate = Time::createFromDate($tYear, $tMonth, 16);

        // // tanggal akhir: 15 bulan depan
        // $endDate = $startDate->addMonths(1)->setDay(15);

        // $interval = $startDate->difference($endDate);
        // $countDay = $interval->getDays() + 1; // +1 untuk hitung kedua tanggal
        // var_dump($countDay);
        // exit();

        // tMonth dan tYear adalah bulan sebelumnya
        $startBase = Time::createFromDate($tYear, $tMonth, 1)->subMonths(1);

        // tanggal awal: 16 bulan berikutnya
        $startDate = $startBase->setDay(16);

        // buat clone untuk tanggal akhir
        $endDate = clone $startDate;
        $endDate = $endDate->addMonths(1)->setDay(15);

        // hitung hari inklusif
        $interval = $startDate->difference($endDate);
        $countDay = $interval->getDays() + 1;

        //Kondisi jika dia ada roster formatnya
        $hasRosterFormat = false;

        if ($rosterBase == '131') {
            $hasRosterFormat = true;
        } else if ($rosterBase == '52') {
            if ($rosterFormat) {
                $hasRosterFormat = true;
            } else {
                $hasRosterFormat = false;
            }
        }
        $rdData = '';
        $dayCountInMonth = $countDay;
        $tmpRosterLength = strlen($rosterFormat);
        $strNumRoster   = '';
        $tmp            = '';
        $dayNo          = '';
        $groupCount     = 0;
        $strNum         = '';
        $dayTotal       = 0;


        /* START GET DAYS TOTAL BY ROSTER */
        for ($i = 0; $i < $tmpRosterLength; $i++) {
            $strNum = substr($rosterFormat, $i, 1);
            /* START MAKE SURE DATA IS NUMBER */
            if (is_numeric($strNum)) {
                $strNumRoster .= $strNum;
                $dayTotal += $strNum;
            }
            /* END MAKE SURE DATA IS NUMBER */
        }

        $numChar    = '';
        $rdData     = '';
        $dataIdx    = 8;
        $tIdx       = 0;
        /** Penambahan untuk roster format 31/07/23 */
        for ($m = 0; $m < strlen($strNumRoster); $m++) {
            $numChar = substr($strNumRoster, $m, 1);

            for ($k = 1; $k <= $numChar; $k++) {
                # code...
                $dataIdx++;

                /* START GET PUBLIC HOLIDAY BY ROSTER MASTER */
                $tIdx++;
                $tColIdx = '';
                if ($tIdx < 10) {
                    $tColIdx = 'd0' . $tIdx;
                } else {
                    $tColIdx = 'd' . $tIdx;
                }
                $tVal = substr($rowData->$tColIdx, 0, 2);

                if ($tVal == 'PH') {
                    $rdData = 'PH';
                } else {
                    $rdData = $k;
                }
                /* END GET PUBLIC HOLIDAY BY ROSTER MASTER */

                if ($m % 2 == 0) {
                    $tmp .= $k . "  ";
                    // $rdData = $k;
                } else {
                    $tmp .= "RO";
                    $rdData = "RO";
                }
                /* START ROSTER DAY TITLE */
                // $dayCountInMonth = cal_days_in_month(CAL_GREGORIAN, $monthPeriod, $yearPeriod);
                if ($dataIdx <= ($dayCountInMonth + 8)) {
                    $spreadsheet->getActiveSheet()->getStyle("B" . $dataIdx . ":B" . $dataIdx)->applyFromArray($allBorderStyle);
                    $spreadsheet->getActiveSheet()->getStyle("B" . $dataIdx . ":B" . $dataIdx)->applyFromArray($right);
                    $spreadsheet->getActiveSheet()->setCellValue('B' . ($dataIdx), $rdData);
                }
                /* END ROSTER DAY TITLE */
            }
        }






        if ($tMonth == 1) {
            $monthCount = 12;
            $yearCount = $tYear - 1;
        } else {
            $monthCount = $tMonth - 1;
            $yearCount = $tYear;
        }
        $dateInMonth = cal_days_in_month(CAL_GREGORIAN, $monthCount, $yearCount);

        $dateCal = $calendarStart;
        $isEndCalc = 0;
        $rowIdx_new = 8;
        /** ISI TABLE ABSENSI PER HARI */
        for ($i = 1; $i <= $countDay; $i++) {
            // JIKA ADA ROSTER BASE FORMAT
            if ($rosterBase > 0) {
                /* START NUMBER TITLE */
                $rowIdx++;
                $rowIdx_new++;

                $ot01Column = '';
                $ot02Column = '';
                $ot03Column = '';
                $ot04Column = '';

                if ($i < 10) {
                    $ot01Column = 'ot1_d0' . $i;
                    $ot02Column = 'ot2_d0' . $i;
                    $ot03Column = 'ot3_d0' . $i;
                    $ot04Column = 'ot4_d0' . $i;
                    $timePerday = 'd0' . $i;
                } else {
                    $ot01Column = 'ot1_d' . $i;
                    $ot02Column = 'ot2_d' . $i;
                    $ot03Column = 'ot3_d' . $i;
                    $ot04Column = 'ot4_d' . $i;
                    $timePerday = 'd' . $i;
                }
                /* OVER TIME */
                $ot01Val = $rowData->$ot01Column;
                $ot02Val = $rowData->$ot02Column;
                $ot03Val = $rowData->$ot03Column;
                $ot04Val = $rowData->$ot04Column;

                $timeTotal = 0;
                $strTimePerday = $rowData->$timePerday;
                $tTimePerday = 0;

                /* START PUBLIC HOLIDAY  */
                $phCodeTmp = substr($strTimePerday, 0, 2);
                $phHoursTmp = substr($strTimePerday, 2, strlen($strTimePerday) - 2);
                if ((strtoupper($phCodeTmp) == "RO" || strtoupper($phCodeTmp) == "PH" || strtoupper($phCodeTmp) == "NS" || strtoupper($phCodeTmp) == "RN"
                    || strtoupper($phCodeTmp) == "PN" || strtoupper($phCodeTmp) == "KR") && (is_numeric($phHoursTmp)) && ($phHoursTmp > 0)) {
                    $tTimePerday = $phHoursTmp;
                } else {
                    $tTimePerday = $rowData->$timePerday;
                }

                if (is_numeric($tTimePerday)) {
                    $timeTotal = $tTimePerday;
                    if ($tTimePerday > 1) {
                        $attendStatus = 1;
                    }
                }

                $normalTime = $timeTotal - $ot01Val - $ot02Val - $ot03Val - $ot04Val;
                $normalTotal += $normalTime;

                $allTimeTotal += $timeTotal;

                $ot01Count += $ot01Val;
                $ot02Count += $ot02Val;
                $ot03Count += $ot03Val;
                $ot04Count += $ot04Val;

                if ($dateCal > $dayCountInMonth) {
                    $dateCal = 1;
                }

                $spreadsheet->getActiveSheet()->getStyle('A' . $rowIdx . ':A' . $rowIdx)->applyFromArray($allBorderStyle);
                $spreadsheet->getActiveSheet()->getStyle('A' . $rowIdx . ':A' . $rowIdx)->applyFromArray($right);
                $spreadsheet->getActiveSheet()->setCellValue('A' . $rowIdx, $dateCal);

                $dateCal++;
                /* END NUMBER TITLE */

                $attendStatus = 0;
                $rosterIdx++;
                if ($rosterIdx > 7) {
                    $rosterIdx = 1;
                }
                $rdData = $rosterIdx;

                /* Start Cek Roster STR & END */
                if (strtoupper($strTimePerday) == 'STR' || strtoupper($strTimePerday) == 'END' || strtoupper($strTimePerday) == 'BP' || strtoupper($strTimePerday) == 'PR') {
                    $rdData = $strTimePerday;
                }
                /* END Cek Roster STR & END */

                /* START SHIFT DAY TITLE */
                if (strtoupper($phHoursTmp) == 'PH') {
                    $attendStatus = 'PH';
                    $rdData = $phCodeTmp;
                } elseif (is_numeric($tTimePerday)) {
                    if ($tTimePerday > 0) {
                        if (is_numeric($phCodeTmp)) {
                            $attendStatus = 1;
                        } else {
                            $attendStatus = $phCodeTmp;
                        }
                        $shiftCount++;
                    } else {
                        $attendStatus = 0;
                    }
                } elseif ($tTimePerday == '') {
                    $attendStatus = 0;
                } else {
                    $attendStatus = $tTimePerday;
                }
            } else {
                /* START NUMBER TITLE */
                $rowIdx++;
                $rowIdx_new++;

                $ot01Column = '';
                $ot02Column = '';
                $ot03Column = '';
                $ot04Column = '';

                if ($i < 10) {
                    $ot01Column = 'ot1_d0' . $i;
                    $ot02Column = 'ot2_d0' . $i;
                    $ot03Column = 'ot3_d0' . $i;
                    $ot04Column = 'ot4_d0' . $i;
                    $timePerday = 'd0' . $i;
                } else {
                    $ot01Column = 'ot1_d' . $i;
                    $ot02Column = 'ot2_d' . $i;
                    $ot03Column = 'ot3_d' . $i;
                    $ot04Column = 'ot4_d' . $i;
                    $timePerday = 'd' . $i;
                }
                /* OVER TIME */
                $ot01Val = $rowData->$ot01Column;
                $ot02Val = $rowData->$ot02Column;
                $ot03Val = $rowData->$ot03Column;
                $ot04Val = $rowData->$ot04Column;

                $timeTotal = 0;
                $strTimePerday = $rowData->$timePerday;
                $tTimePerday = 0;

                /* START PUBLIC HOLIDAY  */
                $phCodeTmp = substr($strTimePerday, 0, 2);
                $phHoursTmp = substr($strTimePerday, 2, strlen($strTimePerday) - 2);
                if ((strtoupper($phCodeTmp) == "RO" || strtoupper($phCodeTmp) == "PH" || strtoupper($phCodeTmp) == "NS" || strtoupper($phCodeTmp) == "RN"
                    || strtoupper($phCodeTmp) == "PN" || strtoupper($phCodeTmp) == "KR") && (is_numeric($phHoursTmp)) && ($phHoursTmp > 0)) {
                    $tTimePerday = $phHoursTmp;
                } else {
                    $tTimePerday = $rowData->$timePerday;
                }

                if (is_numeric($tTimePerday)) {
                    $timeTotal = $tTimePerday;
                    if ($tTimePerday > 1) {
                        $attendStatus = 1;
                    }
                }

                $normalTime = $timeTotal - $ot01Val - $ot02Val - $ot03Val - $ot04Val;
                $normalTotal += $normalTime;

                $allTimeTotal += $timeTotal;

                $ot01Count += $ot01Val;
                $ot02Count += $ot02Val;
                $ot03Count += $ot03Val;
                $ot04Count += $ot04Val;

                if ($dateCal > $dayCountInMonth) {
                    $dateCal = 1;
                }

                $spreadsheet->getActiveSheet()->getStyle('A' . $rowIdx . ':A' . $rowIdx)->applyFromArray($allBorderStyle);
                $spreadsheet->getActiveSheet()->getStyle('A' . $rowIdx . ':A' . $rowIdx)->applyFromArray($right);
                $spreadsheet->getActiveSheet()->setCellValue('A' . $rowIdx, $dateCal);

                $dateCal++;
                /* END NUMBER TITLE */

                $attendStatus = 0;
                $rosterIdx++;
                if ($rosterIdx > 7) {
                    $rosterIdx = 1;
                }
                $rdData = $rosterIdx;
                if (strtoupper($strTimePerday) == 'STR' || strtoupper($strTimePerday) == 'END' || strtoupper($strTimePerday) == 'BP' || strtoupper($strTimePerday) == 'PR') {
                    $rdData = $strTimePerday;
                    $rosterIdx = 0;
                }
                if (strtoupper($strTimePerday) == 'ID' || strtoupper($strTimePerday) == 'MD' || strtoupper($strTimePerday) == 'AL'  || strtoupper($strTimePerday) == 'PS' || strtoupper($strTimePerday) == 'A' || strtoupper($strTimePerday) == 'S') {
                    $rdData = $strTimePerday;
                }

                if (strtoupper($phCodeTmp) == 'PH' || strtoupper($phCodeTmp) == 'RO' || strtoupper($phCodeTmp) == 'NS' || strtoupper($phCodeTmp) == 'RN' || strtoupper($phCodeTmp) == 'PN' || strtoupper($phCodeTmp) == 'KR') {
                    $rdData = $phCodeTmp;
                }

                $hourVal = $this->right($strTimePerday, 1);
                if (is_numeric($hourVal)) {
                    $attendStatus = 1;
                }

                $arrTmpCode = array('PH', 'RO', 'NS', 'RN', 'PN', 'ID', 'AL', 'MD', 'PS', 'KR');
                $arrOtTmp = array('A', 'U', 'S', 'V', 'E', 'STR', 'END', 'BP', 'PR');
                if ((!in_array(strtoupper($phCodeTmp), $arrTmpCode)) && (!in_array(strtoupper($strTimePerday), $arrOtTmp)) && !is_numeric($strTimePerday)) {
                    $attendStatus = 0;
                }

                if ($rdData == 1 || $rdData == 2 || $rdData == 3 || $rdData == 4 || $rdData == 5 || $rdData == 6 || $rdData == 7 || $rdData == 8 || $rdData == 9) {
                    $rdData = 'DS';
                }
                $spreadsheet->getActiveSheet()
                    ->setCellValue('B' . $rowIdx, $rdData);
            }

            $spreadsheet->getActiveSheet()
                ->setCellValue('C' . $rowIdx, $attendStatus)
                ->setCellValue('D' . $rowIdx, $timeTotal)
                ->setCellValue('E' . $rowIdx, $normalTime)
                ->setCellValue('F' . $rowIdx, $ot01Val)
                ->setCellValue('G' . $rowIdx, $ot02Val)
                ->setCellValue('H' . $rowIdx, $ot03Val)
                ->setCellValue('I' . $rowIdx, $ot04Val);

            $spreadsheet->getActiveSheet()->getStyle("B" . $rowIdx . ":I" . $rowIdx)->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("B" . $rowIdx . ":I" . $rowIdx)->applyFromArray($center);
        }
        $end = $isEnd;
        if ($rdData == 'END' || $isEnd == '1') {
            $isEnd = 1;
        }



        $potongPajak = $bruttoTotal - $taxFinal - $jkkJkm - $empJht - $empHealthBpjs - $healthBpjs - $empJp;
        // dd($bruttoTotal, $taxFinal, $jkkJkm, $empJht, $empHealthBpjs, $healthBpjs, $empJp);
        // dd($potongPajak);
        $sebelumPembulatan = round($potongPajak + $tBurden + $thrByUser + $workDayAdjustment);
        // Hapus data lama berdasarkan ID
        $this->mRosterHist->where('rh_id', $slipId)->delete();

        // Siapkan data baru
        $data = [
            'rh_id'        => $slipId,
            'biodata_id'   => $biodataId,
            'client_name'  => $ptName,
            'year_period'  => $yearPeriod,
            'month_period' => $monthPeriod,
            'last_day'     => $rosterIdx,
        ];

        // Simpan data baru ke tabel
        $this->mRosterHist->insert($data);


        // $totalTitle = 40;
        $totalTitle = $rowIdx + 1;
        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . $totalTitle, 'TOTAL')
            ->setCellValue('C' . $totalTitle, $wdAttendCount)
            ->setCellValue('D' . $totalTitle, $allTimeTotal)
            ->setCellValue('E' . $totalTitle, $normalTotal)
            ->setCellValue('F' . $totalTitle, $ot01Count)
            ->setCellValue('G' . $totalTitle, $ot02Count)
            ->setCellValue('H' . $totalTitle, $ot03Count)
            ->setCellValue('I' . $totalTitle, $ot04Count);

        $totalTitle1 = $totalTitle + 1;
        $spreadsheet->getActiveSheet()->getStyle("A" . $totalTitle . ":A" . $totalTitle1)->applyFromArray($left);

        $spreadsheet->getActiveSheet()->getStyle("C" . $totalTitle . ":I" . $totalTitle1)->applyFromArray($right);
        $spreadsheet->getActiveSheet()->getStyle("A" . $totalTitle . ":I" . $totalTitle1)->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A" . $totalTitle . ":I" . $totalTitle1)->getFont()->setBold(true)->setSize(13);

        /* END TITLE */

        /* START TOTAL OVER TIME  */
        $totalTitle++;
        /* Title */
        $spreadsheet->getActiveSheet()->getStyle("A" . $totalTitle . ":A" . $totalTitle)->applyFromArray($left);
        $spreadsheet->getActiveSheet()->getStyle("A" . $totalTitle . ":A" . $totalTitle)->getFont()->setBold(true)->setSize(13);
        $spreadsheet->getActiveSheet()->setCellValue('A' . $totalTitle, 'TOTAL OT');
        /* Data */
        $total_ot       = $ot01Count + $ot02Count + $ot03Count + $ot04Count;

        $spreadsheet->getActiveSheet()->getStyle("C" . $totalTitle . ":I" . $totalTitle . "")->applyFromArray($right);
        $spreadsheet->getActiveSheet()->getStyle("A" . $totalTitle . ":I" . $totalTitle . "")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->mergeCells("C" . $totalTitle . ":I" . $totalTitle . "");
        $spreadsheet->getActiveSheet()->getStyle("C" . $totalTitle . ":I" . $totalTitle . "")->getFont()->setBold(true)->setSize(13);
        $spreadsheet->getActiveSheet()->setCellValue("C" . $totalTitle . "", $total_ot);

        // $payrollConfig = $this->mPayrollConfig->loadByClient($ptName);

        $salaryDividerConfig = $payrollConfig['salary_divider'];
        $salaryHourly = $basicSalary / $salaryDividerConfig;
        // $salaryHourly = number_format($salaryHourly,2);
        $otMultiplierConfig01 = $payrollConfig['ot_01_multiplier'];
        // $otMultiplierConfig01 = number_format($otMultiplierConfig01,1);
        $otMultiplierConfig02 = $payrollConfig['ot_02_multiplier'];
        // $otMultiplierConfig02 = number_format($otMultiplierConfig02,1);
        $otMultiplierConfig03 = $payrollConfig['ot_03_multiplier'];
        // $otMultiplierConfig03 = number_format($otMultiplierConfig03,1);
        $otMultiplierConfig04 = $payrollConfig['ot_04_multiplier'];
        // $otMultiplierConfig04 = number_format($otMultiplierConfig04,1);

        $spreadsheet->getActiveSheet()->getStyle("K4:L4")->applyFromArray($left);
        $spreadsheet->getActiveSheet()->getStyle("K3:Q43")->getFont()->setSize(13);

        if (strlen($npwpNo) < 19) {
            $npwpnoExcel = '-';
        } else {
            $npwpnoExcel = $npwpNo;
        }


        $spreadsheet->getActiveSheet()
            // ->setCellValue('K3', 'PERIODE KONTRAK')
            // ->setCellValue('M3', '')
            // ->setCellValue('N3', 's/d')
            ->setCellValue('O3', '')
            ->setCellValue('K4', 'NAMA')
            ->setCellValue('M4', $bioName)
            ->setCellValue('N4', 'Id')
            ->setCellValue('O4', $nie)
            ->setCellValue('K5', 'JABATAN')
            ->setCellValue('M5', $position)
            ->setCellValue('N5', 'DEPT ')
            ->setCellValue('O5', $dept)
            ->setCellValue('N6', 'STATUS')
            ->setCellValue('O6', $maritalStatus)
            ->setCellValue('K6', 'GAJI POKOK')
            ->setCellValue('M6', $basicSalary)
            ->setCellValue('K7', 'RATE/HOUR')
            ->setCellValue('M7', $salaryHourly)
            ->setCellValue('N7', 'NPWP')
            ->setCellValue('O7', $npwpnoExcel)
            ->setCellValue('K9', 'Upah') //textUPAH
            ->setCellValue('O9', round($bsProrate))
            ->setCellValue('K10', 'OT 1')
            ->setCellValue('K11', 'OT 2')
            ->setCellValue('K12', 'OT 3')
            ->setCellValue('K13', 'OT 4');

        $spreadsheet->getActiveSheet()->getStyle('M6:M7')->applyFromArray($totalStyle);
        $spreadsheet->getActiveSheet()->getStyle("K9")->applyFromArray($totalStyle);
        $spreadsheet->getActiveSheet()->getStyle("O9")->applyFromArray($totalStyle);
        $spreadsheet->getActiveSheet()->getStyle('N8:N9')->applyFromArray($boldFont);
        $spreadsheet->getActiveSheet()->getStyle('M8:M9')->applyFromArray($boldFont);



        $strOT1 = number_format($otCount1, 1) . " x " . number_format($otMultiplierConfig01, 1) . " x " . number_format($salaryHourly, 2) . " = Rp.";
        $strOT2 = number_format($otCount2, 1) . " x " . number_format($otMultiplierConfig02, 1) . " x " . number_format($salaryHourly, 2) . " = Rp.";
        $strOT3 = number_format($otCount3, 1) . " x " . number_format($otMultiplierConfig03, 1) . " x " . number_format($salaryHourly, 2) . " = Rp.";
        $strOT4 = number_format($otCount4, 1) . " x " . number_format($otMultiplierConfig04, 1) . " x " . number_format($salaryHourly, 2) . " = Rp.";

        $spreadsheet->getActiveSheet()
            ->setCellValue('M10', $strOT1)
            ->setCellValue('N10', $otTotal1)
            ->setCellValue('M11', $strOT2)
            ->setCellValue('N11', $otTotal2)
            ->setCellValue('M12', $strOT3)
            ->setCellValue('N12', $otTotal3)
            ->setCellValue('M13', $strOT4)
            ->setCellValue('N13', $otTotal4)
            ->setCellValue('K14', 'OT TOTAL')
            ->setCellValue('O14', round($allOt));

        $spreadsheet->getActiveSheet()->getStyle('K14:O14')->applyFromArray($topBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle('K14:O14')->applyFromArray($totalStyle);


        $bonusIdx = 16;
        $spreadsheet->getActiveSheet()
            ->setCellValue('K' . ($bonusIdx + 1), 'Tunjangan Kehadiran')
            ->setCellValue('M' . ($bonusIdx + 1), $wdAttendCount . ' Hari')
            ->setCellValue('N' . ($bonusIdx + 1), $attendanceBonus)
            ->setCellValue('K' . ($bonusIdx + 2), 'Tunjangan Transport')
            ->setCellValue('M' . ($bonusIdx + 2), $wdAttendCount . ' Hari')
            ->setCellValue('N' . ($bonusIdx + 2), $transportBonus)
            // 24-05-2023 + Insentif Bonus
            ->setCellValue('K' . ($bonusIdx + 3), 'Tunjangan Shift Malam')
            ->setCellValue('M' . ($bonusIdx + 3), $tNightShiftCount . ' Hari')
            ->setCellValue('N' . ($bonusIdx + 3), $nightShiftBonus)
            ->setCellValue('K' . ($bonusIdx + 4), 'Tunjangan')
            ->setCellValue('N' . ($bonusIdx + 4), $tunjangan)
            ->setCellValue('K' . ($bonusIdx + 5), 'THR')
            ->setCellValue('N' . ($bonusIdx + 5), $thr)

            ->setCellValue('K' . ($bonusIdx + 6), 'BONUS TOTAL')
            ->setCellValue('O' . ($bonusIdx + 6), $bonusTotal);


        $spreadsheet->getActiveSheet()->getStyle('M' . ($bonusIdx + 1) . ':M' . ($bonusIdx + 3))->applyFromArray($right);
        $spreadsheet->getActiveSheet()->getStyle('M' . ($bonusIdx + 1) . ':M' . ($bonusIdx + 3))->applyFromArray($italic);
        $spreadsheet->getActiveSheet()
            ->getStyle('M' . ($bonusIdx + 1) . ':M' . ($bonusIdx + 3))
            ->getFont()
            ->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle('K' . ($bonusIdx + 6) . ':O' . ($bonusIdx + 6))->applyFromArray($topBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle('K' . ($bonusIdx + 6) . ':O' . ($bonusIdx + 6))->applyFromArray($totalStyle);

        /* START GOVERNMENT REGULATION */
        $subTotalVal = $jkkJkm + $healthBpjs /*+ $rowData->jp*/ + $unpaidTotal + $adjustmentIn + $adjustmentOut;

        $govRegIdx = 24;
        $spreadsheet->getActiveSheet()
            ->setCellValue('K' . $govRegIdx, 'JKK & JKM(2.04%)')
            ->setCellValue('N' . $govRegIdx, $jkkJkm)
            ->setCellValue('K' . ($govRegIdx + 1), 'Iuran BPJS Kesehatan(4%)')
            ->setCellValue('N' . ($govRegIdx + 1), $healthBpjs)
            ->setCellValue('K' . ($govRegIdx + 2), 'Potongan Absensi ' . $tUnpaidCount . ' Hari')
            ->setCellValue('N' . ($govRegIdx + 2), -$unpaidTotal)
            ->setCellValue('K' . ($govRegIdx + 3), 'Adjustment In')
            ->setCellValue('N' . ($govRegIdx + 3), $adjustmentIn)
            ->setCellValue('K' . ($govRegIdx + 4), 'Adjustment Out')
            ->setCellValue('N' . ($govRegIdx + 4), $adjustmentOut)
            ->setCellValue('K' . ($govRegIdx + 5), 'Subtotal')
            ->setCellValue('O' . ($govRegIdx + 5), round($subTotalVal));

        $spreadsheet->getActiveSheet()->getStyle('K' . ($govRegIdx + 5) . ':O' . ($govRegIdx + 5))->applyFromArray($totalStyle);
        /* END Penghasilan Kotor */
        $bruttoCoordinate = $govRegIdx + 5;
        $spreadsheet->getActiveSheet()
            ->setCellValue('K' . $bruttoCoordinate, 'TOTAL KOTOR')
            ->setCellValue('O' . $bruttoCoordinate, $bruttoTotal);

        $spreadsheet->getActiveSheet()->getStyle('K' . $bruttoCoordinate . ':O' . $bruttoCoordinate)->applyFromArray($topBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle('K' . $bruttoCoordinate . ':O' . $bruttoCoordinate)->applyFromArray($totalStyle);

        $outCoordinate = 31;
        $spreadsheet->getActiveSheet()
            ->setCellValue('K' . $outCoordinate, 'Pajak Penghasilan')
            ->setCellValue('O' . $outCoordinate, 0)
            ->setCellValue('K' . ($outCoordinate + 1), 'JKK & JKM(2.04%)')
            ->setCellValue('O' . ($outCoordinate + 1), 0)
            ->setCellValue('K' . ($outCoordinate + 2), 'Iuran JHT BPJS TK(2%)')
            ->setCellValue('O' . ($outCoordinate + 2), 0)
            ->setCellValue('K' . ($outCoordinate + 3), 'Iuran BPJS Kesehatan(1%)')
            ->setCellValue('O' . ($outCoordinate + 3), 0)
            ->setCellValue('K' . ($outCoordinate + 4), 'Iuran BPJS Kesehatan(4%)')
            ->setCellValue('O' . ($outCoordinate + 4), 0)
            ->setCellValue('K' . ($outCoordinate + 5), 'Iuran JP TK(1%)')
            ->setCellValue('O' . ($outCoordinate + 5), 0);

        $namaPayroll = str_replace('\\', '', substr($bioName, 0, 25));
        $str = $namaPayroll . $bioRecId . '-' . $monthPeriod;
        // Check Closing Payroll
        $checkEnd = 0;
        if ($isEnd == '1') {
            $checkEnd = 1;
        } else if ($rdData == 'END' && $isEnd == '1') {
            $checkEnd = 1;
        } else if ($isEndCalc > 0) {
            $checkEnd = 1;
        }

        // Always append '-END' to $str and $namaPayroll when $checkEnd is 1
        // if ($checkEnd == 1) {
        //     $terGolongan = 'END';
        //     $str .= '-END';
        //     $namaPayroll .= ' - END';
        // } else if ($checkEnd == 0) {
        // }
        // if ($monthPeriod == 12 && $checkEnd != 1) {
        //     $terGolongan = 'FINAL';
        // }

        if ($terPersentase == 'END') {
            $spreadsheet->getActiveSheet()->setCellValue('K' . $outCoordinate, "Pajak Penghasilan");
        } else {
            $spreadsheet->getActiveSheet()->setCellValue('K' . $outCoordinate, "Pajak Penghasilan");
            $spreadsheet->getActiveSheet()->setCellValue('M' . $outCoordinate, $terGolongan);
            $spreadsheet->getActiveSheet()->setCellValue('N' . $outCoordinate, $terPersentase . "%");
            $spreadsheet->getActiveSheet()->getStyle("M" . $outCoordinate . ":N" . $outCoordinate)->applyFromArray($right);
        }
        $spreadsheet->getActiveSheet()->setCellValue('O' . $outCoordinate, "=ROUNDDOWN(-(" . $taxFinal . "),0)");
        /** End Pajak Penghasilan Final */
        if ($jkkJkm > 0) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('O' . ($outCoordinate + 1), -$jkkJkm);
        }

        if ($empJht > 0) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('O' . ($outCoordinate + 2), -$empJht);
        }

        if ($empHealthBpjs > 0) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('O' . ($outCoordinate + 3), -$empHealthBpjs);
        }

        if ($healthBpjs > 0) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('O' . ($outCoordinate + 4), -$healthBpjs);
        }

        if ($empJp > 0) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('O' . ($outCoordinate + 5), -$empJp);
        }

        $outCoordinate2 = $outCoordinate + 6;
        $spreadsheet->getActiveSheet()->getStyle('K' . $outCoordinate2 . ':O' . $outCoordinate2)->applyFromArray($topBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle('K' . $outCoordinate2 . ':O' . $outCoordinate2)->applyFromArray($totalStyle);

        $debtExplanation = '';

        $debtText = "";
        if ($debtExplanation != "") {
            $debtText = "(" . $debtExplanation . ")";
        }


        $cutCoordinate = 37;
        $spreadsheet->getActiveSheet()
            ->setCellValue('K' . $outCoordinate2, 'Potong Pajak')
            ->setCellValue('O' . $outCoordinate2, round($potongPajak))
            ->setCellValue('K' . ($cutCoordinate + 2), 'THR')
            ->setCellValue('O' . ($cutCoordinate + 2), $thrByUser)
            ->setCellValue('K' . ($cutCoordinate + 3), 'Adj')
            ->setCellValue('O' . ($cutCoordinate + 3), $workDayAdjustment)

            ->setCellValue('K' . ($cutCoordinate + 4), 'Beban Hutang')
            ->setCellValue('M' . ($cutCoordinate + 4), $debtText)
            ->setCellValue('O' . ($cutCoordinate + 4), $tBurden);


        $cutCoordinate2 = $cutCoordinate + 4;
        $cutCoordinate3 = $cutCoordinate + 6;
        $spreadsheet->getActiveSheet()->getStyle("K" . $cutCoordinate . ":O" . $cutCoordinate3)->getFont()->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle('K' . $cutCoordinate3 . ':O' . $cutCoordinate3)->applyFromArray($totalStyle);
        $spreadsheet->getActiveSheet()->getStyle('K' . $cutCoordinate3 . ':O' . $cutCoordinate3)->applyFromArray($topBorderStyle);

        $pembulatanGaji = ConfigurationHelper::pembulatanTotal($sebelumPembulatan);
        $totalTerima = $sebelumPembulatan + $pembulatanGaji;

        $spreadsheet->getActiveSheet()->setCellValue('K' . ($cutCoordinate2 + 3), 'Pembulatan')->setCellValue('O' . ($cutCoordinate2 + 3), $pembulatanGaji);
        $spreadsheet->getActiveSheet()->getStyle('K' . ($cutCoordinate2 + 3) . ':O' . ($cutCoordinate2 + 3))->getFont()->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle('K' . ($cutCoordinate2 + 4) . ':O' . ($cutCoordinate2 + 4))->getFont()->setSize(13);
        $spreadsheet->getActiveSheet()->getStyle('K' . ($cutCoordinate2 + 4) . ':O' . ($cutCoordinate2 + 4))->applyFromArray($totalStyle);
        $spreadsheet->getActiveSheet()->getStyle('K' . ($cutCoordinate2 + 4) . ':O' . ($cutCoordinate2 + 4))->applyFromArray($topBorderStyle);
        $spreadsheet->getActiveSheet()
            ->setCellValue('K' . ($cutCoordinate2 + 4), 'TOTAL TERIMA')
            ->setCellValue('O' . ($cutCoordinate2 + 4), round($totalTerima));
        /** END PEMBULATAN TOTAL TERIMA */

        if ($rdData == 'END' || $isEnd == '1') {
            // $koorBayar = '44';
            $koorBayar = $totalTitle + 5;
        } else {
            // $koorBayar = '46';
            $koorBayar = $totalTitle + 5;
        }
        $spreadsheet->getActiveSheet()->getStyle('A' . ($koorBayar) . ':I' . ($koorBayar + 18))->applyFromArray($outlineBorderStyle);
        $spreadsheet->getActiveSheet()
            ->mergeCells("A" . ($koorBayar + 1) . ":I" . ($koorBayar + 1) . "")
            ->mergeCells("A" . ($koorBayar + 4) . ":I" . ($koorBayar + 4) . "")
            ->mergeCells("A" . ($koorBayar + 8) . ":I" . ($koorBayar + 8) . "")
            ->mergeCells("A" . ($koorBayar + 12) . ":I" . ($koorBayar + 12) . "")
            ->mergeCells("A" . ($koorBayar + 16) . ":I" . ($koorBayar + 16) . "");

        $spreadsheet->getActiveSheet()->getStyle('A' . ($koorBayar) . ':I' . ($koorBayar + 18))->applyFromArray($center);

        $salary = $this->mSalary->getByBioId($biodataId);
        $bankName = $salary['bank_name'];
        $bankNo = $salary['account_no'];

        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . ($koorBayar + 1), 'Dibayarkan Oleh')
            ->setCellValue('A' . ($koorBayar + 4), 'Payroll/ Accounting')
            ->setCellValue('A' . ($koorBayar + 8), 'Diterima Oleh Karyawan')
            ->setCellValue('A' . ($koorBayar + 12), str_replace('\\', '', $bioName))
            ->setCellValue('A' . ($koorBayar + 16), $bankName . " : " . $bankNo);
        // $spreadsheet->getActiveSheet()->getStyle('M6:O68')->getNumberFormat()->setFormatCode('#,##0');
        $spreadsheet->getActiveSheet()->getStyle('M6:O68')->getNumberFormat()->setFormatCode('#,##0.00');
        // $spreadsheet->getActiveSheet()->getStyle('M6:O68')->getNumberFormat()->setFormatCode('#,#');


        // if ($checkEnd == '1'  || $monthPeriod == '12') {
        //     $cordinatepajak = 47;
        //     $endStatus = 1;
        //     $this->getViewExcelDefaultTaxEnd($spreadsheet, $cordinatepajak, $center, $totalStyle, $topBorderStyle, $bottomBorderStyle, $outlineBorderStyle, $rowData, $bioRecId, $bruttoTotal, $marital, $endStatus, $clientName, $monthPeriod, $yearPeriod, $empJp, $empJht);
        // }

        $spreadsheet->getActiveSheet()->setTitle($namaPayroll);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(19);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(29);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(14);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(23);
        $spreadsheet->setActiveSheetIndex($noNewSheet);

        $fileName = preg_replace('/\s+/', '', $str);
        $spreadsheet->createSheet();
        return $fileName;
    }
    private function getMonthName($monthNumber)
    {
        $months = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $months[$monthNumber] ?? 'Invalid month number';
    }

    public function updatePicPrint($slipId, $clientName, $biodataId)
    {
        // Koneksi ke database
        $db = \Config\Database::connect();

        // 🔍 Cek apakah data slip ada
        $checkSql = "
        SELECT * FROM tr_slip
        WHERE slip_id = ? AND client_name = ? AND biodata_id = ?
        LIMIT 1
    ";
        $query = $db->query($checkSql, [$slipId, $clientName, $biodataId]);
        $slip = $query->getRowObject();

        // Jika tidak ditemukan
        if (!$slip) {
            return $this->response->setJSON(['message' => 'Salary slip not found'])->setStatusCode(404);
        }

        // Ambil user_id (kalau pakai session login CI4)
        $userId = session()->get('uId');

        // Waktu sekarang
        $currentTime = date('Y-m-d H:i:s');

        // 📝 Query update
        $updateSql = "
        UPDATE tr_slip
        SET pic_print = ?, print_time = ?
        WHERE slip_id = ? AND client_name = ? AND biodata_id = ?
    ";

        $db->query($updateSql, [
            $userId,
            $currentTime,
            $slipId,
            $clientName,
            $biodataId
        ]);

        // Debugging jika perlu
        if ($db->error()['code'] !== 0) {
            echo "<pre>DB Error: " . $db->error()['message'] . "</pre>";
            return $this->response->setJSON(['message' => 'Database error'])->setStatusCode(500);
        }

        return $this->response->setJSON(['message' => 'Salary slip updated successfully']);
    }

    private function getAllowanceData($bioRecId, $clientName, $year, $month)
    {
        // Panggil model Allowance (pastikan sudah dimuat sebelumnya)
        $mAllowance = new MtAllowance();

        // Ambil data allowance dari model
        $allowanceData = $mAllowance->selectAllowanceAll($bioRecId, $clientName, $year, $month, '');
        // Inisialisasi variabel
        $thr = 0;
        $tunjangan = 0;
        $attendanceBonus = 0;
        $transportBonus = 0;
        $nightShiftBonus = 0;
        $adjustmentIn = 0;
        $adjustmentOut = 0;
        $workDayAdjustment = 0;
        $thrByUser = 0;
        $debtBurden = 0;

        // Loop data allowance
        foreach ($allowanceData as $allowance) {
            $name = $allowance['allowance_name'] ?? '';
            $amount = $allowance['allowance_amount'] ?? 0;

            switch ($name) {
                case 'thr':
                    $thr += $amount;
                    break;
                case 'tunjangan':
                    $tunjangan += $amount;
                    break;
                case 'night_shift_bonus':
                    $nightShiftBonus += $amount;
                    break;
                case 'transport_bonus':
                    $transportBonus += $amount;
                    break;
                case 'attendance_bonus':
                    $attendanceBonus += $amount;
                    break;
                case 'adjustment_in':
                    $adjustmentIn += $amount;
                    break;
                case 'adjustment_out':
                    $adjustmentOut += $amount;
                    break;
                case 'workday_adjustment':
                    $workDayAdjustment += $amount;
                    break;
                case 'thr_by_user':
                    $thrByUser += $amount;
                    break;
                case 'debt_burden':
                    $debtBurden += $amount;
                    break;
            }
        }

        // Total keseluruhan allowance
        $total = $thr + $tunjangan + $nightShiftBonus + $transportBonus + $attendanceBonus + $adjustmentIn + $adjustmentOut + $workDayAdjustment + $thrByUser + $debtBurden;

        return [
            'thr' => $thr,
            'tunjangan' => $tunjangan,
            'nightShiftBonus' => $nightShiftBonus,
            'transportBonus' => $transportBonus,
            'attendanceBonus' => $attendanceBonus,
            'adjustmentIn' => $adjustmentIn,
            'adjustmentOut' => $adjustmentOut,
            'workDayAdjustment' => $workDayAdjustment,
            'thrByUser' => $thrByUser,
            'debtBurden' => $debtBurden,
            'amountAll' => $total,
        ];
    }

    private function getViewExcelDefaultTaxEnd($spreadsheet, $cordinatepajak, $center, $totalStyle, $topBorderStyle, $bottomBorderStyle, $outlineBorderStyle, $rowData, $bioRecId, $bruttoTotal, $marital, $end, $clientName, $monthPeriod, $yearPeriod, $empJp, $empJht)
    {
        // === Model initialization ===
        $mTax = new Tax();
        $mSalarySlip = new SalarySlip();

        // === Ambil data pajak & ptkp ===
        $taxData = $mTax->getTaxDataByBiodata($clientName, $bioRecId, $yearPeriod, $monthPeriod);
        $ptkpData = $mSalarySlip->getPtkpByBiodataId($clientName, $bioRecId, $yearPeriod, $monthPeriod);
        $nilaiPtkp = $ptkpData->ptkp_total ?? 0;

        $bruttoSetahun        = $taxData->brutto_setahun ?? 0;
        $jpSetahun            = $taxData->jp_setahun ?? 0;
        $jhtSetahun           = $taxData->jht_setahun ?? 0;
        $tunjanganJabatan     = $taxData->tunjangan_jabatan_setahun ?? 0;
        $nettoSetahun         = $taxData->netto_setahun ?? 0;
        $maritalStatus        = $rowData->marital_status ?? '';
        $penghasilanPajak     = $taxData->penghasilan_pajak ?? 0;
        $penghasilanPajakBulat = $taxData->penghasilan_pajak_bulat ?? 0;
        $totalTaxSebelumnya   = $taxData->total_tax_sebelumnya ?? 0;

        $taxVal1 = $taxData->tax_val_1 ?? 0;
        $taxVal2 = $taxData->tax_val_2 ?? 0;
        $taxVal3 = $taxData->tax_val_3 ?? 0;
        $taxVal4 = $taxData->tax_val_4 ?? 0;
        $taxVal5 = $taxData->tax_val_5 ?? 0;
        $tax = $taxVal1 + $taxVal2 + $taxVal3 + $taxVal4 + $taxVal5;

        $taxPinalty = $taxData->tax_pinalty ?? 0;
        $taxFinal = $tax + $taxPinalty;

        $sheet = $spreadsheet->getActiveSheet();

        // === Style setup ===
        $sheet->mergeCells("K{$cordinatepajak}:O{$cordinatepajak}");
        $sheet->getStyle("K{$cordinatepajak}:O{$cordinatepajak}")->applyFromArray($center);
        $sheet->getStyle("K{$cordinatepajak}:O{$cordinatepajak}")->applyFromArray($totalStyle);
        $sheet->getStyle("K" . ($cordinatepajak + 16) . ":O" . ($cordinatepajak + 18))->applyFromArray($totalStyle);
        $sheet->getStyle("K" . ($cordinatepajak + 5) . ":O" . ($cordinatepajak + 5))->applyFromArray($topBorderStyle);
        $sheet->getStyle("K" . ($cordinatepajak + 7) . ":O" . ($cordinatepajak + 7))->applyFromArray($topBorderStyle);
        $sheet->getStyle("K" . ($cordinatepajak + 14) . ":O" . ($cordinatepajak + 14))->applyFromArray($topBorderStyle);
        $sheet->getStyle("K{$cordinatepajak}:O{$cordinatepajak}")->applyFromArray($bottomBorderStyle);
        $sheet->getStyle("K{$cordinatepajak}:O" . ($cordinatepajak + 16))->applyFromArray($outlineBorderStyle);
        $sheet->getStyle("K{$cordinatepajak}:O" . ($cordinatepajak + 16))->getFont()->setSize(13);

        // === Isi nilai ke spreadsheet ===
        $sheet->setCellValue("K{$cordinatepajak}", 'PERHITUNGAN FINALISASI PAJAK PENGHASILAN')
            ->setCellValue("K" . ($cordinatepajak + 1), 'Penghasilan Kotor')
            ->setCellValue("O" . ($cordinatepajak + 1), $bruttoSetahun)
            ->setCellValue("K" . ($cordinatepajak + 2), 'Iuran JP TK')
            ->setCellValue("O" . ($cordinatepajak + 2), $jpSetahun)
            ->setCellValue("K" . ($cordinatepajak + 3), 'Iuran JHT')
            ->setCellValue("O" . ($cordinatepajak + 3), $jhtSetahun)
            ->setCellValue("K" . ($cordinatepajak + 4), 'Tunjangan Jabatan')
            ->setCellValue("O" . ($cordinatepajak + 4), $tunjanganJabatan)
            ->setCellValue("K" . ($cordinatepajak + 5), 'Netto Disetahunkan (Tetap)')
            ->setCellValue("O" . ($cordinatepajak + 5), $nettoSetahun)
            ->setCellValue("K" . ($cordinatepajak + 6), "PTKP {$maritalStatus}")
            ->setCellValue("O" . ($cordinatepajak + 6), $nilaiPtkp)
            ->setCellValue("K" . ($cordinatepajak + 7), 'Penghasilan Kena Pajak')
            ->setCellValue("O" . ($cordinatepajak + 7), $penghasilanPajak)
            ->setCellValue("K" . ($cordinatepajak + 8), 'Pembulatan')
            ->setCellValue("O" . ($cordinatepajak + 8), $penghasilanPajakBulat);

        $cordinatetarif = $cordinatepajak + 9;

        $sheet->setCellValue("K{$cordinatetarif}", "Tarif Pajak I    5%")
            ->setCellValue("O{$cordinatetarif}", $taxVal1)
            ->setCellValue("K" . ($cordinatetarif + 1), "Tarif Pajak II   15%")
            ->setCellValue("O" . ($cordinatetarif + 1), $taxVal2)
            ->setCellValue("K" . ($cordinatetarif + 2), "Tarif Pajak III  25%")
            ->setCellValue("O" . ($cordinatetarif + 2), $taxVal3)
            ->setCellValue("K" . ($cordinatetarif + 3), "Tarif Pajak IV   30%")
            ->setCellValue("O" . ($cordinatetarif + 3), $taxVal4)
            ->setCellValue("K" . ($cordinatetarif + 4), "Tarif Pajak V   35%")
            ->setCellValue("O" . ($cordinatetarif + 4), $taxVal5)
            ->setCellValue("K" . ($cordinatetarif + 5), "Pajak Setahun")
            ->setCellValue("O" . ($cordinatetarif + 5), $tax)
            ->setCellValue("K" . ($cordinatetarif + 6), "Total Pajak Sebelumnya")
            ->setCellValue("O" . ($cordinatetarif + 6), $totalTaxSebelumnya)
            ->setCellValue("K" . ($cordinatetarif + 7), "Pajak Akhir")
            ->setCellValue("O" . ($cordinatetarif + 7), $tax - $totalTaxSebelumnya);
    }

    public function getAllowanceBySlipId($slipId, $clientName, $year, $month)
    {
        // Inisialisasi model (pastikan sudah di-import di atas)
        $mSalarySlip = new SalarySlip();
        $mAllowance  = new MtAllowance();

        // Ambil data salary slip
        $slipData = $mSalarySlip->findSalarySlipById($slipId);

        if (!$slipData) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Slip gaji tidak ditemukan.'
            ])->setStatusCode(404);
        }

        $biodataId   = $slipData['biodata_id'];
        $fullName    = $slipData['full_name'];
        $yearPeriod  = $slipData['year_period'];
        $monthPeriod = $slipData['month_period'];

        // Ambil data roster


        // Ambil data allowance
        $allowanceData = $mAllowance->selectAllowanceAll($biodataId, $clientName, $year, $month, '');
        $allowanceMap = [];
        foreach ($allowanceData as $item) {
            $allowanceMap[$item['allowance_name']] = $item['allowance_amount'];
        }
        // Gabungkan data menjadi satu array respons
        $responseData = array_merge((array)$allowanceMap, [
            'fullName'        => $fullName,
            'biodataId'       => $biodataId,
            'payrollId'       => $slipId,
            'clientName'       => $clientName,
            'yearPeriod'      => $yearPeriod,
            'monthPeriod'     => $monthPeriod,
        ]);

        // Kembalikan hasil JSON response
        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Successfully retrieve data',
            'data'    => $responseData
        ])->setStatusCode(200);
    }

    public function updatePayroll()
    {
        // Validate the request data
        $salarySlipId = $_POST['payrollId'];
        $biodataId = $_POST['biodataId'];
        $clientName = $_POST['clientName'];
        $year = $_POST['year'];
        $month = $_POST['month'];
        $thr = $_POST['thr'];
        $tunjangan = $_POST['tunjangan'];
        $adjustmentIn = $_POST['adjustmentIn'];
        $adjustmentOut = $_POST['adjustmentOut'];
        $workdayAdj = $_POST['workdayAdj'];
        $debtBurden = $_POST['debtBurden'];
        $thrByUser = $_POST['thrByUser'];

        $isHealthBPJS  = $this->request->getPost('isHealthBPJS');
        $isJHT         = $this->request->getPost('isJHT');
        $isJP          = $this->request->getPost('isJP');
        $isJKKM        = $this->request->getPost('isJKKM');
        $isEnd         = $this->request->getPost('isEnd');


        $this->mAllowance->updateAllowance($clientName, $biodataId, $year, $month, 'thr', $thr, null);
        $this->mAllowance->updateAllowance($clientName, $biodataId, $year, $month, 'tunjangan', $tunjangan, null);
        $this->mAllowance->updateAllowance($clientName, $biodataId, $year, $month, 'adjustment_in', $adjustmentIn, null);
        $this->mAllowance->updateAllowance($clientName, $biodataId, $year, $month, 'adjustment_out', -$adjustmentOut, null); // Minus nilainya
        $this->mAllowance->updateAllowance($clientName, $biodataId, $year, $month, 'workday_adjustment', $workdayAdj, null);
        $this->mAllowance->updateAllowance($clientName, $biodataId, $year, $month, 'debt_burden', -$debtBurden, null); // Minus nilainya
        $this->mAllowance->updateAllowance($clientName, $biodataId, $year, $month, 'thr_by_user', -$thrByUser, null); // Minus nilainya

        $selectedSalarySlip = $this->mSalarySlip->where('slip_id', $salarySlipId)->first();
        // var_dump($selectedSalarySlip);
        // exit();
        $isEnd = $selectedSalarySlip['is_end'];
        $dept = $selectedSalarySlip['dept'];
        $taxData =   $this->cTaxProcess->taxProcess($biodataId, $clientName, $year, $month, "All", $isEnd, $limit = null, $offset = null);
        $selectedSalarySlip->update([
            'pic_edit' => session('user_id'),
            'edit_time' => date('Y-m-d H:i:s'),
        ]);


        return ResponseFormatter::success(null, "Allowance updated successfully.", 200);
    }


    function right($string, $length)
    {
        return substr($string, -$length);
    }
}
