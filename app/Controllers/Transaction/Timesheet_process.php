<?php

namespace App\Controllers\Transaction;

use App\Controllers\BaseController;
use App\Helpers\ConfigurationHelper;
use App\Helpers\ResponseFormatter;
use App\Models\Admin\UserModel;
use App\Models\Master\Config;
use App\Models\Master\ConfigAllowance;
use App\Models\Master\M_mt_salary;
use App\Models\Master\MtAllowance;
use App\Models\Master\ProcessClossing;
use App\Models\Transaction\M_tr_overtime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Transaction\M_download;
use App\Models\Transaction\M_tr_slip;
use App\Models\Transaction\M_tr_timesheet;
use App\Models\Transaction\SalarySlip;
use Config\Database;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Timesheet_process extends BaseController
{
    protected $userModel;
    protected $mtdownload;
    protected $trSalarySlip;
    protected $trOvertime;
    protected $mtSalary;
    protected $mtProcessClossing;
    protected $mTimesheet;
    protected $mtPayrollConfig;
    protected $govController;
    protected $mAllowanceConfig;
    protected $taxProcessController;
    protected $mtAllowance;

    public function __construct()
    {
        $this->mtPayrollConfig = new Config();
        $this->userModel = new UserModel();
        $this->mtdownload = new M_download();
        $this->trSalarySlip = new SalarySlip();
        $this->mtSalary = new M_mt_salary();
        $this->trOvertime = new M_tr_overtime();
        $this->mtProcessClossing = new ProcessClossing();
        $this->mTimesheet = new M_tr_timesheet();
        $this->govController = new Gov_regulation();
        $this->mAllowanceConfig = new ConfigAllowance();
        $this->taxProcessController = new Tax_process();
        $this->mtAllowance = new MtAllowance();
    }

    public function index()
    {
        $session = session();
        $clients = $session->get('userClients');

        $data['clients'] = $clients;
        $data['actView'] = 'Transaction/timesheet_process';
        return view('home', $data);
    }

    public function process()
    {
        // batas waktu & memory
        set_time_limit(6000);
        ini_set('max_execution_time', 6000);
        ini_set('memory_limit', '-1');

        // ambil input dari request
        $bioId         = $this->request->getPost('biodataId');
        $slipId        = $this->request->getPost('slipId');
        $badgeNo       = $this->request->getPost('badgeNo');
        $clientName    = $this->request->getPost('clientName');
        $year          = $this->request->getPost('yearPeriod');
        $month         = $this->request->getPost('monthPeriod');
        $dataGroup     = $this->request->getPost('dataGroup');
        // custom process
        $isHealthBPJS  = $this->request->getPost('isHealthBPJS');
        $isJHT         = $this->request->getPost('isJHT');
        $isJP          = $this->request->getPost('isJP');
        $isJKKM        = $this->request->getPost('isJKKM');
        $isEnd         = $this->request->getPost('isEnd');




        // return ResponseFormatter::success($data, 'Debug data diterima');
        // panggil fungsi rosterProcess
        if ($clientName == "Promincon_Indonesia") {
            return $this->rosterProcessPromincon(
                $slipId,
                $clientName,
                $bioId,
                $badgeNo,
                $year,
                $month,
                $dataGroup,
                $isHealthBPJS,
                $isJHT,
                $isJP,
                $isJKKM,
                $isEnd,
                $this->request
            );
        }
    }

    public function rosterProcessPromincon(
        $slipId,
        $clientName,
        $biodataId,
        $badgeNo,
        $year,
        $month,
        $dept,
        $isHealthBPJS,
        $isJHT,
        $isJP,
        $isJKKM,
        $isEnd,
        $request
    ) {
        // Batch info
        $batch        = $request->getPost('batch') ?? 0;
        $limit        = 30;
        $offset       = $batch * $limit;
        $currentBatch = $batch + 1;
        $isBySlipId = $biodataId;

        $failMessage = "Process Failed";
        $salarySlip  = null;


        if (isset($biodataId) && $biodataId == 'BySlipId') {
            $salarySlip = $this->trSalarySlip->find($slipId);
            if (!$salarySlip) {
                return ResponseFormatter::error($failMessage, "Salary slip not found", 404);
            }
            $biodataId = $salarySlip['biodata_id'];
            if ($biodataId == '') {
                return ResponseFormatter::error($failMessage, "Roster error", 500);
            }
        }


        // === Format bulan ===
        if (isset($month)) {
            $month = intval($month);
            $month_day = $month - 1;
            if ($month_day == 0) $month_day = 12;
            $month_day = sprintf('%02d', $month_day);
            $month = sprintf('%02d', $month);
        }

        // === Process by NIE ===
        $processByNie = false;
        $bioId = $biodataId;
        if (!empty($biodataId) &&  $biodataId != 'BySlipId') {
            $salaryRow = $this->mtSalary->loadSalaryIdByBiodataId($biodataId);
            if ($salaryRow) {
                $biodataId = $salaryRow['biodata_id'];
                $bioId = $salaryRow['biodata_id'];
                $processByNie = true;
            }
        }

        // === Cek apakah periode sudah diproses ===
        $count = $this->mtProcessClossing->getCountByClientPeriod($clientName, $year, $month);
        if ($count >= 1) {
            return ResponseFormatter::error("Data with the same period has been processed before", null, 500);
        }



        // === Hitung jumlah hari dalam bulan ===
        $monthh = ($month == 1) ? 12 : $month - 1;
        $myDate = "{$year}-{$monthh}-01";
        $daysCountMonth = ConfigurationHelper::getDbLastDayOfMonth($myDate);

        // === Ambil data roster ===
        // $mTimesheet = new Roster();
        $employeeName = '';

        if ($biodataId == 0) {

            $dataExist = $this->mTimesheet->loadByClientPeriodExist($clientName, $year, $month, $dept);
            $data = $this->mTimesheet->loadByClientPeriod2($clientName, $year, $month, $dept, $limit, $offset);
        } else {
            $dataExist = $this->mTimesheet->loadByIdClientPeriod($biodataId, $clientName, $year, $month, $dept);
            $data = $dataExist;
            $employeeName = $data[0]->employee_name ?? '';
        }


        $totalData = count($dataExist);

        if (empty($dataExist)) {
            return ResponseFormatter::error("Not Found", "There is no roster data uploaded", 500);
        }

        // Jika batch lanjut tapi datanya habis
        if (empty($data) && $batch >= 0) {
            $payrollList = $this->getPayrollList($clientName, $year, $month, $dept);
            // var_dump($payrollList);
            return ResponseFormatter::success([
                'data'         => $payrollList,
                'nextBatch'    => 0,
                'totalData'    => $totalData,
                'currentBatch' => $currentBatch,
                'totalBatch'   => ceil($totalData / $limit),
            ], 'Roster Process Success Final', 200);
        }


        // === Variabel inisialisasi ===
        $rosterFormat = '';
        $dayIndex = 0;
        $offAttendCount = $otCount1 = $otCount2 = $otCount3 = $otCount4 = 0;
        $offCount = $emergencyCount = $sickCount = $unpaidPermitCount = $unpaidCount = $attendCount = $attendPHCount = 0;
        $phCountInMonth = $vacationCount = $standByCount = $paidPermitCount = $paidVacation = 0;
        $vacationDate = $unpaidDate = '';
        $otTmp = $ntDefault = $startOT = 0;

        $db = Database::connect();
        $db->transBegin();

        try {
            $tmp = '';
            $otInOffCount = '';
            if (!empty($data)) {

                foreach ($data as $row) {
                    $st = '';

                    $biodataId = $row->biodata_id;
                    $clientName = $row->client_name;
                    $rosterFormat = $row->roster_format;
                    $rosterBase = $row->roster_base;


                    $mSalarySlip = new SalarySlip();
                    $mOvertime = new M_tr_overtime();


                    $salaryRow = $this->mtSalary->loadSalaryIdByBiodataId($biodataId);
                    if (empty($salaryRow)) {
                        return ResponseFormatter::error('Not Found', 'Biodata Not Found', 404);
                    }


                    $biodataIdSalary = $salaryRow['biodata_id'];

                    if (empty($biodataIdSalary)) {
                        return ResponseFormatter::error('Not Found', 'Data Salary Not Found', 404);
                    }

                    if ($rosterFormat && ($rosterBase == '0' || $rosterBase == '')) {
                        $failMessage = "Badge ID {$biodataId}. Mohon cek kembali roster base dan format roster-nya.";
                        return ResponseFormatter::error('Failed to process', $failMessage, 500);
                    }

                    // Hapus data overtime & slip lama
                    $mSalarySlip->deleteByIdPeriod($biodataId, $clientName, $year, $month);
                    $mOvertime->deleteByIdPeriod($biodataId, $clientName, $year, $month);

                    // Tentukan OT Count berdasarkan base
                    if ($rosterBase == '131') {
                        $otInOffCount = 7;
                    } elseif ($rosterBase == '0' || $rosterBase == '52') {
                        $otInOffCount = 8;
                    } else {
                        $otInOffCount = 8;
                    }

                    /* OVERTIME VARIABLE  */
                    $otInOffCount1 = $otInOffCount;
                    $otInOffCount2 = $otInOffCount1 + 1;
                    $otInOffCount3 = $otInOffCount2 + 1;


                    $tmpRoster = $row->roster_format;
                    $tmpRosterBase = substr($row->roster_base, 0, 1);

                    if (empty($tmpRosterBase)) {
                        $rosterFormat = '';
                        $z = 0;
                        $dayIndex = 0;

                        $offAttendCount = 0;

                        $otCount1 = 0;
                        $otCount2 = 0;
                        $otCount3 = 0;
                        $otCount4 = 0;

                        $offCount = 0;
                        $emergencyCount = 0;
                        $sickCount = 0;
                        $unpaidPermitCount = 0;
                        $unpaidCount = 0;
                        $attendCount = 0;
                        $dayShiftCount = 0;
                        $nightShiftCount = 0;
                        $attendPHCount = 0; /* Attend in Public Holiday */
                        $phCountInMonth = 0; /* Public Holiday Count */
                        $vacationDate = '';
                        $vacationCount = 0;

                        $standByCount = 0;
                        $paidPermitCount = 0;
                        $paidVacation = 0;

                        $unpaidDate = '';
                        $otTmp = 0;
                        $wdCode = '';

                        $ntDefault = 0;
                        $startOT = 0;
                        $tesns = 0;
                        $tesro = 0;

                        $sixthDayTmp = 0;
                        $mOvertime->resetValues();
                        // $this->M_TrnAttendance->resetValues();
                        /* START SHIFT BREAKDOWN */
                        // for ($x=1; $x <= $rosterMasterCount; $x++)
                        // {
                        /* OVERTIME VARIABLE  */
                        $otInOffCount1 = $otInOffCount;
                        $otInOffCount2 = $otInOffCount1 + 1;
                        $otInOffCount3 = $otInOffCount2 + 1;

                        /* START GET WORK DAYS BY SHIFT */
                        for ($wd = 1; $wd <= $daysCountMonth; $wd++) {
                            $dayIndex++;
                            if ($dayIndex < 10) {
                                $rosterColumn = 'd0' . $dayIndex;
                                $ot01Column = 'setOt1D0' . $dayIndex;
                                $ot02Column = 'setOt2D0' . $dayIndex;
                                $ot03Column = 'setOt3D0' . $dayIndex;
                                $ot04Column = 'setOt4D0' . $dayIndex;
                                $isAlpaColumn = 'setIsAlpa0' . $dayIndex;
                                $isPermitColumn = 'setIsPermit0' . $dayIndex;
                                $isSickColumn = 'setIsSick0' . $dayIndex;
                                $isEmergencyColumn = 'setIsEmergency0' . $dayIndex;
                                $offColumn = 'setIsOff0' . $dayIndex;
                            } else {
                                $rosterColumn = 'd' . $dayIndex;
                                $ot01Column = 'setOt1D' . $dayIndex;
                                $ot02Column = 'setOt2D' . $dayIndex;
                                $ot03Column = 'setOt3D' . $dayIndex;
                                $ot04Column = 'setOt4D' . $dayIndex;
                                $isAlpaColumn = 'setIsAlpa' . $dayIndex;
                                $isPermitColumn = 'setIsPermit' . $dayIndex;
                                $isSickColumn = 'setIsSick' . $dayIndex;
                                $isEmergencyColumn = 'setIsEmergency' . $dayIndex;
                                $offColumn = 'setIsOff' . $dayIndex;
                            }
                            $otTmp = trim($row->$rosterColumn);

                            if (strtoupper($otTmp) == "NS") {
                                var_dump($otTmp);
                                exit();
                                $unpaidCount++;
                                if ($clientName == 'Promincon_Indonesia') {
                                    $unpaidDate .= '5';
                                }
                            }

                            /* PUBLIC HOLIDAY */
                            $otTmp = preg_replace('/[\r\n]+/', '', $otTmp);
                            // test($otTmp,1);
                            $shiftCodeTmp = substr($otTmp, 0, 2);
                            $shiftHoursTmp = substr($otTmp, 2, strlen($otTmp) - 2);


                            $st .= $shiftCodeTmp;
                            if ((strtoupper($shiftCodeTmp) == "PH")) {
                                if ((is_numeric($shiftHoursTmp)) && ($shiftHoursTmp > 0)) {
                                    $attendPHCount++;
                                }
                                $phCountInMonth++;
                            }

                            if ((strtoupper($shiftCodeTmp) == "RO")) {
                                if ((is_numeric($shiftHoursTmp)) && ($shiftHoursTmp > 0)) {
                                    $tesro++;
                                    $attendCount++;
                                    $offAttendCount++;
                                }

                                $offCount++;
                            }

                            if (($clientName == 'Promincon_Indonesia') && (strtoupper($shiftCodeTmp) == "RN" || strtoupper($shiftCodeTmp) == "PN")) {
                                if ((is_numeric($shiftHoursTmp)) && ($shiftHoursTmp > 0)) {
                                    $attendCount++;
                                    $offAttendCount++;
                                }
                                $offCount++;
                            }


                            /*if($shiftCodeTmp == 'SD')
                        {
                            echo $otTmp; exit(0);
                        }*/
                            /* EXCLUDE OF DATA IN ARRAY WILL BE TREATED AS  */
                            $arrTmpCode = array('PH', 'RO', 'NS', 'ID', 'AL', 'MD', 'RN', 'PN', 'PS');
                            if ($clientName == 'Promincon_Indonesia') {
                                $arrTmpCode = array('PH', 'RO', 'RN', 'PN', 'NS', 'SD', 'ID', 'AL', 'MD', 'PS', 'KR');
                            }
                            $arrOtTmp = array('A', 'U', 'S', 'V', 'E', 'STR', 'END', 'BP', 'PR');
                            if ((!in_array(strtoupper($otTmp), $arrOtTmp)) && (!in_array(strtoupper($shiftCodeTmp), $arrTmpCode)) && !is_numeric($otTmp)) {
                                $tmp .= $otTmp;
                                $unpaidCount++;
                                if ($clientName == 'Promincon_Indonesia') {
                                    $unpaidDate .= '5';
                                }
                            }

                            // test($otTmp,0);

                            /* RESET OVERTIME PROPERTIES IN WORKDAY */
                            $mOvertime->$ot01Column(0);
                            $mOvertime->$ot02Column(0);
                            $mOvertime->$ot03Column(0);
                            $mOvertime->$ot04Column(0);

                            /* GET ATTEND COUNT SHIFT DAY */

                            // test($shiftCodeTmp.' '.$shiftHoursTmp,0);
                            if (is_numeric($otTmp) && $otTmp > 0) {
                                $attendCount++;
                                $dayShiftCount++;

                                // if($clientName == 'AMNT_Sumbawa' || $clientName == 'Machmahon_Sumbawa') {
                                //     # 7 JAM PERTAMA NORMAL
                                //     # JAM KE 8 (LEMBUR 1 x 1.5)
                                //     # JAM KE >= 9 (LEMBUR 1 x 2)
                                //     $ntDefault = $otInOffCount1;
                                //     // $ntDefault = 7;
                                // }
                                if ($clientName == 'Promincon_Indonesia') {
                                    # 8 JAM PERTAMA NORMAL
                                    # JAM KE 9 (LEMBUR 1 x 1.5)
                                    # JAM KE >= 10 (LEMBUR 1 x 2)
                                    $ntDefault = $otInOffCount1;
                                    // $ntDefault = 8;
                                }
                                $startOT = $ntDefault + 1;

                                if (is_numeric($otTmp)) {
                                    if ($otTmp > $ntDefault) {
                                        if ($otTmp < $startOT) {
                                            $tVal = $startOT - $otTmp;
                                        } else {
                                            $tVal = 1;
                                        }
                                        $mOvertime->$ot01Column($tVal);
                                        $otCount1 += $tVal;
                                    }
                                    if ($otTmp > $startOT) {
                                        $tVal = $otTmp - $startOT;
                                        $otCount2 += $tVal;
                                        $mOvertime->$ot02Column($tVal);
                                    }
                                }
                            } elseif ((strtoupper($shiftCodeTmp) == "KR") && (is_numeric($shiftHoursTmp)) && ($shiftHoursTmp > 0)) {
                                $attendCount++;
                                $dayShiftCount++;

                                // if($clientName == 'Trakindo_Sumbawa') {
                                //     # 8 JAM PERTAMA NORMAL
                                //     # JAM KE 9 (LEMBUR 1 x 1.5)
                                //     # JAM KE >= 10 (LEMBUR 1 x 2)
                                //     $ntDefault = 8;
                                // }
                                // // if('Pontil_Sumbawa') {
                                // //     # 7 JAM PERTAMA NORMAL
                                // //     # JAM KE 8 (LEMBUR 1 x 1.5)
                                // //     # JAM KE >= 9 (LEMBUR 1 x 2)
                                // //     $ntDefault = 7;
                                // // }
                                // $startOT = $ntDefault + 1;

                                // if(is_numeric($shiftHoursTmp)){
                                //     if($shiftHoursTmp > $ntDefault){
                                //         if($shiftHoursTmp < $startOT){
                                //             $tVal = $startOT - $shiftHoursTmp;
                                //         } else {
                                //             $tVal = 1;
                                //         }
                                //         $mOvertime->$ot01Column($tVal);
                                //         $otCount1 += $tVal;
                                //     }
                                //     if($shiftHoursTmp > $startOT){
                                //         $tVal = $shiftHoursTmp - $startOT;
                                //         $otCount2 += $tVal;
                                //         $mOvertime->$ot02Column($tVal);
                                //     }
                                // }
                                // test('<---- sini 2',0);
                            }

                            /* GET STAND BY COUNT */ elseif (strtoupper($otTmp) == "STR" || strtoupper($otTmp) == "END" || strtoupper($otTmp) == "BP" || strtoupper($otTmp) == 'PR') {
                                $standByCount++;
                            }


                            /* START PUBLIC HOLIDAY  */ elseif ((strtoupper($shiftCodeTmp) == "PH" || strtoupper($shiftCodeTmp) == "RO") && (is_numeric($shiftHoursTmp)) && ($shiftHoursTmp > 0)) {
                                if ($clientName == 'Promincon_Indonesia' && $shiftCodeTmp == 'PH') {
                                    # 7 JAM PERTAMA x 2
                                    # JAM KE 8 (LEMBUR 1 x 3)
                                    # JAM KE >= 9 (LEMBUR 1 x 4)
                                    $otInOffCount1 = 7;
                                }
                                if ($clientName == 'Promincon_Indonesia' && strtoupper($shiftCodeTmp) == 'PH') {
                                    $otInOffCount1 = 7; // sama tp kalau ada perubahan tinggal ganti
                                }
                                if ($shiftHoursTmp >= $otInOffCount1) {
                                    $mOvertime->$ot02Column($otInOffCount1);
                                    $otCount2 += $otInOffCount1;
                                } else {
                                    $mOvertime->$ot02Column($shiftHoursTmp);
                                    $otCount2 += $shiftHoursTmp;
                                }

                                if ($shiftHoursTmp > $otInOffCount1) {
                                    if ($shiftHoursTmp < $otInOffCount2) {
                                        $tVal = $otInOffCount2 - $shiftHoursTmp;
                                    } else {
                                        $tVal = 1;
                                    }

                                    $mOvertime->$ot03Column($tVal);
                                    $otCount3 += $tVal;
                                }

                                if ($shiftHoursTmp > $otInOffCount2) {
                                    $tVal = $shiftHoursTmp - $otInOffCount2;
                                    $mOvertime->$ot04Column($tVal);
                                    $otCount4 += $tVal;
                                }
                            }

                            // echo "Hello"; exit(0);

                            /* START PUBLIC HOLIDAY  */ elseif ((strtoupper($shiftCodeTmp) == "NS") && (is_numeric($shiftHoursTmp)) && ($shiftHoursTmp > 0)) {
                                $tesns++;
                                $attendCount++;
                                $nightShiftCount++;
                                if ($clientName == 'Promincon_Indonesia') {
                                    # 8 JAM PERTAMA NORMAL
                                    # JAM KE 9 (LEMBUR 1 x 1.5)
                                    # JAM KE >= 10 (LEMBUR 1 x 2)
                                    $ntDefault = 8;
                                }
                                // if('Pontil_Sumbawa') {
                                //     # 7 JAM PERTAMA NORMAL
                                //     # JAM KE 8 (LEMBUR 1 x 1.5)
                                //     # JAM KE >= 9 (LEMBUR 1 x 2)
                                //     $ntDefault = 7;
                                // }
                                $startOT = $ntDefault + 1;

                                if (is_numeric($shiftHoursTmp)) {
                                    if ($shiftHoursTmp > $ntDefault) {
                                        if ($shiftHoursTmp < $startOT) {
                                            $tVal = $startOT - $shiftHoursTmp;
                                        } else {
                                            $tVal = 1;
                                        }
                                        $mOvertime->$ot01Column($tVal);
                                        $otCount1 += $tVal;
                                    }
                                    if ($shiftHoursTmp > $startOT) {
                                        $tVal = $shiftHoursTmp - $startOT;
                                        $otCount2 += $tVal;
                                        $mOvertime->$ot02Column($tVal);
                                    }
                                }
                            } elseif ((strtoupper($shiftCodeTmp) == "RN" || strtoupper($shiftCodeTmp) == "PN") && (is_numeric($shiftHoursTmp)) && ($shiftHoursTmp > 0)) {
                                // $attendCount++;
                                $nightShiftCount++;
                                if ($clientName == 'Promincon_Indonesia' && $shiftCodeTmp == 'PN') {
                                    # 7 JAM x 2
                                    # JAM KE 8 (LEMBUR 1 x 3)
                                    # JAM KE >= 9 (LEMBUR 1 x 4)
                                    $otInOffCount1 = 7;
                                }

                                if (is_numeric($shiftHoursTmp)) {
                                    if ($shiftHoursTmp >= $otInOffCount1) {
                                        $mOvertime->$ot02Column($otInOffCount1);
                                        $otCount2 += $otInOffCount1;
                                    } else {
                                        $mOvertime->$ot02Column($shiftHoursTmp);
                                        $otCount2 += $shiftHoursTmp;
                                    }

                                    if ($shiftHoursTmp > $otInOffCount1) {
                                        if ($shiftHoursTmp < $otInOffCount2) {
                                            $tVal = $otInOffCount2 - $shiftHoursTmp;
                                        } else {
                                            $tVal = 1;
                                        }

                                        $mOvertime->$ot03Column($tVal);
                                        $otCount3 += $tVal;
                                    }

                                    if ($shiftHoursTmp > $otInOffCount2) {
                                        $tVal = $shiftHoursTmp - $otInOffCount2;
                                        $mOvertime->$ot04Column($tVal);
                                        $otCount4 += $tVal;
                                    }
                                }
                            }


                            /* GET PAID PERMIT COUNT */ elseif (strtoupper($shiftCodeTmp) == "ID" || strtoupper($shiftCodeTmp) == "AL" || strtoupper($shiftCodeTmp) == "MD") {
                                $paidPermitCount++;
                            }

                            /* GET SICK PAID PERMIT COUNT */ elseif (strtoupper($shiftCodeTmp) == "PS") {
                                $paidPermitCount++;
                            }


                            /* GET PAID VACATION COUNT */ elseif (strtoupper($shiftCodeTmp) == "PV") {
                                $paidVacation++;
                            }

                            /* GET ALPA COUNT OFF DAY */ elseif (strtoupper($otTmp) == "A" || strtoupper($otTmp) == "U" || strtoupper($otTmp) == "S") {
                                $unpaidCount++;
                                if ($clientName == 'Promincon_Indonesia') {
                                    $unpaidDate .= '5';
                                }
                            }

                            /* GET SICK COUNT OFF DAY */ elseif (strtoupper($otTmp) == "S") {
                                // $sickCount++;
                                $unpaidCount++;

                                if ($clientName == 'Promincon_Indonesia') {
                                    $unpaidDate .= '5';
                                }
                            }


                            /* GET VACATION COUNT OFF DAY */ elseif (strtoupper($otTmp) == "V") {
                                $vacationDate .= $dayIndex . '.';
                                $vacationCount++;
                            }

                            /* GET ALPA COUNT */ elseif ($otTmp == '0' || $otTmp == '') {
                                $unpaidCount++;
                                if ($clientName == 'Promincon_Indonesia') {
                                    $unpaidDate .= '5';
                                }
                            } elseif (strtoupper($otTmp) == "E") {
                                $emergencyCount++;
                            }

                            // test('exitttt',1);
                        }
                    } else {
                        $strNumRoster = '';
                        $totalRoster = 0;
                        $tmpRosterLength = strlen($tmpRoster);
                        /* START GET DAYS TOTAL BY ROSTER */
                        for ($i = 0; $i < $tmpRosterLength; $i++) {
                            $tNumChar = substr($tmpRoster, $i, 1);
                            /* START MAKE SURE DATA IS NUMBER */
                            if (is_numeric($tNumChar)) {
                                $strNumRoster .= $tNumChar;
                                $totalRoster += $tNumChar;
                            }
                            /* END MAKE SURE DATA IS NUMBER */
                        }
                        /* END GET DAYS TOTAL BY ROSTER */

                        $rosterFormat = '';
                        $z = 0;
                        /* PENAMBAHAN ROSTER FORMAT 31-07-23 */
                        $rosterMasterCount = strlen($strNumRoster);
                        $tNumberRoster = $strNumRoster;
                        /* PENAMBAHAN ROSTER FORMAT 31-07-23 */
                        $dayIndex = 0;

                        $offAttendCount = 0;

                        $otCount1 = 0;
                        $otCount2 = 0;
                        $otCount3 = 0;
                        $otCount4 = 0;

                        $offCount = 0;
                        $emergencyCount = 0;
                        $sickCount = 0;
                        $unpaidPermitCount = 0;
                        $unpaidCount = 0;
                        $attendCount = 0;
                        $dayShiftCount = 0;
                        $nightShiftCount = 0;
                        $attendPHCount = 0; /* Attend in Public Holiday */
                        $phCountInMonth = 0; /* Public Holiday Count */
                        $vacationDate = '';
                        $vacationCount = 0;

                        $standByCount = 0;
                        $paidPermitCount = 0;
                        $paidVacation = 0;

                        $unpaidDate = '';
                        $otTmp = 0;
                        $wdCode = '';

                        $ntDefault = 0;
                        $startOT = 0;
                        $tesns = 0;
                        $tesro = 0;

                        $sixthDayTmp = 0;
                        $mOvertime->resetValues();
                        // $this->M_TrnAttendance->resetValues();
                        /* START SHIFT BREAKDOWN */
                        /* START SHIFT BREAKDOWN */
                        for ($x = 1; $x <= $rosterMasterCount; $x++) {
                            /* SHIFT GROUPING */
                            if (($x % 2) == 1) {
                                $rosterFormat = substr($tNumberRoster, $x - 1, 2);
                            }
                            $x++;

                            $workDay = substr($rosterFormat, 0, 1);
                            $offDay = substr($rosterFormat, 1, 1);

                            /* OVERTIME VARIABLE  */

                            /* START GET WORK DAYS BY SHIFT */
                            for ($wd = 1; $wd <= $workDay; $wd++) {
                                $otInOffCount1 = 8;
                                $otInOffCount2 = $otInOffCount1 + 1;
                                $dayIndex++;
                                if ($dayIndex < 10) {
                                    $rosterColumn = 'd0' . $dayIndex;
                                    $ot01Column = 'setOt1D0' . $dayIndex;
                                    $ot02Column = 'setOt2D0' . $dayIndex;
                                    $ot03Column = 'setOt3D0' . $dayIndex;
                                    $ot04Column = 'setOt4D0' . $dayIndex;
                                    $isAlpaColumn = 'setIsAlpa0' . $dayIndex;
                                    $isPermitColumn = 'setIsPermit0' . $dayIndex;
                                    $isSickColumn = 'setIsSick0' . $dayIndex;
                                    $isEmergencyColumn = 'setIsEmergency0' . $dayIndex;
                                    $offColumn = 'setIsOff0' . $dayIndex;
                                } else {
                                    $rosterColumn = 'd' . $dayIndex;
                                    $ot01Column = 'setOt1D' . $dayIndex;
                                    $ot02Column = 'setOt2D' . $dayIndex;
                                    $ot03Column = 'setOt3D' . $dayIndex;
                                    $ot04Column = 'setOt4D' . $dayIndex;
                                    $isAlpaColumn = 'setIsAlpa' . $dayIndex;
                                    $isPermitColumn = 'setIsPermit' . $dayIndex;
                                    $isSickColumn = 'setIsSick' . $dayIndex;
                                    $isEmergencyColumn = 'setIsEmergency' . $dayIndex;
                                    $offColumn = 'setIsOff' . $dayIndex;
                                }
                                $otTmp = trim($row->$rosterColumn);

                                if ($otTmp === 'NS') {
                                    $tmp .= $otTmp;
                                    $unpaidCount++;
                                    if ($clientName == 'Promincon_Indonesia') {
                                        $unpaidDate .= '5';
                                    }
                                }

                                /* PUBLIC HOLIDAY */
                                $otTmp = preg_replace('/[\r\n]+/', '', $otTmp);
                                // test($otTmp,1);
                                $shiftCodeTmp = substr($otTmp, 0, 2);
                                $shiftHoursTmp = substr($otTmp, 2, strlen($otTmp) - 2);


                                $st .= $shiftCodeTmp;
                                if ((strtoupper($shiftCodeTmp) == "PH")) {
                                    if ((is_numeric($shiftHoursTmp)) && ($shiftHoursTmp > 0)) {
                                        $attendCount++;
                                        $attendPHCount++;
                                    }
                                    $phCountInMonth++;
                                }

                                if ((strtoupper($shiftCodeTmp) == "RO")) {
                                    if ((is_numeric($shiftHoursTmp)) && ($shiftHoursTmp > 0)) {
                                        $tesro++;
                                        $attendCount++;
                                        $offAttendCount++;
                                    }
                                    $offCount++;
                                }
                                if (($clientName == 'Promincon_Indonesia') && (strtoupper($shiftCodeTmp) == "RN" || strtoupper($shiftCodeTmp) == "PN")) {
                                    if ((is_numeric($shiftHoursTmp)) && ($shiftHoursTmp > 0)) {
                                        $attendCount++;
                                        $offAttendCount++;
                                    }
                                    $offCount++;
                                }

                                /* EXCLUDE OF DATA IN ARRAY WILL BE TREATED AS  */
                                $arrTmpCode = array('PH', 'RO', 'NS', 'ID', 'AL', 'MD', 'RN', 'PN', 'PS');
                                if ($clientName == 'Promincon_Indonesia') {
                                    $arrTmpCode = array('PH', 'RO', 'RN', 'PN', 'NS', 'SD', 'ID', 'AL', 'MD', 'PS', 'KR');
                                }
                                $arrOtTmp = array('A', 'U', 'S', 'I', 'V', 'E', 'STR', 'END', 'BP', 'PR');
                                if ((!in_array(strtoupper($otTmp), $arrOtTmp)) && (!in_array(strtoupper($shiftCodeTmp), $arrTmpCode)) && !is_numeric($otTmp)) {
                                    $tmp .= $otTmp;
                                    $unpaidCount++;
                                    if ($clientName == 'Promincon_Indonesia') {
                                        $unpaidDate .= '5';
                                    }
                                }

                                // test($otTmp,0);

                                /* RESET OVERTIME PROPERTIES IN WORKDAY */
                                $mOvertime->$ot01Column(0);
                                $mOvertime->$ot02Column(0);
                                $mOvertime->$ot03Column(0);
                                $mOvertime->$ot04Column(0);

                                /* GET ATTEND COUNT SHIFT DAY */

                                // test($shiftCodeTmp.' '.$shiftHoursTmp,0);
                                if (is_numeric($otTmp) && $otTmp > 0) {
                                    $attendCount++;
                                    $dayShiftCount++;

                                    // if($clientName == 'AMNT_Sumbawa' || $clientName == 'Machmahon_Sumbawa') {
                                    //     # 7 JAM PERTAMA NORMAL
                                    //     # JAM KE 8 (LEMBUR 1 x 1.5)
                                    //     # JAM KE >= 9 (LEMBUR 1 x 2)
                                    //     $ntDefault = $otInOffCount1;
                                    //     // $ntDefault = 7;
                                    // }
                                    if ($clientName == 'Promincon_Indonesia') {
                                        # 8 JAM PERTAMA NORMAL
                                        # JAM KE 9 (LEMBUR 1 x 1.5)
                                        # JAM KE >= 10 (LEMBUR 1 x 2)
                                        $ntDefault = $otInOffCount1;
                                        // $ntDefault = 8;
                                    }
                                    $startOT = $ntDefault + 1;

                                    if (is_numeric($otTmp)) {
                                        if ($otTmp > $ntDefault) {
                                            if ($otTmp < $startOT) {
                                                $tVal = $startOT - $otTmp;
                                            } else {
                                                $tVal = 1;
                                            }
                                            $mOvertime->$ot01Column($tVal);
                                            $otCount1 += $tVal;
                                        }
                                        if ($otTmp > $startOT) {
                                            $tVal = $otTmp - $startOT;
                                            $otCount2 += $tVal;
                                            $mOvertime->$ot02Column($tVal);
                                        }
                                    }
                                } elseif ((strtoupper($shiftCodeTmp) == "KR") && (is_numeric($shiftHoursTmp)) && ($shiftHoursTmp > 0)) {
                                    $attendCount++;
                                    $dayShiftCount++;

                                    // if($clientName == 'Promincon_Indonesia') {
                                    //     # 8 JAM PERTAMA NORMAL
                                    //     # JAM KE 9 (LEMBUR 1 x 1.5)
                                    //     # JAM KE >= 10 (LEMBUR 1 x 2)
                                    //     $ntDefault = 8;
                                    // }
                                    // // if('Pontil_Sumbawa') {
                                    // //     # 7 JAM PERTAMA NORMAL
                                    // //     # JAM KE 8 (LEMBUR 1 x 1.5)
                                    // //     # JAM KE >= 9 (LEMBUR 1 x 2)
                                    // //     $ntDefault = 7;
                                    // // }
                                    // $startOT = $ntDefault + 1;

                                    // if(is_numeric($shiftHoursTmp)){
                                    //     if($shiftHoursTmp > $ntDefault){
                                    //         if($shiftHoursTmp < $startOT){
                                    //             $tVal = $startOT - $shiftHoursTmp;
                                    //         } else {
                                    //             $tVal = 1;
                                    //         }
                                    //         $mOvertime->$ot01Column($tVal);
                                    //         $otCount1 += $tVal;
                                    //     }
                                    //     if($shiftHoursTmp > $startOT){
                                    //         $tVal = $shiftHoursTmp - $startOT;
                                    //         $otCount2 += $tVal;
                                    //         $mOvertime->$ot02Column($tVal);
                                    //     }

                                    // }

                                    // test('<---- sini 2',0);
                                }

                                /* GET STAND BY COUNT */ elseif (strtoupper($otTmp) == "STR" || strtoupper($otTmp) == "END" || strtoupper($otTmp) == "BP" || strtoupper($otTmp) == 'PR') {
                                    $standByCount++;
                                }


                                /* START PUBLIC HOLIDAY  */ elseif ((strtoupper($shiftCodeTmp) == "PH" || strtoupper($shiftCodeTmp) == "RO") && (is_numeric($shiftHoursTmp)) && ($shiftHoursTmp > 0)) {
                                    if ($clientName == 'Promincon_Indonesia' && strtoupper($shiftCodeTmp) == 'PH') {
                                        # 7 JAM x 2
                                        # JAM KE 8 (LEMBUR 1 x 3)
                                        # JAM KE >= 9 (LEMBUR 1 x 4)
                                        $otInOffCount1 = 7;
                                        $otInOffCount2 = $otInOffCount1 + 1;
                                    }
                                    if ($shiftHoursTmp >= $otInOffCount1) {
                                        $mOvertime->$ot02Column($otInOffCount1);
                                        $otCount2 += $otInOffCount1;
                                    } else {
                                        $mOvertime->$ot02Column($shiftHoursTmp);
                                        $otCount2 += $shiftHoursTmp;
                                    }

                                    if ($shiftHoursTmp > $otInOffCount1) {
                                        if ($shiftHoursTmp < $otInOffCount2) {
                                            $tVal = $otInOffCount2 - $shiftHoursTmp;
                                        } else {
                                            $tVal = 1;
                                        }

                                        $mOvertime->$ot03Column($tVal);
                                        $otCount3 += $tVal;
                                    }

                                    if ($shiftHoursTmp > $otInOffCount2) {
                                        $tVal = $shiftHoursTmp - $otInOffCount2;
                                        $mOvertime->$ot04Column($tVal);
                                        $otCount4 += $tVal;
                                    }
                                }

                                // echo "Hello"; exit(0);

                                /* START PUBLIC HOLIDAY  */ elseif ((strtoupper($shiftCodeTmp) == "NS") && (is_numeric($shiftHoursTmp)) && ($shiftHoursTmp > 0)) {
                                    $tesns++;
                                    $attendCount++;
                                    $nightShiftCount++;
                                    if ($clientName == 'Promincon_Indonesia') {
                                        # 8 JAM PERTAMA NORMAL
                                        # JAM KE 9 (LEMBUR 1 x 1.5)
                                        # JAM KE >= 10 (LEMBUR 1 x 2)
                                        $ntDefault = 8;
                                    }
                                    // if('Pontil_Sumbawa') {
                                    //     # 7 JAM PERTAMA NORMAL
                                    //     # JAM KE 8 (LEMBUR 1 x 1.5)
                                    //     # JAM KE >= 9 (LEMBUR 1 x 2)
                                    //     $ntDefault = 7;
                                    // }
                                    $startOT = $ntDefault + 1;

                                    if (is_numeric($shiftHoursTmp)) {
                                        if ($shiftHoursTmp > $ntDefault) {
                                            if ($shiftHoursTmp < $startOT) {
                                                $tVal = $startOT - $shiftHoursTmp;
                                            } else {
                                                $tVal = 1;
                                            }
                                            $mOvertime->$ot01Column($tVal);
                                            $otCount1 += $tVal;
                                        }
                                        if ($shiftHoursTmp > $startOT) {
                                            $tVal = $shiftHoursTmp - $startOT;
                                            $otCount2 += $tVal;
                                            $mOvertime->$ot02Column($tVal);
                                        }
                                    }
                                } elseif ((strtoupper($shiftCodeTmp) == "RN" || strtoupper($shiftCodeTmp) == "PN") && (is_numeric($shiftHoursTmp)) && ($shiftHoursTmp > 0)) {
                                    // $attendCount++;
                                    $nightShiftCount++;
                                    if ($clientName == 'Promincon_Indonesia' && strtoupper($shiftCodeTmp) == "PN") {
                                        # 8 JAM PERTAMA NORMAL
                                        # JAM KE 9 (LEMBUR 1 x 1.5)
                                        # JAM KE >= 10 (LEMBUR 1 x 2)
                                        $otInOffCount1 = 7;
                                        $otInOffCount2 = $otInOffCount1 + 1;
                                    }

                                    if (is_numeric($shiftHoursTmp)) {
                                        if ($shiftHoursTmp >= $otInOffCount1) {
                                            $mOvertime->$ot02Column($otInOffCount1);
                                            $otCount2 += $otInOffCount1;
                                        } else {
                                            $mOvertime->$ot02Column($shiftHoursTmp);
                                            $otCount2 += $shiftHoursTmp;
                                        }

                                        if ($shiftHoursTmp > $otInOffCount1) {
                                            if ($shiftHoursTmp < $otInOffCount2) {
                                                $tVal = $otInOffCount2 - $shiftHoursTmp;
                                            } else {
                                                $tVal = 1;
                                            }

                                            $mOvertime->$ot03Column($tVal);
                                            $otCount3 += $tVal;
                                        }

                                        if ($shiftHoursTmp > $otInOffCount2) {
                                            $tVal = $shiftHoursTmp - $otInOffCount2;
                                            $mOvertime->$ot04Column($tVal);
                                            $otCount4 += $tVal;
                                        }
                                    }
                                }


                                /* GET PAID PERMIT COUNT */ elseif (strtoupper($shiftCodeTmp) == "ID" || strtoupper($shiftCodeTmp) == "AL" || strtoupper($shiftCodeTmp) == "MD") {
                                    $paidPermitCount++;
                                }

                                /* GET SICK PAID PERMIT COUNT */ elseif (strtoupper($shiftCodeTmp) == "PS") {
                                    $paidPermitCount++;
                                }


                                /* GET PAID VACATION COUNT */ elseif (strtoupper($shiftCodeTmp) == "PV") {
                                    $paidVacation++;
                                }

                                /* GET ALPA COUNT OFF DAY */ elseif (strtoupper($otTmp) == "A" || strtoupper($otTmp) == "I" || strtoupper($otTmp) == "U" || strtoupper($otTmp) == "S") {
                                    $unpaidCount++;
                                    if ($clientName == 'Promincon_Indonesia') {
                                        $unpaidDate .= '5';
                                    }
                                }

                                /* GET SICK COUNT OFF DAY */ elseif (strtoupper($otTmp) == "S") {
                                    // $sickCount++;
                                    $unpaidCount++;
                                    if ($clientName == 'Promincon_Indonesia') {
                                        $unpaidDate .= '5';
                                    }
                                }


                                /* GET VACATION COUNT OFF DAY */ elseif (strtoupper($otTmp) == "V") {
                                    $vacationDate .= $dayIndex . '.';
                                    $vacationCount++;
                                }

                                /* GET ALPA COUNT */ elseif ($otTmp == '0' || $otTmp == '') {
                                    $unpaidCount++;
                                    if ($clientName == 'Promincon_Indonesia') {
                                        $unpaidDate .= '5';
                                    }
                                } elseif (strtoupper($otTmp) == "E") {
                                    $emergencyCount++;
                                }
                            }
                            /* END GET WORK DAYS BY SHIFT */
                            /* START GET OFF DAYS BY SHIFT */
                            for ($od = 1; $od <= $offDay; $od++) {
                                $otInOffCount1 = 8;
                                $otInOffCount2 = $otInOffCount1 + 1;
                                $dayIndex++;
                                $otCount1 = 0;
                                if ($dayIndex < 10) {
                                    $rosterColumn = 'd0' . $dayIndex;
                                    $ot01Column = 'setOt1D0' . $dayIndex;
                                    $ot02Column = 'setOt2D0' . $dayIndex;
                                    $ot03Column = 'setOt3D0' . $dayIndex;
                                    $ot04Column = 'setOt4D0' . $dayIndex;
                                } else {
                                    $rosterColumn = 'd' . $dayIndex;
                                    $ot01Column = 'setOt1D' . $dayIndex;
                                    $ot02Column = 'setOt2D' . $dayIndex;
                                    $ot03Column = 'setOt3D' . $dayIndex;
                                    $ot04Column = 'setOt4D' . $dayIndex;
                                }
                                $otTmp = $row->$rosterColumn;

                                /* RESET OVERTIME PROPERTIES IN WORKDAY */
                                $mOvertime->$ot01Column(0);
                                $mOvertime->$ot02Column(0);
                                $mOvertime->$ot03Column(0);
                                $mOvertime->$ot04Column(0);
                                /* GET ATTEND COUNT OFF DAY */

                                $phCodeTmp = substr($otTmp, 0, 2);
                                $phHoursTmp = substr($otTmp, 2, strlen($otTmp) - 2);
                                $st .= $phCodeTmp;
                                if ((strtoupper($phCodeTmp) == "PH") && (is_numeric($phHoursTmp)) && ($phHoursTmp > 0)) {
                                    $otTmp = $phHoursTmp;
                                }

                                /* GET VACATION COUNT OFF DAY */
                                if (strtoupper($phCodeTmp) == "SB") {
                                    $standByCount++;
                                } elseif ((strtoupper($otTmp) == "STR" || strtoupper($otTmp) == "END" || strtoupper($otTmp) == "BP" || strtoupper($otTmp) == 'PR')) {
                                    $standByCount++;
                                }

                                // test($otTmp,1);
                                $shiftCodeTmp = substr($otTmp, 0, 2);
                                $shiftHoursTmp = substr($otTmp, 2, strlen($otTmp) - 2);
                                if ((strtoupper($shiftCodeTmp) == "NS" || strtoupper($shiftCodeTmp) == "RN" || strtoupper($shiftCodeTmp) == "PN") && (is_numeric($shiftHoursTmp)) && ($shiftHoursTmp > 0)) {
                                    // $attendCount++;
                                    $tesns++;
                                    $attendCount++;
                                    $nightShiftCount++;
                                    if ($clientName == 'Promincon_Indonesia' && strtoupper($shiftCodeTmp) == 'PN') {
                                        # 7 JAM x 2
                                        # JAM KE 8 (LEMBUR 1 x 3)
                                        # JAM KE >= 9 (LEMBUR 1 x 4)
                                        $otInOffCount1 = 7;
                                        $otInOffCount2 = $otInOffCount1 + 1;
                                    }
                                    if (is_numeric($shiftHoursTmp)) {
                                        if ($shiftHoursTmp >= $otInOffCount1) {
                                            $mOvertime->$ot02Column($otInOffCount1);
                                            $otCount2 += $otInOffCount1;
                                        } else {
                                            $mOvertime->$ot02Column($shiftHoursTmp);
                                            $otCount2 += $shiftHoursTmp;
                                        }

                                        if ($shiftHoursTmp > $otInOffCount1) {
                                            if ($shiftHoursTmp < $otInOffCount2) {
                                                $tVal = $otInOffCount2 - $shiftHoursTmp;
                                            } else {
                                                $tVal = 1;
                                            }

                                            $mOvertime->$ot03Column($tVal);
                                            $otCount3 += $tVal;
                                        }

                                        if ($shiftHoursTmp > $otInOffCount2) {
                                            $tVal = $shiftHoursTmp - $otInOffCount2;
                                            $mOvertime->$ot04Column($tVal);
                                            $otCount4 += $tVal;
                                        }
                                    }
                                }
                                /* GET PAID PERMIT COUNT */ elseif (strtoupper($shiftCodeTmp) == "ID" || strtoupper($shiftCodeTmp) == "AL" || strtoupper($shiftCodeTmp) == "MD") {
                                    $paidPermitCount++;
                                }
                                /* GET SICK PAID PERMIT COUNT */ elseif (strtoupper($shiftCodeTmp) == "PS") {
                                    $paidPermitCount++;
                                }
                                /* GET PAID VACATION COUNT */ elseif (strtoupper($shiftCodeTmp) == "PV") {
                                    $paidVacation++;
                                }

                                if (is_numeric($otTmp) && $otTmp > 0) {
                                    $attendCount++;
                                    $offAttendCount++;
                                }

                                /* HARI LIBUR */
                                # LEMBUR II (7 JAM PERTAMA X 2)
                                # LEMBUR III (JAM KE 8 X 3)
                                # LEMBUR IV (JAM >= 9 X 4)
                                if (is_numeric($otTmp)) {
                                    if ($otTmp <= 0) {
                                        $offCount++;
                                    }
                                } else {
                                    $offCount++;
                                }
                                if (is_numeric($otTmp)) {
                                    if ($clientName == 'Promincon_Indonesia' && strtoupper($phCodeTmp) == 'PH') {
                                        # 7 JAM x 2
                                        # JAM KE 8 (LEMBUR 1 x 3)
                                        # JAM KE >= 9 (LEMBUR 1 x 4)
                                        $otInOffCount1 = 7;
                                        $otInOffCount2 = $otInOffCount1 + 1;
                                    }
                                    if ($otTmp >= $otInOffCount1) {
                                        $mOvertime->$ot02Column($otInOffCount1);
                                        $otCount2 += $otInOffCount1;
                                    } else {
                                        $mOvertime->$ot02Column($otTmp);
                                        $otCount2 += $otTmp;
                                    }

                                    if ($otTmp > $otInOffCount1) {
                                        if ($otTmp < $otInOffCount2) {
                                            $tVal = $otInOffCount2 - $otTmp;
                                        } else {
                                            $tVal = 1;
                                        }

                                        $otCount3 += $tVal;
                                        $mOvertime->$ot03Column($tVal);
                                    }

                                    if ($otTmp > $otInOffCount2) {
                                        $tVal = $otTmp - $otInOffCount2;
                                        $mOvertime->$ot04Column($tVal);
                                        $otCount4 += $tVal;
                                    }
                                }

                                /* GET ALPA COUNT OFF DAY */ elseif (strtoupper($otTmp) == "A" || strtoupper($otTmp) == "U" || strtoupper($otTmp) == "S" || strtoupper($otTmp) == "I") {
                                    $unpaidCount++;
                                    if ($clientName == 'Promincon_Indonesia') {
                                        $unpaidDate .= '5';
                                    }
                                }

                                /* GET SICK COUNT OFF DAY */ elseif (strtoupper($otTmp) == "S") {
                                    // $sickCount++;
                                    $unpaidCount++;
                                    if ($clientName == 'Promincon_Indonesia') {
                                        $unpaidDate .= '5';
                                    }
                                }
                            }
                            /* END GET OFF DAYS BY SHIFT */
                        }
                        // test($row['roster_id'],0);
                    }
                    // test($standByCount.' '.$row['bio_rec_id'],1);
                    /* END GET WORK DAYS BY SHIFT */

                    // }
                    /* for ($x=1; $x <= $rosterMasterCount; $x++) */
                    /* END SHIFT BREAKDOWN */
                    // test($attendCount.' '.$offAttendCount.' '.$attendPHCount.' '.$tesns.' '.$tesro,1);
                    /* START SET DATA INSERT TRN_OVERTIME */
                    // $otId = $mOvertime->generateId('overtime_id');
                    // dd($otId);

                    $otData = $mOvertime->generateId('overtime_id');
                    $otId = $otData['doc_id'];
                    // echo $otId;
                    $mOvertime->setOvertimeId($otId);
                    $mOvertime->setBioRecId($row->biodata_id);
                    $mOvertime->setClientName($clientName);
                    $mOvertime->setRosterId($row->ts_id);
                    $mOvertime->setYearPeriod($row->year_process);
                    $mOvertime->setMonthPeriod($row->month_process);

                    /* Start Dynamic Data*/

                    $mOvertime->setOffTotal($offCount);
                    $mOvertime->setEmergencyTotal($emergencyCount);
                    $mOvertime->setSickTotal($sickCount);
                    $mOvertime->setPermitTotal($unpaidPermitCount);
                    $mOvertime->setAlpaTotal($unpaidCount);
                    $mOvertime->setAttendTotal($attendCount);

                    $mOvertime->setNightShiftCount($nightShiftCount);
                    $mOvertime->setDayShiftCount($dayShiftCount);

                    $mOvertime->setUnpaidDays($unpaidDate);
                    $mOvertime->setVacationDays($vacationDate);
                    $mOvertime->setVacationTotal($vacationCount);
                    $mOvertime->setAttendInOff($offAttendCount);
                    $mOvertime->setPhTotal($phCountInMonth);
                    $mOvertime->setInPhTotal($attendPHCount);
                    $mOvertime->setStandbyTotal($standByCount);
                    $mOvertime->setPaidVacationTotal($paidVacation);
                    $mOvertime->setPaidPermitTotal($paidPermitCount);
                    /* End Dynamic Data*/
                    $mOvertime->setIsActive($row->is_active);
                    $mOvertime->setPicProcess(session()->get('uId'));
                    $currDateTm = date("Y-m-d H:i:s");
                    $mOvertime->setProcessTime($currDateTm);
                    $mOvertime->ins();

                    $this->payrollProcess(
                        $biodataId,
                        $clientName,
                        $dept,
                        $year,
                        $month,
                        $isHealthBPJS,
                        $isJHT,
                        $isJP,
                        $isJKKM,
                        $isEnd
                    );
                }
            }

            if ($bioId == "" || $bioId == 0) {

                $taxData = $this->taxProcess(0, $clientName, $year, $month, $dept, $isEnd, $limit, $offset);
            } else {
                $taxData = $this->taxProcess($biodataId, $clientName, $year, $month, $dept, $isEnd, $limit, $offset);
            }
            // Commit jika sukses
            $db->transCommit();
            // Jika batch lanjut tapi datanya habis
            if ($isBySlipId == 'BySlipId' || $bioId != null) {
                $payrollList = $this->getPayrollList($clientName, $year, $month, $dept);

                return ResponseFormatter::success([
                    'data'         => $payrollList,
                    'nextBatch'    => 0,
                    'totalData'    => $totalData,
                    'currentBatch' => $currentBatch,
                    'totalBatch'   => ceil($totalData / $limit),
                ], 'Roster Process Success Final', 200);
            }

            return ResponseFormatter::success([
                'message'       => 'Roster processed successfully',
                'nextBatch'     => $currentBatch,
                'totalData'     => $totalData,
                'currentBatch'  => $currentBatch,
                'totalBatch'    => ceil($totalData / $limit),
            ], 'Roster Process Success', 200);
        } catch (\Throwable $e) {
            $db->transRollback();
            // return ResponseFormatter::error('Transaction failed', $e->getMessage(), 500);
            return ResponseFormatter::error('Transaction failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ], 500);
        }
    }


    // public function payrollProcess()
    public function payrollProcess(
        $biodataId,
        $clientName,
        $department,
        $yearPeriod,
        $monthPeriod,
        $isHealthBPJS,
        $isJHT,
        $isJP,
        $isJKKM,
        $isEnd
    ) {
        // $tst = '';
        $userId = session()->get('user_id');
        $year = '';
        $month = '';
        // $dept = $this->security->xss_clean($department);
        $overtimeDatas = $this->trSalarySlip->fetchOvertimeData($biodataId, $clientName, $department, $yearPeriod, $monthPeriod);
        /* START GET CONFIG FROM mst_payroll_config TABLE */
        /* START LOOP DATABASE */
        $myData = array();



        $mSalarySlip = new SalarySlip();

        $payrollConfig = $this->mtPayrollConfig->loadByClient($clientName);


        $myData = array();
        foreach ($overtimeDatas as $key => $row) {

            /* Set Default Variable Values */
            $salaryHourly = 0; // gaji harian
            $otTotal01 = 0; // ot total 1
            $otTotal02 = 0; // ot total 2
            $otTotal03 = 0; // ot total 3
            $otTotal04 = 0; // ot total 4

            $trvValue = 0; //
            $allowanceEconomy = 0;
            $incentiveBonus = 0;
            $shiftBonus = 0;
            $remoteAllowance = 0;
            $positionalAllowance = 0;
            $allOverTimeTotal = 0;
            $otBonus = 0;
            $transHousingAllowance = 0;

            $nonTaxAllowance = 0;
            $attendTotal = 0;
            $attendInPH = 0;
            $phTotal = 0;
            $attendInOff = 0;
            $sickTotal = 0;
            $emergencyTotal = 0;
            $offTotal = 0;
            $alpaTotal = 0;
            $vacationTotal = 0;

            $mSalarySlip->resetValues();

            /* Start Data Values */
            $biodataId = $row['bio_rec_id'];

            $rosterId = $row['roster_id'];
            $rosterBase = $row['roster_base'];
            /* BASIC SALARY */
            $basicSalary = $row['basic_salary'];
            $dailyBasic = $row['daily_basic'];
            $fullName = $row['full_name'];
            $dept = $row['dept'];

            $maritalStatus = $row['marital_status'];
            $phTotal = $row['ph_total'];
            $attendInPH = $row['in_ph_total'];
            $attendInOff = $row['attend_in_off'];
            $attendTotal = $row['attend_total'] + $phTotal;
            $permitTotal = $row['permit_total'];
            $offTotal = $row['off_total'];
            $sickTotal = $row['sick_total'];
            $alpaTotal = $row['alpa_total'];
            $vacationTotal = $row['vacation_total'];
            $emergencyTotal = $row['emergency_total'];
            $unpaidDays = $row['unpaid_days'];
            $position = $row['position'];
            /* Add By Maurice @28-12-2017 */
            $standByCount = $row['standby_total'];
            $paidPermitCount = $row['paid_permit_total'];
            $paidVacation = $row['paid_vacation_total'];
            $attendTotal = $row['attend_total'];
            /* End Data Values */

            $shiftAttend = $row['attend_total'] - $row['attend_in_off'];
            $offAttend = $row['attend_in_off'];
            $phAttend = $row['in_ph_total'];


            if ($isHealthBPJS == 1) {
                $isHealthBPJS = 1;
            } else if ($isHealthBPJS == 0) {
                $isHealthBPJS = 0;
            }
            if ($isJHT == 1) {
                $isJHT = 1;
            } else if ($isJHT == 0) {
                $isJHT = 0;
            }
            if ($isJP == 1) {
                $isJP = 1;
            } else if ($isJP == 0) {
                $isJP = 0;
            }
            if ($isJKKM == 1) {
                $isJKKM = 1;
            } else if ($isJKKM == 0) {
                $isJKKM = 0;
            }
            if ($isEnd == 1) {
                $isEnd = 1;
            }

            $govDetails =  $this->govController->calculateGovernmentRegulation(
                $clientName,
                $basicSalary,
                $maritalStatus,
                $isHealthBPJS,
                $isJHT,
                $isJP,
                $isJKKM
            );


            // Ambil bagian 'data' dari hasil responsenya
            // $govDetails = $govData['data'];
            $nonTaxAllowance = $govDetails['nonTaxAllowance'];
            $totalPTKP = $govDetails['totalPTKP'];
            $healthBpjs = $govDetails['healthBpjs'];
            $jkkJkm = $govDetails['jkkJkm'];
            $jht = $govDetails['jht'];
            $empJht = $govDetails['empJht'];
            $jp = $govDetails['jp'];
            $empJp = $govDetails['empJp'];
            $empHealthBpjs = $govDetails['empHealthBpjs'];
            // var_dump($payrollConfig);
            // exit();


            /* Start Get Config Values */
            $salaryDividerConfig = $payrollConfig['salary_divider'];
            $otMultiplierConfig01 = $payrollConfig['ot_01_multiplier'];
            $otMultiplierConfig02 = $payrollConfig['ot_02_multiplier'];
            $otMultiplierConfig03 = $payrollConfig['ot_03_multiplier'];
            $otMultiplierConfig04 = $payrollConfig['ot_04_multiplier'];
            $isProrateConfig = $payrollConfig['is_prorate'];
            $umk = $payrollConfig['umk'];

            /* START OVER TIME TOTAL */
            $otCount1 = 0;
            $otCount2 = 0;
            $otCount3 = 0;
            $otCount4 = 0;
            $timeTotal = 0;
            $ntTotal = 0;

            /* Only More Than 12 Hours Will Counted  */
            $trvDayCount = 0;

            for ($i = 1; $i <= 31; $i++) {
                # code...
                $idx = '';
                if ($i < 10) {
                    $idx = '0' . $i;
                } else {
                    $idx = $i;
                }
                $otCount1 += $row['ot1_d' . $idx];
                $otCount2 += $row['ot2_d' . $idx];
                $otCount3 += $row['ot3_d' . $idx];
                $otCount4 += $row['ot4_d' . $idx];

                /* COUNTING OF WORKED HOUR */
                $tmpTime = trim($row['d' . $idx]);


                if ($clientName == 'Promincon_Indonesia') {
                    if (is_numeric($tmpTime)) {
                        $timeTotal += $row['d' . $idx];
                    } else {
                        $tmpCode = substr($tmpTime, 0, 2);
                        if ($tmpCode == "PH" || $tmpCode == "RO" || $tmpCode == "PN" || $tmpCode == "RN" || $tmpCode == "NS") {
                            $tmpHour = substr($tmpTime, 2, strlen($tmpTime) - 2);
                            $timeTotal += floatval($tmpHour);
                        }
                    }
                }
            }

            $ntTotal = $timeTotal - $otCount1 - $otCount2 - $otCount3 - $otCount4;
            /* OVER TIME HOURLY */
            $salaryHourly = $basicSalary / $salaryDividerConfig;
            $otTotal01 = $salaryHourly * $otMultiplierConfig01 * $otCount1; /* Rate Per Jam x Nilai OT 1 x Total Jam Lembur */
            $salaryHourly = $basicSalary / $salaryDividerConfig;
            $otTotal02 = $salaryHourly * $otMultiplierConfig02 * $otCount2; /* Rate Per Jam x Nilai OT 2 x Total Jam Lembur */
            $otTotal03 = $salaryHourly * $otMultiplierConfig03 * $otCount3; /* Rate Per Jam x Nilai OT 3 x Total Jam Lembur */
            $otTotal04 = $salaryHourly * $otMultiplierConfig04 * $otCount4; /* Rate Per Jam x Nilai OT 4 x Total Jam Lembur */

            /* START ALPA TOTAL */

            $totalAlpa = 0;
            $unpaidLength = strlen($unpaidDays);
            for ($ud = 0; $ud < $unpaidLength; $ud++) {
                $unpaidChar = substr($unpaidDays, $ud, 1);

                $month_day         = $monthPeriod - 1;

                if ($month_day == 0) {
                    $month_day     = 12;
                }
                if ($month_day >= 10) {
                    $month_day = $month_day;
                } else {
                    $month_day = '0' . $month_day;
                }

                /* START JMLH HARI DALAM 1 BULAN POTONGAN ALPA */
                // if($monthPeriod<10){
                //     $monthPeriod_str    = substr($monthPeriod,1,1);
                // }else{
                //     $monthPeriod_str    = $monthPeriod;
                // }
                $jmlHari = cal_days_in_month(CAL_GREGORIAN, $month_day, $yearPeriod);
                $totalAlpa += (1 / 20) * $basicSalary;
                /* END JMLH HARI DALAM 1 BULAN POTONGAN ALPA */
                // uang alpa
                // if ($unpaidChar == '5') {
                //     $totalAlpa += (1/$jmlHari) * $basicSalary;
                // }
                // else if($unpaidChar == '6')
                // {
                //     $totalAlpa += (1/25) * $basicSalary;
                // }
            }
            /* END ALPA TOTAL */

            /* START OVER TIME BONUS */
            $allOverTimeTotal = $otTotal01 + $otTotal02 + $otTotal03 + $otTotal04;

            /* START OVER TIME BONUS */
            /* END OVER TIME BONUS */
            /* START DEVELOPMENT INCENTIVE */

            /* EXCLUDE If Work Hours Less Than 8 Hours */
            $excludeCountDay = 0;
            $colTmp = '';
            for ($z = 1; $z <= 31; $z++) {
                if ($z < 10) {
                    $colTmp = 'd0' . $z;
                } else {
                    $colTmp = 'd' . $z;
                }
                if (is_numeric($row[$colTmp]) && ($row[$colTmp] > 0) && ($row[$colTmp] < 8)) {
                    $excludeCountDay++;
                }

                if (substr($row[$colTmp], 0, 2) == 'PH') {
                    // test(strlen($row[$colTmp]),1);
                    $nilai      = strlen($row[$colTmp]);
                    $pengurang  = $nilai - 2;
                    $phVal = substr($row[$colTmp], 2, $pengurang);
                    if (is_numeric($phVal) && ($phVal > 0) && ($phVal < 8)) {
                        $excludeCountDay++;
                    }
                }
            }


            $alpaSickPercent = 0;
            for ($m = 0; $m < ($alpaTotal - $permitTotal); $m++) {
                $alpaSickPercent += 50;
            }
            for ($m = 0; $m < $sickTotal; $m++) {
                $alpaSickPercent += 15;
            }
            $devIncentiveBonus = 0;
            if ($alpaSickPercent < 100) {
                /* FORMULA CHANGE (35/100)/27) to (35/100)/26) BASE ON  */
                $tmp = $basicSalary * ((35 / 100) / 26) * ($row['attend_total'] + $row['in_ph_total'] - $excludeCountDay);
                /* (35%)/27 x Jumlah Masuk Kerja Yang Lebih Dari 7 Jam (Jika ada alpa, potong 50% perhari, Sakit 15% perhari ) */
                $devIncentiveBonus = $tmp - ($tmp * ($alpaSickPercent / 100));
            }

            if ($devIncentiveBonus <= 0) {
                $devIncentiveBonus = 0;
            }
            /* END DEVELOPMENT INCENTIVE */

            /* END GET PTKP TOTAL */

            $tmpSalary = $basicSalary;
            $wdAttendCount = 0;
            $bsProrate = 0;

            /* START GET PRORATE */
            if (($isProrateConfig == true)) {
                if (($clientName == 'Promincon_Indonesia') && ($standByCount > 0)) {
                    // echo $wdAttendCount; exit();

                    $wdAttendCount += $row['attend_total']; // 5
                    $wdAttendCount += $row['in_ph_total']; //2
                    // $wdAttendCount += $row['attend_in_off'];//2
                    $wdAttendCount += $row['sick_total']; //0
                    $wdAttendCount += $row['emergency_total']; //1
                    $wdAttendCount += $row['paid_permit_total']; //0
                    $wdAttendCount += $row['paid_vacation_total']; //0
                    $wdAttendCount += $row['alpa_total']; /* Include $row['permit_total'] */ //5


                    if ($clientName == 'Promincon_Indonesia') {
                        // if($monthPeriod<=10){
                        //     $monthPeriod_str    = substr($monthPeriod,1,1);
                        // }else{
                        //     $monthPeriod_str    = $monthPeriod;
                        // }

                        $month_day         = $monthPeriod - 1;

                        if ($month_day == 0) {
                            $month_day     = 12;
                        }
                        if ($month_day >= 10) {
                            $month_day = $month_day;
                        } else {
                            $month_day = '0' . $month_day;
                        }

                        $jmlHari = cal_days_in_month(CAL_GREGORIAN, $month_day, $yearPeriod);

                        // if($wdAttendCount > 21)
                        // {
                        //     $wdAttendCount = 21;
                        // }
                        // $bsProrate = ($wdAttendCount/$jmlHari) * $basicSalary;
                        // test($basicSalary.' '.$jmlHari.' '.$jmlHari.' '.$standByCount.' '.$fullName,1);
                        // $bsProrate = ($basicSalary / $jmlHari) * ($jmlHari - $standByCount);
                        $bsProrate = $attendTotal * ($umk / 20);
                        // echo $wdAttendCount; exit();
                    }

                    /* Request By Ilham & Bambang @15-12-2017 */
                    $tmpSalary = $bsProrate;
                }
            }


            if ($clientName == 'Promincon_Indonesia') {
                $bsProrate = $attendTotal * ($umk / 20);
            }
            // test($tmpSalary,1);
            // echo $row['attend_total']; exit(0);
            $mSalarySlip->setbs_prorate($bsProrate);

            /* START ALL ALLOWANCE */
            $allAllowance = 0;

            /* END ALL ALLOWANCE */

            /* START BRUTO INCOME */
            $bruto = $tmpSalary + $allOverTimeTotal + $allAllowance;
            /* END BRUTO INCOME */

            /* START NETTO INCOME */
            $nettoTax = $bruto - $empJht - $nonTaxAllowance;
            /* END NETTO INCOME */

            /* END GOVERNMENT REGULATION */

            /* START INSERT TABEL DATA SLIP */
            $slipId = $mSalarySlip->generateId('slip_id');
            // var_dump($slipId);
            // exit();
            $mSalarySlip->setSlipId($slipId['doc_id']);
            $mSalarySlip->setBiodataId($biodataId);
            $mSalarySlip->setSlipPeriod(16);
            $mSalarySlip->setMaritalStatus($maritalStatus);
            $mSalarySlip->setbaseWage($basicSalary);
            $mSalarySlip->setdailyWage($dailyBasic);
            $mSalarySlip->setTsId($rosterId);
            $mSalarySlip->setYearPeriod($yearPeriod);
            $mSalarySlip->setMonthPeriod($monthPeriod);

            $fullName = preg_replace('~[\r\n]+~', '', $fullName);
            $mSalarySlip->setFullName($fullName);
            $mSalarySlip->setDept($dept);
            $mSalarySlip->setPosition($position);
            $mSalarySlip->setMaritalStatus($maritalStatus);
            $mSalarySlip->setClientName($clientName);
            // $mSalarySlip->setBasicSalary($basicSalary);

            $mSalarySlip->setNormalTime($ntTotal);
            $mSalarySlip->setOt1($otTotal01);
            $mSalarySlip->setOt2($otTotal02);
            $mSalarySlip->setOt3($otTotal03);
            $mSalarySlip->setOt4($otTotal04);

            $mSalarySlip->setOtCount1($otCount1);
            $mSalarySlip->setOtCount2($otCount2);
            $mSalarySlip->setOtCount3($otCount3);
            $mSalarySlip->setOtCount4($otCount4);

            $mSalarySlip->setBpjs($healthBpjs);
            $mSalarySlip->setJkkJkm($jkkJkm);
            $mSalarySlip->setJht($jht);
            $mSalarySlip->setJp($jp);
            $mSalarySlip->setEmpBpjs($empHealthBpjs);
            $mSalarySlip->setEmpJht($empJht);
            $mSalarySlip->setEmpJp($empJp);
            /* START IN SHIFT, IN OFF, IN PH  */
            $shiftAttend = $row['attend_total'] - $row['attend_in_off'];
            $offAttend = $row['attend_in_off'];
            $phAttend = $row['in_ph_total'];
            $nightShiftCount = $row['night_shift_count'];
            $standbyTotal   = $row['standby_total'];
            // $this->M_overtime->setNightShiftCount($nightShiftCount);
            // $mSalarySlip->setInShift($shiftAttend);
            // $mSalarySlip->setInOff($offAttend);
            // $mSalarySlip->setInPh($phAttend);
            // Perhitungan Allowance
            if ($clientName == 'Promincon_Indonesia') {
                $tAttendT = $shiftAttend + $offAttend + $phAttend;
                $attend_count        = $tAttendT;
                $total_night_shift  = $nightShiftCount;
                $rate_per_day  = $dailyBasic;
                $list_allowance     = $this->mAllowanceConfig->loadConfigAllowance($clientName);


                foreach ($list_allowance as $key => $value) {
                    $formula   = $value['allowance_formula'];
                    $res_allow = eval('return ' . $formula . ';');
                    $mAllowance = new MtAllowance();

                    // Cari data allowance yang sudah ada
                    $existing = $mAllowance->where('biodata_id', $biodataId)
                        ->where('client_name', $clientName)
                        ->where('year_period', $yearPeriod)
                        ->where('month_period', $monthPeriod)
                        ->where('allowance_name', $value['allowance_field'])
                        ->first();

                    $data = [
                        'biodata_id'       => $biodataId,
                        'client_name'      => $clientName,
                        'year_period'      => $yearPeriod,
                        'month_period'     => $monthPeriod,
                        'emp_name'         => $row['full_name'],
                        'allowance_name'   => $value['allowance_field'],
                        'allowance_amount' => $res_allow,
                        'allowance_type' => "plus",
                        'pic_process'      => session('user_id'), // pengganti auth()->user()->user_id
                        'process_time'     => date('Y-m-d H:i:s'),
                    ];



                    if ($existing) {
                        // Update data allowance
                        $mAllowance->update($existing['allowance_id'], $data);
                    } else {
                        // Insert baru
                        $data['allowance_id'] = $mAllowance->generateNumber(); // jika pakai generator ID
                        $mAllowance->insert($data);
                    }
                }
                /* End Bonus */
            }

            /* END IN SHIFT, IN OFF, IN PH  */
            $mSalarySlip->setisEnd($isEnd);
            $mSalarySlip->setunpaid($unpaidLength);
            $mSalarySlip->setpotonganAbsensi($totalAlpa);
            $mSalarySlip->setNonTaxAllowance($nonTaxAllowance);
            $mSalarySlip->setPtkpTotal($totalPTKP);
            $mSalarySlip->setPicInput($userId);
            $mSalarySlip->setInputTime(date('Y-m-d H:i:s'));
            $test = $mSalarySlip->ins();
        }
    }

    public function taxProcess($biodata_id, $clientName, $tahun, $bulan, $dept, $isEnd, $limit = null, $offset = null)
    {
        set_time_limit(6000);
        ini_set('max_execution_time', 6000);
        ini_set('memory_limit', '-1');

        $db = \Config\Database::connect();
        $builder = $db->table('tr_slip');

        // 🔹 Query dasar
        $builder->where('month_period', $bulan)
            ->where('year_period', $tahun)
            ->where('client_name', $clientName);

        if ($biodata_id != 0) {
            $builder->where('biodata_id', $biodata_id);
        }

        if (!is_null($limit) && !is_null($offset)) {
            $builder->limit($limit, $offset);
        }

        $builder->orderBy('biodata_id', 'ASC');

        // 🔹 Eksekusi query
        $results = $builder->get()->getResult();

        $userId = session()->get('uId');
        $allResponses = [];

        foreach ($results as $row) {
            $biodata_id  = $row->biodata_id;
            $clientName  = $row->client_name;

            // 🔹 Komponen payroll
            $jkkJkm       = $row->jkkjkm;
            $emp_jp       = $row->emp_jp;
            $emp_jht      = $row->emp_jht;
            $healthBpjs   = $row->bpjs;
            $unpaidTotal  = $row->potongan_absensi;
            $marital      = $row->marital_status;

            $otTotal1 = $row->ot_1;
            $otTotal2 = $row->ot_2;
            $otTotal3 = $row->ot_3;
            $otTotal4 = $row->ot_4;
            $allOt = $otTotal1 + $otTotal2 + $otTotal3 + $otTotal4;
            $bsProrate = $row->bs_prorate;

            // 🔹 Ambil data allowance
            // $allowanceData = $this->mtAllowance->getAllowanceTotal($biodata_id, $clientName, $tahun, $bulan);
            $allowanceRaw = $this->mtAllowance->selectAllowanceAll($biodata_id, $clientName, $tahun, $bulan, '');
            $allowanceData = [];
            foreach ($allowanceRaw as $allowance) {
                $allowanceData[$allowance['allowance_name']] = $allowance['allowance_amount'];
            }

            $thr                = $allowanceData['thr'] ?? 0;
            $tunjangan      = $allowanceData['tunjangan'] ?? 0;
            $nightShiftBonus      = $allowanceData['night_shift_bonus'] ?? 0;
            $transportBonus      = $allowanceData['transport_bonus'] ?? 0;
            $attendanceBonus      = $allowanceData['attendance_bonus'] ?? 0;
            $adjustIn           = $allowanceData['adjustment_in'] ?? 0;
            $adjustOut          = $allowanceData['adjustment_out'] ?? 0;

            $bonusTotal    = $thr + $tunjangan + $nightShiftBonus + $transportBonus + $attendanceBonus + $adjustIn + $adjustOut;

            // 🔹 Hitung Brutto
            $brutto = $bsProrate + $allOt + $bonusTotal + $jkkJkm + $healthBpjs - $unpaidTotal;

            // 🔹 Cek status END
            $isEndDB      = $row->is_end;
            $checkFinalDB = $row->check_final;
            $end = ($isEnd == 1 || $isEndDB == 1 || $checkFinalDB == 'END' || $bulan == 12) ? 1 : 0;

            // 🔹 Update data slip
            // $updateData = [
            //     'is_end'       => $end == 1 ? 1 : null,
            //     'check_final'  => $end == 1 ? 'END' : null,
            // ];

            // $db->table('tr_slip')
            //     ->where('slip_id', $row->slip_id)
            //     ->update($updateData);

            // 🔹 Proses pajak

            $this->taxProcessController->taxProcess(
                $biodata_id,
                $brutto,
                $marital,
                $end,
                $bulan,
                $tahun,
                $clientName,
                $emp_jp,
                $emp_jht,
                $userId
            );
        }

        return $allResponses;
    }


    public function getPayrollList($clientName, $year, $month, $dataGroup)
    {

        $result = $this->trSalarySlip->getPayrollList($clientName, $year, $month, $dataGroup);

        $myData = [];
        $checkFinal = '';
        foreach ($result as $row) {

            if (isset($row->check_final) && $row->check_final === 'END') {
                $checkFinal = ' - ' . $row->check_final;
            } else {
                $checkFinal = '';
            }

            $myData[] = [
                $row->slip_id,
                $row->company_name,
                $row->biodata_id,
                $row->full_name,
                $row->dept,
                $row->position
            ];
        }

        return $myData;
    }

    public function checkClosingPayrollByPeriod($clientName, $year, $month, $dataGroup)
    {
        // Model instance
        $mProcessClossing = new ProcessClossing();

        // Ambil data payroll list (gunakan Eloquent atau Query Builder)
        $payrollList = $this->getPayrollList($clientName, $year, $month, $dataGroup);

        // Ambil status closing
        $clossingStatus = $mProcessClossing->getCountByClientPeriod($clientName, $year, $month);

        // Gabungkan data
        $myData = [
            'payrollList' => $payrollList,
            'clossingStatus' => $clossingStatus
        ];

        // Return API response
        return ResponseFormatter::success($myData, 'Successfully Retrieve data', 200);
    }
}
