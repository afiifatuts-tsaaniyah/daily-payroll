<?php

namespace App\Controllers\Report;

use App\Models\Transaction\M_rp_db;
use App\Controllers\BaseController;
use App\Controllers\Transaction\Spreadsheet_template_controller;
use App\Helpers\ConfigurationHelper;
use App\Models\Master\Client;
use App\Models\Master\Config;
use App\Models\Master\M_dept;
use App\Models\Master\MtAllowance;
use App\Models\Transaction\SalarySlip;


class Summary_invoice extends BaseController
{
    public function index()
    {
        $session = session();
        $clients = $session->get('userClients');

        $data['clients'] = $clients;
        $data['actView'] = 'Report/summary_invoice';
        return view('home', $data);
    }

    public function exportInvoice($clientName, $dataPrint, $yearPeriod, $monthPeriod, $dataGroup)
    {
        // Load Spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $spreadsheet->getProperties()
            ->setCreator('Maurice - Web - Android')
            ->setLastModifiedBy('Maurice - Web - Android')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Generated using PhpSpreadsheet (CI4)')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Export Invoice');

        $mSalarySlip = new SalarySlip();
        // Ambil data payroll
        $payrollDataResults = $mSalarySlip
            ->getEmployeePayrollDetails($clientName, $yearPeriod, $monthPeriod, $dataGroup);
        // Tentukan file export sesuai dataPrint
        if ($dataPrint == "summaryInvoice") {

            if ($clientName == 'Promincon_Indonesia') {
                $fileName = $this->exportSummaryInvoicePmc(
                    $clientName,
                    $yearPeriod,
                    $monthPeriod,
                    $dataGroup,
                    $spreadsheet
                );
            }
        } elseif ($dataPrint == "paymentList") {

            $fileName = $this->exportPaymentListClient(
                $clientName,
                $yearPeriod,
                $monthPeriod,
                $dataGroup,
                $spreadsheet
            );
        }
        //elseif ($dataPrint == "invoiceReceiptNote") {

        //     $fileName = $this->exportReceiptInvoicePGP(
        //         $clientName,
        //         $yearPeriod,
        //         $monthPeriod,
        //         $spreadsheet,
        //         $payrollDataResults
        //     );
        // } elseif ($dataPrint == "nap") {

        //     $fileName = $this->exportNapPGP(
        //         $clientName,
        //         $yearPeriod,
        //         $monthPeriod,
        //         $spreadsheet,
        //         $payrollDataResults
        //     );
        // } else {
        //     return "Invalid dataPrint parameter.";
        // }

        // Create Writer
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        // CI4 Temporary File Path
        $tempFilePath = WRITEPATH . 'exports/' . $fileName;

        // Create folder jika belum ada
        if (!is_dir(WRITEPATH . 'exports')) {
            mkdir(WRITEPATH . 'exports', 0777, true);
        }

        // Save file
        $writer->save($tempFilePath);

        // Return file as download (CI4)
        return $this->response
            ->download($tempFilePath, null)
            ->setFileName($fileName)
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function exportSummaryInvoicePmc($clientName, $yearPeriod, $monthPeriod, $dataGroup, $spreadsheet)
    {
        Spreadsheet_template_controller::addLogo($spreadsheet, 'A1');
        $styles = Spreadsheet_template_controller::getStyles();
        $outlineBorderStyle = $styles['outlineBorderStyle'];
        $allBorderStyle = $styles['allBorderStyle'];
        $center = $styles['center'];
        $right = $styles['right'];
        $left = $styles['left'];

        // CI4 Model Call
        $mSalarySlip = new SalarySlip();
        $payrollDataResults = $mSalarySlip
            ->getEmployeePayrollDetails($clientName, $yearPeriod, $monthPeriod, $dataGroup);

        // Client Display – CI4 style

        $clientModel = new Client();
        $queryClient = $clientModel->clientDisplay($clientName);

        $clientDisplay = $queryClient->clientDisplay ?? '';


        $lastColumn = 'Z';

        // ==========================
        //  HEADER
        // ==========================
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'SUMMARY INVOICE PT SANGATI SOERYA SEJAHTERA - PT ' . strtoupper($clientDisplay))
            ->setCellValue('A2', 'PERIOD : ' . $monthPeriod . '-' . $yearPeriod)
            ->setCellValue('A3', 'DATE        : ')
            ->setCellValue('A4', 'INVOICE NO  : ')
            ->setCellValue('A5', 'CONTRACT NO : ');

        $sheet->mergeCells("A1:$lastColumn" . "1");
        $sheet->mergeCells("A2:$lastColumn" . "2");

