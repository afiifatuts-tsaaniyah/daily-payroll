<?php

namespace App\Controllers\Transaction;

use App\Controllers\BaseController;
use App\Helpers\ConfigurationHelper;
use App\Helpers\ResponseFormatter;
use App\Models\Master\M_mt_salary;
use App\Models\Master\MtAllowance;
use App\Models\Transaction\M_tr_overtime;
use App\Models\Master\ProcessClossing;
use App\Models\Transaction\SalarySlip;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class Allowance extends BaseController
{
    protected $mtProcessClosssing;
    protected $trSalarySlip;
    protected $mtSalary;
    protected $trOvertime;
    protected $mAllowance;

    public function __construct()
    {
        $this->mAllowance = new MtAllowance();
        $this->mtProcessClosssing = new ProcessClossing();
        $this->trSalarySlip = new SalarySlip();
        $this->mtSalary = new M_mt_salary();
        $this->trOvertime = new M_tr_overtime();
    }

    public function upload()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        // === Validasi file ===
        $validationRule = [
            'file' => [
                'label' => 'File',
                'rules' => 'uploaded[file]|ext_in[file,xls,xlsx,csv]|max_size[file,15000]',
            ],
        ];

        if (! $this->validate($validationRule)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Import Data Failed, Please Check File Format',
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        $req = service('request');
        $file = $req->getFile('file');
        $originalName = $file->getClientName();


        $smFile = substr($originalName, 0, 2);
        $sm = '';



        if ($smFile == 'SM') {
            $sub = substr($originalName, 2, 3);

            if ($sub > 99) {
                $sm = substr($originalName, 0, 5);
                $shortName = substr($originalName, 5, 9);
                $year  = substr($originalName, 14, 4);
                $month = substr($originalName, 18, 2);
            } else if ($sub < 100) {
                $sm = substr($originalName, 0, 4);
                $shortName = substr($originalName, 4, 9);
                $year  = substr($originalName, 13, 4);
                $month = substr($originalName, 17, 2);
            }

            // var_dump($sm, $shortName, $year, $month);
            // exit();
        } else {
            // Extract name → client, year, month
            $shortName = substr($originalName, 0, 9);
            $year  = substr($originalName, 9, 4);
            $month = substr($originalName, 13, 2);
        }


        $clientName = ConfigurationHelper::getClientNameByAllowance($shortName);
        if (!$clientName) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Import failed: client not detected from file name',
            ]);
        }

        // Cek periode sudah ada?
        $processModel = new ProcessClossing();
        if ($processModel->getCountByClientPeriod($clientName, $year, $month) >= 1) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data with the same period has been uploaded before',
            ]);
        }

        // Upload file
        $saveDir  = WRITEPATH . 'uploads/';
        $newName  = time() . '_' . $originalName;
        $file->move($saveDir, $newName);
        $filePath = $saveDir . $newName;

        // Load Excel
        try {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Error loading file: ' . $e->getMessage(),
            ]);
        }

        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();


        $dataUploaded    = $spreadsheet->getActiveSheet()->toArray();

        $db = \Config\Database::connect();
        $db->transBegin();

        $now = date("Y-m-d H:i:s");
        $userId = $_SESSION['uId'];

        $salaryModel = $this->mtSalary;
        $allowanceModel = new MtAllowance();

        $batchInsert = [];
        $salaryCache = [];
        $failed = [];

        // PREPARE ALLOWANCE ID
        $prefix = date('Ym');                // contoh 202511
        $counter = $allowanceModel->getLastNumber(); // ambil nomor terakhir 1x saja



        // Hapus data lama (1x saja)
        // $this->mAllowance->deleteByClientPeriod($clientName, $year, $month);

        for ($row = 4; $row <= $highestRow; $row++) {

            $biodataId = trim((string)$sheet->getCell("B{$row}")->getValue());
            if ($biodataId == '') continue;
            $this->mAllowance->deleteByIdPeriod($biodataId, $clientName, $year, $month, $sm);

            // Salary cache
            if (!isset($salaryCache[$biodataId])) {
                $salaryCache[$biodataId] = $salaryModel->getByBioId($biodataId);
            }

            if (empty($salaryCache[$biodataId])) {
                $failed[] = $biodataId;
                continue;
            }

            $empName = (string)$sheet->getCell("C{$row}")->getValue();

            // Ambil config allowance
            $allowances = ConfigurationHelper::getAllowanceConfigOptimized($clientName, $sheet, $row);


            foreach ($allowances as $field => $value) {
                $remarks = '';
                if ($value == 0 || $value === null || $value === "") continue;

                // Generate allowance ID aman
                $counter++;
                $allowance_id = $prefix . str_pad($counter, 4, '0', STR_PAD_LEFT);

                if ($clientName == 'Agincourt_Martabe' && $field == 'workday_adjustment') {
                    $remarks = (string)$sheet->getCell("I{$row}")->getValue();
                }

                $batchInsert[] = [
                    'allowance_id'     => $allowance_id,
                    'biodata_id'       => $biodataId,
                    'client_name'      => $clientName,
                    'emp_name'         => $empName,
                    'year_period'      => $year,
                    'month_period'     => $month,
                    'allowance_name'   => $field,
                    'allowance_amount' => $value,
                    'remarks'          => $remarks,
                    'pic_process'      => $userId,
                    'process_time'     => $now,
                    'payroll_group'       => $sm,
                ];
                // var_dump($batchInsert);
                // exit();


                // Insert per 300 rows (lebih cepat)
                if (count($batchInsert) >= 300) {
                    $allowanceModel->insertBatch($batchInsert);
                    $batchInsert = [];
                }
            }
        }

        if (!empty($batchInsert)) {
            $allowanceModel->insertBatch($batchInsert);
        }

        $db->transCommit();

        return $this->response->setJSON([
            'status'  => true,
            'data' => $dataUploaded,
            'message' => "Import Allowance Success for {$clientName}",
            'failed'  => $failed,
        ]);
    }


    public function downloadAllowance($year, $month, $startDate, $sm, $arrayBio)
    {
        // $request = service('request'); // ambil instance request CI4

        // $clientName = $request->getGet('clientName'); // karena form kamu pakai method GET
        $clientName = 'Agincourt_Martabe'; // karena form kamu pakai method GET
        // $year = $request->getGet('year');
        // $month = $request->getGet('month');
        // var_dump($arrayBio);
        // exit();

        return $this->allowance($clientName, $year, $month, $startDate, $sm, $arrayBio);
    }

    public function allowance($ptName, $tYear, $tMonth, $startDate, $sm, $arrayBio)
    {
        /**
         * =====================================
         * 1. BASIC CONFIG
         * =====================================
         */
        $shortName = ConfigurationHelper::getShortnameForAllowance($ptName);
        $client    = strtoupper($ptName);

        /**
         * =====================================
         * 2. HANDLE BIODATA ID
         * input bisa:
         * - "221000008,221000009"
         * - ['221000008','221000009']
         * =====================================
         */
        $biodataIds = [];

        if (is_string($arrayBio)) {
            $biodataIds = array_map('trim', explode(',', $arrayBio));
        }

        if (is_array($arrayBio)) {
            $biodataIds = $arrayBio;
        }

        /**
         * =====================================
         * 3. GET BIODATA FROM DATABASE (CI4)
         * =====================================
         */
        $db = \Config\Database::connect();

        $biodataBuilder = $db->table('mt_biodata_01');
        $biodataBuilder->select('biodata_id, full_name, emp_position');

        if (!empty($biodataIds)) {
            $biodataBuilder->whereIn('biodata_id', $biodataIds);
        }

        $biodataList = $biodataBuilder->get()->getResultArray();

        /**
         * =====================================
         * 4. CREATE EXCEL
         * =====================================
         */
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        /**
         * =====================================
         * 5. TITLE & PERIODE
         * =====================================
         */
        $sheet->setCellValue('A1', 'ALLOWANCE ' . $client);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $dateStart = date(
            'd-m-Y',
            strtotime('-1 month', strtotime("$tYear-$tMonth-01"))
        );
        $dateEnd = date(
            'd-m-Y',
            strtotime('-1 day', strtotime("$tYear-$tMonth-01"))
        );

        $sheet->setCellValue('A2', "Periode : {$dateStart} to {$dateEnd}");

        /**
         * =====================================
         * 6. TABLE HEADER
         * =====================================
         */
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

        if ($ptName == 'Promincon_Indonesia') {
            $columns = [
                'NO',
                'BIODATA ID',
                'NAME',
                'POSITION',
                'TUNJANGAN',
                'THR',
                'ADJUSTMENT IN',
                'ADJUSTMENT OUT',
                'ADJ',
                'THR BY USER',
                'BEBAN HUTANG'
            ];
        } else {
            $columns = [
                'NO',
                'BIODATA ID',
                'NAME',
                'POSITION',
                'THR',
                'ADJUSTMENT IN',
                'ADJUSTMENT OUT',
                'ADJ',
                'REMARKS',
                'THR BY USER',
                'Lain-Lain'
            ];
        }



        $colIndex = 1;
        foreach ($columns as $title) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($col . '3', $title);
            $sheet->mergeCells($col . '3:' . $col . '4');
            $colIndex++;
        }

        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($columns));

        $sheet->getStyle("A3:{$lastCol}4")->applyFromArray($styleCenter);
        $sheet->getStyle("A3:{$lastCol}4")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4BE36E');

        /**
         * =====================================
         * 8. FILL DATA
         * =====================================
         */
        $row = 5;
        $no  = 1;

        foreach ($biodataList as $bio) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", $bio['biodata_id']);
            $sheet->setCellValue("C{$row}", $bio['full_name']);
            $sheet->setCellValue("D{$row}", $bio['emp_position']);
            $row++;
        }
        /**
         * FULL BORDER UNTUK SEMUA TABEL
         */
        $lastRow = $row - 1;
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($columns));

        $sheet->getStyle("A3:{$lastCol}{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        /**
         * =====================================
         * 9. SAVE & DOWNLOAD
         * =====================================
         */
        $fileName = "{$sm}{$shortName}{$tYear}{$tMonth}.xlsx";
        $filePath = WRITEPATH . 'uploads/' . $fileName;

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $this->response
            ->download($filePath, null)
            ->setFileName($fileName)
            ->setContentType(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            );
    }
}
