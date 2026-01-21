<?php

namespace App\Controllers\Transaction;

use App\Controllers\BaseController;
use App\Controllers\Master\Mt_salary;
use App\Helpers\ConfigurationHelper;
use App\Helpers\ResponseFormatter;
use App\Models\Master\M_mt_salary;
use App\Models\Transaction\M_tr_overtime;
use App\Models\Master\MtBiodata01;
use App\Models\Master\ProcessClossing;
use App\Models\Transaction\M_tr_timesheet;
use App\Models\Transaction\SalarySlip;
use CodeIgniter\I18n\Time;
use Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;

class Timesheet extends BaseController
{
    protected $mtProcessClosssing;
    protected $trSalarySlip;
    protected $trOvertime;

    public function __construct()
    {
        $this->mtProcessClosssing = new ProcessClossing();
        $this->trSalarySlip = new SalarySlip();
        $this->trOvertime = new M_tr_overtime();
    }
    public function upload()
    {
        $request = service('request'); // ambil instance request CI4

        // === 🔹 Validasi File ===
        $validationRule = [
            'file' => [
                'label' => 'File Timesheet',
                'rules' => 'uploaded[file]|ext_in[file,xls,xlsx,csv]|max_size[file,10000]',
                'errors' => [
                    'uploaded' => 'File wajib diunggah.',
                    'ext_in'   => 'Format file harus .xls, .xlsx, atau .csv.',
                    'max_size' => 'Ukuran file maksimal 10MB.'
                ]
            ]
        ];

        $validation = \Config\Services::validation();
        if (! $validation->setRules($validationRule)->withRequest($request)->run()) {
            return ResponseFormatter::error(
                'Validasi gagal',
                $validation->getErrors(),
                400
            );
        }

        // === 🔹 Ambil file ===
        $file = $request->getFile('file');
        $originalName = $file->getClientName();
        $shortName = substr($originalName, 0, 5);

        // === 🔹 Ambil tahun & bulan dari nama file ===
        $shortName = substr($originalName, 0, 6);
        $year = substr($originalName, 6, 4);
        $month = substr($originalName, 10, 2);
        $clientName = ConfigurationHelper::getClientNameByTimesheet($shortName);
        // var_dump($clientName);


        // === 🔹 Validasi nama client ===
        if (empty($clientName)) {
            return ResponseFormatter::error(
                'Import Data Failed, Please Check File Format',
                null,
                400
            );
        }


        $dataCount = $this->mtProcessClosssing->getCountByClientPeriod($clientName, $year, $month);
        if ($dataCount >= 1) {
            return ResponseFormatter::error("Error", 'Data with the same period has been uploaded before', 500);
        }

        // === 🔹 Simpan file ===
        $fileDir = WRITEPATH . 'uploads/';
        if (!is_dir($fileDir)) {
            mkdir($fileDir, 0755, true);
        }

        $fileName = time() . '_' . $originalName;
        $file->move($fileDir, $fileName);
        $inputFileName = $fileDir . $fileName;

        // === 🔹 Panggil fungsi uploadTs (jika sudah ada)
        if (method_exists(__CLASS__, 'uploadTs')) {
            return $this->uploadTs($inputFileName, $clientName, $year, $month, $request);
        }

        // Kalau belum ada uploadTs, kirim respons sukses dasar
        return ResponseFormatter::success([
            'client' => $clientName,
            'file' => $fileName,
            'year' => $year,
            'month' => $month,
            'path' => $inputFileName
        ], 'File berhasil diunggah');
    }