        $sheet->getStyle("A1:$lastColumn" . "1")->applyFromArray($center);
        $sheet->getStyle("A2:$lastColumn" . "2")->applyFromArray($center);

        $sheet->getStyle("A1:D1")->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle("A2:D2")->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle("A3:G5")->getFont()->setBold(true)->setSize(12);

        // Background color header
        $sheet->getStyle('A6:' . $lastColumn . '7')
            ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('F2BE6B');

        // Header title
        $sheet->getStyle("A6:H6")->getFont()->setSize(12);
        $sheet->getStyle("A6:H6")->applyFromArray($outlineBorderStyle);

        $sheet->fromArray([
            [
                'NO',
                'NAME',
                'ID BADGE',
                'POSITION',
                'UMK',
                'TOTAL HARI KERJA',
                'TOTAL HARI KERJA MALAM',
                'SALARY THIS MONTH',
                'OVERTIME',
                'TUNJANGAN KEHADIRAN',
                'TUNJANGAN TRANSPORTASI',
                'TUNJANGAN SHIFT MALAM',
                'ALLOWANCE 4',
                'THR',
                'ABSENSI',
                'BPJS KESEHATAN',
                'BPJS KETENAGAKERJAAN',
                'BPJS PENSIUN',
                'GROSS SALARY',
                'CONTRACTOR FEE',
                'PKWT KOMPENSASI',
                'MCU',
                'APD',
                'TOTAL KOMPENSASI, MCU, APD',
                'HANDLING FEE',
                'TOTAL INVOICE'
            ]
        ], NULL, 'A6');

        $sheet->fromArray(range('A', $lastColumn), NULL, 'A8');

