<?php

namespace App\Controllers\Transaction;

use App\Controllers\BaseController;
use App\Helpers\ConfigurationHelper;
use App\Models\Admin\UserModel;
use App\Models\Transaction\M_download;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Timesheet_download extends BaseController
{
    protected $userModel;
    protected $mtdownload;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->mtdownload = new M_download();
    }

    public function index()
    {
        $session = session();
        $clients = $session->get('userClients');

        $data['actView'] = 'Transaction/timesheet_import';
        $data['clients'] = $clients;
        return view('home', $data);
    }

    public function downloadTimesheet()
    {
        $request = service('request'); // ambil instance request CI4

        $clientName = $request->getGet('clientName'); // karena form kamu pakai method GET
        $year = $request->getGet('year');
        $month = $request->getGet('month');

        return $this->timesheet($clientName, $year, $month);
    }

    public function timesheet($clientName, $tYear, $tMonth)
    {
        $shortName = ConfigurationHelper::getShortnameForTimesheet($clientName);
        // var_dump($shortName);
        // exit();
        $siteName = str_replace('_', ' ', $clientName);

        // === 2️⃣ Setup Spreadsheet ===
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('Tsaani Payroll System')
            ->setLastModifiedBy('Tsaani Payroll System')
            ->setTitle('Timesheet Template')
            ->setSubject('Timesheet Template')
            ->setDescription('Timesheet Template generated using CodeIgniter 4 & PhpSpreadsheet.')
            ->setKeywords('timesheet payroll ci4')
            ->setCategory('Payroll Template');

        // === 3️⃣ Gaya dasar spreadsheet ===
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->freezePane('E7');
        $sheet->getStyle("A1:D1")->getFont()->setBold(true)->setSize(16);
        $sheet->setCellValue('A1', 'Timesheet Payroll Site ' . $siteName);

        // === 4️⃣ Header kolom ===
        $sheet->setCellValue('A5', 'NO')
            ->setCellValue('B5', 'BIODATA ID')
            ->setCellValue('C5', 'NAME')
            ->setCellValue('D5', 'BADGE NO')
            ->setCellValue('E5', 'CLIENT');
        $sheet->mergeCells('A5:A6');
        $sheet->mergeCells('B5:B6');
        $sheet->mergeCells('C5:C6');
        $sheet->mergeCells('D5:D6');
        $sheet->mergeCells('E5:E6');


        // === 5️⃣ Hitung tanggal periode ===
        $previousMonth = new \DateTime("$tYear-$tMonth-01");
        $previousMonth->modify('first day of previous month')->modify('+10 days');
        $thisMonth = new \DateTime("$tYear-$tMonth-01");
        $thisMonth->modify('first day of this month')->modify('+9 days');
        $interval = $thisMonth->diff($previousMonth);
        $countDay = $interval->days + 1;

        if ($tMonth == 1) {
            $monthCount = 12;
            $yearCount = $tYear - 1;
        } else {
            $monthCount = $tMonth - 1;
            $yearCount = $tYear;
        }

        $dateInMonth = cal_days_in_month(CAL_GREGORIAN, $monthCount, $yearCount);

        // === 6️⃣ Kolom A - AZ ===
        $array_A_to_Z = range('A', 'Z');
        $array_A_to_AZ = [];
        foreach ($array_A_to_Z as $char) {
            $array_A_to_AZ[] = $char;
        }
        foreach ($array_A_to_Z as $char1) {
            foreach ($array_A_to_Z as $char2) {
                $array_A_to_AZ[] = $char1 . $char2;
            }
        }

        $startDate = ConfigurationHelper::getStartDatePeriode($clientName);
        $kordinatStart = 4;

        for ($i = 0; $i < $countDay; $i++) {
            $startDate++;
            $kordinatStart++;
            if ($startDate > $dateInMonth) {
                $startDate = 1;
            }
            $sheet->setCellValue($array_A_to_AZ[$kordinatStart] . '5', $startDate);
            $sheet->mergeCells($array_A_to_AZ[$kordinatStart] . "5:" . $array_A_to_AZ[$kordinatStart] . "6");
        }

        // === 7️⃣ Tambahan kolom belakang ===
        $extraCols = [
            'DEPT',
            'ROSTER BASE',
            'ROSTER FORMAT',
            // 'IS PRORATE BEFORE (1 or 0)',
            // 'DATE END (THIS PERIOD)',
            // 'IS END (1 or 0)',
            // 'IS BPJS Health (1 or 0)',
            // 'IS BPJS Ketenagakerjaan (1 or 0)'
        ];

        $colIndex = $kordinatStart + 1;
        foreach ($extraCols as $colTitle) {
            $sheet->setCellValue($array_A_to_AZ[$colIndex] . '5', $colTitle);
            $sheet->mergeCells($array_A_to_AZ[$colIndex] . "5:" . $array_A_to_AZ[$colIndex] . "6");
            $colIndex++;
        }

        // === 8️⃣ Style sederhana (warna & border tipis) ===
        $styleArray = [
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F2BE6B']
            ]
        ];

        $db = \Config\Database::connect();

        $builder = $db->table('mt_biodata_01 b')
            ->select("
        b.biodata_id,
        b.full_name,
        b.dept,
        s.company_name,
        s.id_no AS badge_no,
        s.monthly,
        s.daily,
        s.account_no,
        s.account_name,
        s.bank_id
    ")
            ->join('mt_salary s', 's.biodata_id = b.biodata_id', 'left')
            ->where('s.company_name', $clientName)
            ->where('b.is_active', 1)
            ->orderBy('b.full_name', 'ASC')
            ->groupBy('b.biodata_id');

        $query = $builder->get();
        $employees = $query->getResult();

        $row = 7;
        $no = 1;

        foreach ($employees as $emp) {

            $sheet->setCellValue("A{$row}", $no);
            $sheet->setCellValue("B{$row}", $emp->biodata_id);
            $sheet->setCellValue("C{$row}", $emp->full_name);
            $sheet->setCellValue("D{$row}", $emp->badge_no);
            $sheet->setCellValue("E{$row}", $emp->company_name);

            // Kolom untuk tanggal harian
            $col = 6;

            for ($i = 0; $i < $countDay; $i++) {
                $sheet->setCellValue($array_A_to_AZ[$col] . $row, '');
                $col++;
            }

            // Tulis DEPT di kolom pertama setelah tanggal
            $deptCol = $array_A_to_AZ[$kordinatStart + 1];
            $sheet->setCellValue("{$deptCol}{$row}", $emp->dept);


            // Roster Base
            $sheet->setCellValue($array_A_to_AZ[$col] . $row, '');
            $col++;

            // Roster Format
            $sheet->setCellValue($array_A_to_AZ[$col] . $row, '');
            $col++;

            $row++;
            $no++;
        }

        $lastRow = $row - 1;
        $lastCol = $array_A_to_AZ[$colIndex - 1];

        // Border ke seluruh area A5 sampai kolom terakhir & row terakhir
        $sheet->getStyle("A5:{$lastCol}{$lastRow}")
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ]
            ]);


        $sheet->getStyle("A5:" . $array_A_to_AZ[$colIndex - 1] . "6")->applyFromArray($styleArray);

        // === 9️⃣ Simpan ke file sementara & kirim ke browser ===
        $fileName = preg_replace('/\s+/', '', $shortName . $tYear . $tMonth) . '.xlsx';
        $tempFile = WRITEPATH . 'timesheet/' . $fileName;

        if (!is_dir(WRITEPATH . 'timesheet')) {
            mkdir(WRITEPATH . 'timesheet', 0777, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        // === 🔟 Kembalikan response download ===
        return $this->response
            ->download($tempFile, null)
            ->setFileName($fileName)
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function downloadAllowance()
    {
        $request = service('request'); // ambil instance request CI4

        $clientName = $request->getGet('clientName'); // karena form kamu pakai method GET
        $year = $request->getGet('year');
        $month = $request->getGet('month');
        // var_dump($clientName);
        // exit();

        return $this->allowance($clientName, $year, $month);
    }

    public function allowance($ptName, $tYear, $tMonth)
    {
        $shortName = ConfigurationHelper::getShortnameForAllowance($ptName);
        $client_display = strtoupper($ptName);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // === Style dasar
        $styleCenter = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'font' => ['bold' => true]
        ];

        // === Header Judul
        $sheet->setCellValue('A1', 'ALLOWANCE ' . $client_display);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // === Hitung periode
        $bulan = $tMonth - 1;
        if ($bulan == 0) $bulan = 12;
        $dateCal = ConfigurationHelper::getStartDatePeriode($ptName) + 1;
        $tStr = $tYear . '-' . $tMonth . '-' . $dateCal;
        $tDate2 = strtotime($tStr);
        $tDate3 = date("d-m-Y", strtotime("-1 month", $tDate2));
        $tDate4 = date("d-m-Y", strtotime("-1 day", $tDate2));
        $sheet->setCellValue('A2', "Periode : {$tDate3} to {$tDate4}");

        // === Kolom dasar
        $baseColumns = ['NO', 'BIODATA ID', 'NAME', 'POSITION'];

        // === Kolom allowance (mudah ditambah)
        $allowances = [
            // 'Tunjangan Kehadiran',
            // 'Tunjangan Transportasi',
            // 'Tunjangan Shift Malam',
            'Tunjangan',
            'THR',
            'Adjustment In',
            'Adjustment Out',
            'Adj',
            'Thr By User',
            'Beban Hutang'
        ];

        $allColumns = array_merge($baseColumns, $allowances);

        // === Generate header
        $colIndex = 1;
        foreach ($allColumns as $header) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->mergeCells("{$colLetter}3:{$colLetter}4");
            $sheet->setCellValue("{$colLetter}3", strtoupper($header));
            $colIndex++;
        }

        // === Style header
        $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($allColumns));
        $sheet->getStyle("A3:{$lastColLetter}4")->applyFromArray($styleCenter);
        $sheet->getStyle("A3:{$lastColLetter}4")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4BE36E');

        // === Lebar kolom proporsional (manual)
        $widthMap = [
            1 => 6,    // No
            2 => 14,   // Badge ID
            3 => 25,   // Name
            4 => 20,   // Position
            5 => 22    // Client
        ];

        $startAllowance = count($baseColumns) + 1;
        $endAllowance = count($baseColumns) + count($allowances);

        for ($i = 1; $i <= count($allColumns); $i++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            if (isset($widthMap[$i])) {
                $sheet->getColumnDimension($colLetter)->setWidth($widthMap[$i]);
            } elseif ($i >= $startAllowance && $i <= $endAllowance) {
                // allowance columns
                $sheet->getColumnDimension($colLetter)->setWidth(18);
            } else {
                // TOTAL or other
                $sheet->getColumnDimension($colLetter)->setWidth(15);
            }
        }

        // === Simpan file
        $str = $shortName . $tYear . $tMonth;
        $fileName = preg_replace('/\s+/', '', $str) . '.xlsx';
        $filePath = WRITEPATH . 'uploads/' . $fileName;

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $this->response
            ->download($filePath, null)
            ->setFileName($fileName)
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