    public function uploadTs($inputFileName, $clientName, $year, $month, $request)
    {
        helper(['date']);

        set_time_limit(6000);
        ini_set('memory_limit', '-1');

        // $mRoster = new Tr_timesheet();
        $mSalary = new Mt_salary();
        $mBiodata = new MtBiodata01();
        $trTimesheet = new M_tr_timesheet();
        $userId = $_SESSION['uId'];
        $currDateTm = date("Y-m-d H:i:s");


        try {
            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load($inputFileName);
        } catch (Exception $e) {
            return ResponseFormatter::error("Error", 'Error loading file: ' . $e->getMessage(), 500);
        }

        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $dataCount = 0;
        $failedNames = [];

        $db = db_connect();
        $db->transStart();

        $startRow = 7;

        // tMonth dan tYear adalah bulan sebelumnya
        $startBase = Time::createFromDate($year, $month, 1)->subMonths(1);

        // tanggal awal: 16 bulan berikutnya
        $startDate = $startBase->setDay(16);

        // buat clone untuk tanggal akhir
        $endDate = clone $startDate;
        $endDate = $endDate->addMonths(1)->setDay(15);

        // hitung hari inklusif
        $interval = $startDate->difference($endDate);
        $countDay = $interval->getDays() + 1;

        for ($row = $startRow; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            if (empty($rowData[0][1])) continue;



            $biodataId = $rowData[0][1];
            $fullName = $rowData[0][2];
            $badgeNo = $rowData[0][3];
            $clientCode = $rowData[0][4];
            $client = ConfigurationHelper::getClientValueByTimesheetUpload($clientCode);
            // var_dump($biodataId);
            // exit();
            // Tentukan jumlah hari berdasarkan kolom
            $dayColumnIndex = 4;

            switch ($highestColumn) {
                case 'AM':
                    $numDays = 31;
                    break;
                case 'AJ':
                    $numDays = 28;
                    break;
                case 'AL':
                    $numDays = 30;
                    break;
                case 'AK':
                    $numDays = 29;
                    break;
                default:
                    $numDays = 31;
                    break;
            }



            // $numDays = match ($highestColumn) {
            //     // "AR" => 31,
            //     // "AQ" => 30,
            //     // "AP" => 29,
            //     // "AO" => 28,
            //     // default => 31,

            //     'AM' => 31,
            //     'AJ' => 28,
            //     'AL' => 30,
            //     'AK' => 29,
            //     default => 31,
            // };


            $dept = $rowData[0][$dayColumnIndex + $numDays + 1];
            $rosterBase = $rowData[0][$dayColumnIndex + $numDays + 2];
            $rosterFormat = $rowData[0][$dayColumnIndex + $numDays + 3];


            // Validasi roster base dan format
            if ($rosterBase != '52' || empty($rosterFormat)) {
                return ResponseFormatter::error(
                    "error",
                    "Roster Base $fullName kosong/salah.",
                    500
                );
            }
            if (empty($rosterFormat)) {
                return ResponseFormatter::error(
                    "error",
                    "Roster Format $fullName salah/kosong.",
                    500
                );
            }
            // Bersihkan karakter non-digit (misal menghilangkan tanda "-")
            $onlyDigits = preg_replace('/[^0-9]/', '', $rosterFormat);

            // Pecah menjadi array digit, lalu jumlahkan
            $sumRoster = array_sum(str_split($onlyDigits));

            // Validasi jumlah hari roster dengan countDay
            if ($sumRoster != $countDay) {
                return ResponseFormatter::error(
                    "error",
                    "Silahkan cek Roster Format $fullName.",
                    500
                );
            }

            // Ambil data salary berdasarkan Biodata Id
            $mtSalary = new M_mt_salary();
            $dataSalary =  $mtSalary->getByBioId($biodataId);


            if (empty($dataSalary)) {
                $failedNames[] = $fullName;
                continue;
            }
            $biodataIdSalary = $dataSalary['biodata_id'];

            if (empty($biodataIdSalary)) {
                return ResponseFormatter::error("error", "Data Salary $fullName tidak ditemukan.", 404);
            }

            $days = [];

            $trTimesheet->resetValues();
            $this->trSalarySlip->deleteByBiodataIdPeriod($biodataId, $clientName, $year, $month);
            $this->trOvertime->deleteByBiodataIdPeriod($biodataId, $clientName, $year, $month);
            $trTimesheet->deleteByBiodataIdPeriod($biodataId, $clientName, $year, $month);

            for ($i = 1; $i <= $numDays; $i++) {
                $dayValue = isset($rowData[0][$dayColumnIndex + $i])
                    ? trim(strtoupper(preg_replace('/[\r\n]+/', '', $rowData[0][$dayColumnIndex + $i])))
                    : 'U';

                $method = 'setD' . str_pad($i, 2, '0', STR_PAD_LEFT);
                $varName = '$day' . $i; // kalau kamu mau echo format $day1, $day2, dst

                // kalau kamu ingin langsung assign ke entity:
                if (method_exists($trTimesheet, $method)) {
                    $trTimesheet->$method($dayValue);
                }
            }

            $headerId = $trTimesheet->generateId('ts_id')['doc_id'];


            $trTimesheet->setTsId($headerId);
            $trTimesheet->setBiodataId($biodataId);
            $mtSalary->setBiodataId($biodataId);
            $bankcode = $dataSalary['bank_id'];
            $trTimesheet->setbankId($bankcode);
            $trTimesheet->setBadgeNo($badgeNo);
            $trTimesheet->setFullName($fullName);
            $trTimesheet->setClientName($clientName);
            // $trTimesheet->setpayrollGroup($sm);
            $trTimesheet->setRosterBase($rosterBase);
            $trTimesheet->setRosterFormat($rosterFormat);
            // var_dump($rosterFormat);
            // exit();
            $trTimesheet->setDept($dept);
            $trTimesheet->setdateProcess($currDateTm);
            $trTimesheet->setStartDate(16);
            $trTimesheet->setMonthProcess($month);
            $trTimesheet->setYearProcess($year);
            $trTimesheet->setPicProcess($userId);
            $trTimesheet->setProcessTime($currDateTm);
            $trTimesheet->ins();

            $dataCount++;
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            $dbError = $db->error();
            $lastQuery = (string) $db->getLastQuery();

            $db->transRollback(); // ❌ Ini salah total. Transaksi sudah complete.
            return ResponseFormatter::error('error', 'Gagal menyimpan roster ' . $dbError, 500);
        }

        return ResponseFormatter::success(null, "Berhasil menyimpan $dataCount data roster untuk $clientName", 200);
    }


    public function calculateRosterDay(string $format): int
    {
        // Hilangkan spasi & karakter tidak penting
        $format = trim($format);

        // Jika kosong → langsung 0
        if ($format === '') {
            return 0;
        }

        // Pecah per segmen dengan tanda "-"
        $segments = explode('-', $format);

        $total = 0;

        foreach ($segments as $seg) {
            // Pastikan segmen hanya angka (contoh: "12", "52")
            if (!ctype_digit($seg)) {
                // Kalau segmen ada huruf atau simbol lainnya → error aman
                throw new \Exception("Roster format mengandung karakter non-angka: '$seg'");
            }

            // Ambil setiap digit dari segmen
            $digits = str_split($seg);

            // Tambahkan total digit ke total keseluruhan
            foreach ($digits as $d) {
                $total += intval($d);
            }
        }

        return $total;
    }
}