        foreach (range('B', $lastColumn) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->freezePane('C10');

        // Merge header cells
        foreach (range('A', $lastColumn) as $col) {
            $sheet->mergeCells("$col" . "6:$col" . "7");
        }

        $sheet->getStyle("A6:$lastColumn" . "8")->applyFromArray($allBorderStyle);
        $sheet->getStyle("A6:$lastColumn" . "8")->applyFromArray($center);

        // ================================
        // ROWS
        // ================================
        $rowIdx = 9;
        $rowNo = 0;

        $allowanceModel = new MtAllowance();
        $configModel = new Config();

        foreach ($payrollDataResults as $row) {
            $rowIdx++;
            $rowNo++;

            $biodataId = $row->biodata_id;
            $dataAbsen = $mSalarySlip->getAttendDataByBiodataId($biodataId, $clientName, $yearPeriod, $monthPeriod);

            $configData = $configModel->loadByClient($clientName);

            $healthBpjsPercent = $configData['health_bpjs'];
            $jkkJkmPercent = $configData['jkk_jkm'];
            $jhtPercent = $configData['jht'];
            $totalJkmPercent = $jkkJkmPercent + $jhtPercent;
            $empJhtPercent = $configData['emp_jht'];

            $allowanceRaw = $allowanceModel->selectAllowanceAll($biodataId, $clientName, $yearPeriod, $monthPeriod, '');
            $allowanceData = [];
            foreach ($allowanceRaw as $allowance) {
                $allowanceData[$allowance['allowance_name']] = $allowance['allowance_amount'];
            }
            $thr = $allowanceData['thr'] ?? 0;
            $attendanceBonus = $allowanceData['attendance_bonus'] ?? 0;
            $transportBonus  = $allowanceData['transport_bonus'] ?? 0;
            $nightShiftBonus = $allowanceData['night_shift_bonus'] ?? 0;
            $otherAllowance = $allowanceData['tunjangan'] ?? 0;

            $sheet->setCellValue("A$rowIdx", $rowNo);
            $sheet->setCellValue("B$rowIdx", $row->full_name);
            $sheet->setCellValue("C$rowIdx", '');
            $sheet->setCellValue("D$rowIdx", $row->job_desc);
            $sheet->setCellValue("E$rowIdx", $row->base_wage);
            $sheet->setCellValue("F$rowIdx", $dataAbsen->attend_total);
            $sheet->setCellValue("G$rowIdx", $dataAbsen->night_shift_count);
            // $sheet->setCellValue("H$rowIdx", - ($row->potongan_absensi));
            $sheet->setCellValue("H$rowIdx", ($row->bs_prorate));
            $sheet->setCellValue("I$rowIdx", $row->ot_total);
            $sheet->setCellValue("J$rowIdx", $attendanceBonus);
            $sheet->setCellValue("K$rowIdx", $transportBonus);
            $sheet->setCellValue("L$rowIdx", $nightShiftBonus);
            $sheet->setCellValue("M$rowIdx", $otherAllowance);
            $sheet->setCellValue("N$rowIdx", $thr);
            $sheet->setCellValue("O$rowIdx", -$row->potongan_absensi);

            $sheet->setCellValue("P$rowIdx", "=E$rowIdx*$healthBpjsPercent%");
            $sheet->setCellValue("Q$rowIdx", "=E$rowIdx*$totalJkmPercent%");
            $sheet->setCellValue("R$rowIdx", "=E$rowIdx*$empJhtPercent%");

            $sheet->setCellValue("S$rowIdx", "=ROUND(SUM(H$rowIdx:R$rowIdx),0)");
            $sheet->setCellValue("T$rowIdx", "=ROUND(S$rowIdx*13%,0)");
            $sheet->setCellValue("U$rowIdx", 0);
            $sheet->setCellValue("V$rowIdx", 0);
            $sheet->setCellValue("W$rowIdx", 0);
            $sheet->setCellValue("X$rowIdx", "=U$rowIdx+V$rowIdx+W$rowIdx");
            $sheet->setCellValue("Y$rowIdx", "=X$rowIdx*5%");
            $sheet->setCellValue("Z$rowIdx", "=ROUND(S$rowIdx+T$rowIdx+X$rowIdx+Y$rowIdx,0)");

            // Odd row highlight
            if ($rowIdx % 2 == 1) {
                $sheet->getStyle("A$rowIdx:$lastColumn$rowIdx")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('EAEBAF');
            }

            // Highlight negative THP
            $totalTerima = $mSalarySlip->getTakeHomePay(
                $clientName,
                $biodataId,
                $yearPeriod,
                $monthPeriod,
                $row->slip_id,
                $allowanceData
            );

            if ($totalTerima <= 0) {
                $sheet->getStyle("A$rowIdx:$lastColumn$rowIdx")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FF0000');
            }
        }

        // ===========================
        // TOTAL ROW
        // ===========================
        $totalRow = $rowIdx + 2;

        $sheet->setCellValue("C$totalRow", 'TOTAL');
        foreach (range('E', 'Z') as $col) {
            $sheet->setCellValue("$col$totalRow", "=SUM($col" . "9:$col$rowIdx)");
        }

        $sheet->getStyle("A$totalRow:$lastColumn$totalRow")->applyFromArray($outlineBorderStyle);
        $sheet->getStyle("A$totalRow:$lastColumn$totalRow")->getFont()->setBold(true)->setSize(12);

        $sheet->getStyle("A$totalRow:$lastColumn$totalRow")->getFill()->setFillType(
            \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
        )->getStartColor()->setRGB('F2BE6B');

        $sheet->getStyle("D8:$lastColumn$totalRow")
            ->getNumberFormat()->setFormatCode('#,##0');

        $spreadsheet->setActiveSheetIndex(0);

        $fileName = 'SummaryInvoice_' . $clientName . '_' . $dataGroup . '-' . $monthPeriod . '.xlsx';
        return preg_replace('/\s+/', '', $fileName);
    }

    public function exportPaymentListClient($clientName, $yearPeriod, $monthPeriod, $dataGroup, $spreadsheet)
    {
        // Add logo & style template
        Spreadsheet_template_controller::addLogo($spreadsheet, 'A1');
        $styles = Spreadsheet_template_controller::getStyles();

        /* AUTO WIDTH */
        foreach (range('B', 'I') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Get client display
        $queryClient = Client::clientDisplay($clientName);

        $clientDisplay = $queryClient['clientDisplay'];


        // Header Excel
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1', 'PT. SANGATI SOERYA SEJAHTERA')
            ->setCellValue('A2', 'LIST PEMBAYARAN GAJI KARYAWAN PT. ' . strtoupper($clientDisplay) . ' (' . $dataGroup . ')')
            ->setCellValue('A4', 'Periode : ' . $monthPeriod . '-' . $yearPeriod);

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getStyle("A1:D1")->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle("A2:D2")->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle("A4:G4")->getFont()->setBold(true)->setSize(12);

        $sheet->mergeCells("A1:I1");
        $sheet->mergeCells("A2:I2");
        $sheet->mergeCells("A4:I4");

        /* HEADER WARNA */
        $sheet->getStyle("A6:I7")
            ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('F2BE6B');

        $sheet->setCellValue('A6', 'NO')
            ->setCellValue('B6', 'NAMA')
            ->setCellValue('C6', 'ID')
            ->setCellValue('D6', 'CLASS')
            ->setCellValue('E6', 'NO REKENING')
            ->setCellValue('F6', 'NAMA REKENING')
            ->setCellValue('G6', 'JUMLAH')
            ->setCellValue('H6', 'CODE BANK')
            ->setCellValue('I6', 'BANK');

        $sheet->getStyle("A6:I6")->getFont()->setSize(12);
        $sheet->getStyle("A6:I6")->applyFromArray($styles['outlineBorderStyle']);

        // Border seluruh kolom header
        foreach (range('A', 'I') as $col) {
            $sheet->mergeCells($col . "6:" . $col . "7");

            $sheet->getStyle($col . "6:" . $col . "7")->applyFromArray($styles['allBorderStyle']);
            $sheet->getStyle($col . "6:" . $col . "7")->applyFromArray($styles['center']);
            $sheet->getStyle($col . "6:" . $col . "7")->getAlignment()->setWrapText(true);
        }

        $sheet->getStyle("A1:I4")->applyFromArray($styles['center']);

        $rowIdx = 8;
        $rowNo = 0;
        $ttotalTerima = 0;

        // ================================
        // GET PAYMENT LIST DATA
        // ================================
        $mSalarySlip = new SalarySlip();
        $dataPaymentResults = $mSalarySlip
            ->getInvoicePaymentList($clientName, $yearPeriod, $monthPeriod, $dataGroup);

        foreach ($dataPaymentResults as $row) {

            $rowIdx++;
            $rowNo++;

            // Gaji dasar
            $terima = $row->brutto
                - $row->tax_val
                - $row->jkkjkm
                - $row->emp_jht
                - $row->emp_bpjs
                - $row->emp_jp
                - $row->bpjs;

            // Allowance
            $allowanceModel = new MtAllowance();
            $allowanceRaw = $allowanceModel->selectAllowanceAll($row->biodata_id, $clientName, $yearPeriod, $monthPeriod, '');
            $allowanceData = [];
            foreach ($allowanceRaw as $allowance) {
                $allowanceData[$allowance['allowance_name']] = $allowance['allowance_amount'];
            }
            $workdayAdj = $allowanceData['workday_adjustment'] ?? 0;
            $debtBurden = $allowanceData['debt_burden'] ?? 0;
            $thrByUser = $allowanceData['thr_by_user'] ?? 0;


            $thp = $terima + $workdayAdj + $debtBurden + $thrByUser;
            $thp = round($thp, 2);

            $pembulatan = ConfigurationHelper::pembulatanTotal($thp);
            $totalTerima = $thp + $pembulatan;

            // Formatting rekening as string
            $sheet->getStyle('E' . $rowIdx)
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

            // Isi row
            $sheet->setCellValue('A' . $rowIdx, $rowNo)
                ->setCellValue('B' . $rowIdx, $row->full_name)
                ->setCellValueExplicit('C' . $rowIdx, $row->biodata_id, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValue('D' . $rowIdx, $row->emp_position)
                ->setCellValueExplicit('E' . $rowIdx, $row->account_no, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValue('F' . $rowIdx, $row->account_name)
                ->setCellValue('G' . $rowIdx, $totalTerima)
                ->setCellValue('H' . $rowIdx, $row->bank_code)
                ->setCellValue('I' . $rowIdx, $row->bank_name);

            // Row coloring
            if ($rowIdx % 2 == 1) {
                $sheet->getStyle('A' . $rowIdx . ':I' . $rowIdx)
                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('EAEBAF');
            }

            $ttotalTerima += $totalTerima;
        }

        // TOTAL
        $sheet->setCellValue('F' . ($rowIdx + 2), 'JUMLAH')
            ->setCellValue('G' . ($rowIdx + 2), '=SUM(G9:G' . ($rowIdx + 1) . ')');

        $sheet->getStyle("F" . ($rowIdx + 2) . ":G" . ($rowIdx + 2))
            ->getFont()->setBold(true)->setSize(12);

        $sheet->getStyle("A" . ($rowIdx + 2) . ":I" . ($rowIdx + 2))
            ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('F2BE6B');

        // Number format
        $sheet->getStyle('G8:G' . ($rowIdx + 2))
            ->getNumberFormat()->setFormatCode('#,##0');

        // Sheet Title & Filename
        $sheet->setTitle('Report Excel ' . date('d-m-Y H'));
        $spreadsheet->setActiveSheetIndex(0);

        $fileName = 'PaymentList_' . $clientName . '_' . $dataGroup . '-' . $monthPeriod . '.xlsx';
        return preg_replace('/\s+/', '', $fileName);
    }
}
