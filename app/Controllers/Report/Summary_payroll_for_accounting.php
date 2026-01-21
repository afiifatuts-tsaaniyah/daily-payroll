<?php

namespace App\Controllers\Report;

use App\Models\Transaction\M_rp_db;
use App\Controllers\BaseController;
use App\Controllers\Transaction\Spreadsheet_template_controller;
use App\Helpers\ConfigurationHelper;
use App\Helpers\ResponseFormatter;
use App\Models\Master\Client;
use App\Models\Master\M_dept;
use App\Models\Master\MtAllowance;
use App\Models\Transaction\SalarySlip;


class Summary_payroll_for_accounting extends BaseController
{

    public function exportSumarryPayrollForAccountingPromincon2($clientName, $yearPeriod, $monthPeriod, $dataGroup, $spreadsheet, $dataSummaryPayroll)
    {
        $styles = Spreadsheet_template_controller::getStyles();
        $allBorderStyle = $styles['allBorderStyle'];
        $center = $styles['center'];
        $lastColumn = 'AT';


        $spreadsheet->getActiveSheet()->getStyle('A6:' . $lastColumn . '7')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B');

        $queryClient = Client::clientDisplay($clientName);
        $clientDisplay = $queryClient['clientDisplay'];



        $spreadsheet->getActiveSheet()
            ->setCellValue('A1', 'PT. SANGATI SOERYA SEJAHTERA')
            ->setCellValue('A2', 'SUMMARY PAYROLL PT. ' . $clientDisplay . ' (' . $dataGroup . ')')
            ->setCellValue('A4', 'Periode : ' . $monthPeriod . '-' . $yearPeriod);

        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true)->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle("A2:D2")->getFont()->setBold(true)->setSize(14)->setUnderline(true);
        $spreadsheet->getActiveSheet()->getStyle("A4:G4")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("A6:" . $lastColumn . "7")->getFont()->setBold(true)->setSize(12);

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

        $spreadsheet->getActiveSheet()->mergeCells("R6:V6"); //regular income
        $spreadsheet->getActiveSheet()->mergeCells("W6:Y6"); // non regular income

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

        $spreadsheet->getActiveSheet()->getStyle("A6:" . $lastColumn . "7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A6:" . $lastColumn . "7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()
            ->setCellValue('A6', 'NO')
            ->setCellValue('B6', 'YEAR')
            ->setCellValue('C6', 'MONTH')
            ->setCellValue('D6', 'DEPARTEMENT')
            ->setCellValue('E6', 'INTERNAL ID')
            ->setCellValue('F6', 'NAMA')
            ->setCellValue('G6', 'GENDER')
            ->setCellValue('H6', 'MARITAL STATUS')
            ->setCellValue('I6', 'NPWP')
            ->setCellValue('J6', 'NIK')
            ->setCellValue('K6', 'POSITION')
            ->setCellValue('L6', 'ADDRESS')
            ->setCellValue('M6', 'BASIC SALARY')

            ->setCellValue('N6', 'OT 1')
            ->setCellValue('O6', 'OT 2')
            ->setCellValue('P6', 'OT 3')
            ->setCellValue('Q6', 'OT 4')

            ->setCellValue('R6', 'REGULER INCOME')
            ->setCellValue('R7', 'SALARY')
            ->setCellValue('S7', 'OVERTIME TOTAL')
            ->setCellValue('T7', 'ATTENDANCE BONUS')
            ->setCellValue('U7', 'NIGHT SHIFT')
            ->setCellValue('V7', 'TRANSPORT')

            ->setCellValue('W6', 'NON REGULER INCOME')
            ->setCellValue('W7', 'THR')
            ->setCellValue('X7', 'TUNJANGAN')
            ->setCellValue('Y7', 'OTHER ALLOWANCE')
            ->setCellValue('Z6', 'JKK & JKM')
            ->setCellValue('AA6', 'HEALTH BPJS')
            ->setCellValue('AB6', 'UNPAID TOTAL')
            ->setCellValue('AC6', 'ADJUSTMENT IN')
            ->setCellValue('AD6', 'ADJUSTMENT OUT')
            ->setCellValue('AE6', 'GROSS')
            ->setCellValue('AF6', 'GOLONGAN')
            ->setCellValue('AG6', 'PERSENTASE')
            // ->setCellValue('AC6', 'TAX REGULER')
            // ->setCellValue('AD6', 'TAX PENALTY')
            ->setCellValue('AH6', 'TAX PPH21')
            ->setCellValue('AI6', 'JKK & JKM')
            ->setCellValue('AJ6', 'EMP JHT')
            ->setCellValue('AK6', 'HEALTH BPJS')
            ->setCellValue('AL6', 'EMP HEALTH BPJS')
            ->setCellValue('AM6', 'EMP JP')
            ->setCellValue('AN6', 'THR')
            ->setCellValue('AO6', 'CONTRACT BONUS')
            ->setCellValue('AP6', 'ADJUSTMENT')
            ->setCellValue('AQ6', 'DEBT BURDEN')
            ->setCellValue('AR6', 'PAYMENT')
            ->setCellValue('AS6', 'ROUNDED')
            ->setCellValue('AT6', 'NET PAYMENT');

        /* START GET DAYS TOTAL BY ROSTER */
        $rowIdx = 7;
        $rowNo = 0;
        foreach ($dataSummaryPayroll as $row) {
            $rowIdx++;
            $rowNo++;

            $yearPeriod = $row->year_period;
            $monthPeriod = $row->month_period;
            $nie = $row->biodata_id;
            $biodataId = $row->biodata_id;
            $fullName = $row->full_name;
            $gender = $row->gender;
            $maritalStatus = ConfigurationHelper::maritalFormat($row->marital_status);
            $npwpNo = $row->npwp_no;
            $idCardNo = $row->id_card_no;
            $idCardAddress = $row->id_card_address;
            $position = $row->position;
            $basicSalary = $row->salary_basic;
            // $bsProrate = $row->bs_prorate;
            $bsProrate = $row->bs_prorate;

            $otTotal = $row->ot_total;
            // $thr = $row->thr;
            $jkkJkm = $row->jkkjkm;
            $healthBpjs = $row->bpjs;
            $empHealthBpjs = $row->emp_bpjs;
            $unpaidTotal = $row->unpaid;
            // $adjustIn = $row->adjust_in;
            // $adjustOut = $row->adjust_out;
            $brutto = $row->brutto;
            $healthBpjs = $row->bpjs;
            $empJht = $row->empJht;
            $empJp = $row->empJp;
            $otCount1 = $row->ot_count1;
            $otCount2 = $row->ot_count2;
            $otCount3 = $row->ot_count3;
            $otCount4 = $row->ot_count4;
            //Allowance

            $penalty = $row->tax_pinalty;
            $terGolongan = $row->golongan;
            $isEnd = $row->is_end;
            $terPersen = $row->tarif;
            $taxFinal = $row->tax_val;
            $payrollGroup = $row->payroll_group;

            $allowanceModel = new MtAllowance();
            $allowanceData = $allowanceModel->selectAllowanceAll($biodataId, $clientName, $yearPeriod, $monthPeriod, '');
            $allowances = [];
            foreach ($allowanceData as $item) {
                $allowances[$item['allowance_name']] = $item['allowance_amount'];
            }

            // Contoh ambil thr
            $thr = $allowances['thr'] ?? 0;
            $attendanceBonus = $allowances['attendance_bonus'] ?? 0;
            $transportBonus = $allowances['transport_bonus'] ?? 0;
            $nightShiftBonus = $allowances['night_shift_bonus'] ?? 0;
            $tunjangan = $allowances['tunjangan'] ?? 0;
            $debtBurden = $allowances['debt_burden'] ?? 0;
            $adjustIn = $allowances['adjustment_in'] ?? 0;
            $adjustOut = $allowances['adjustment_out'] ?? 0;
            $workdayAdj = $allowances['workday_adjustment'] ?? 0;
            $thrByUser = $allowances['thr_by_user'] ?? 0;


            $checkFinal = 0;

            if ($isEnd == '1') {
                $checkFinal = 0;
            } else if ($monthPeriod == '12' && $isEnd == '1') {
                $checkFinal = 0;
            } else if ($monthPeriod == '12' && ($isEnd == '' || $isEnd == 0)) {
                $checkFinal = 1;
            }

            if ($checkFinal == 1) {
                $terGolongan = 'FINAL';
            }

            $spreadsheet->getActiveSheet()
                ->getStyle('J' . $rowIdx)
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . ($rowIdx), $rowNo)
                ->setCellValue('B' . ($rowIdx), $yearPeriod)
                ->setCellValue('C' . ($rowIdx), $monthPeriod)
                ->setCellValue('D' . ($rowIdx), $payrollGroup)
                ->setCellValue('E' . ($rowIdx), $nie)
                ->setCellValue('F' . ($rowIdx), str_replace('\\', '', $fullName))
                ->setCellValue('G' . ($rowIdx), $gender)
                ->setCellValue('H' . ($rowIdx), $maritalStatus)
                ->setCellValueExplicit('I' . $rowIdx, $npwpNo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('J' . $rowIdx, $idCardNo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValue('K' . ($rowIdx), $position)
                ->setCellValue('L' . ($rowIdx), $idCardAddress)
                ->setCellValue('M' . ($rowIdx), $basicSalary)

                ->setCellValue('N' . ($rowIdx), $otCount1)
                ->setCellValue('O' . ($rowIdx), $otCount2)
                ->setCellValue('P' . ($rowIdx), $otCount3)
                ->setCellValue('Q' . ($rowIdx), $otCount4)

                ->setCellValue('R' . ($rowIdx), $bsProrate)
                ->setCellValue('S' . ($rowIdx), $otTotal)
                ->setCellValue('T' . ($rowIdx), $attendanceBonus)
                ->setCellValue('U' . ($rowIdx), $nightShiftBonus)
                ->setCellValue('V' . ($rowIdx), $transportBonus)
                ->setCellValue('W' . ($rowIdx), $thr)
                ->setCellValue('X' . ($rowIdx), $tunjangan)
                ->setCellValue('Y' . ($rowIdx), 0)
                ->setCellValue('Z' . ($rowIdx), $jkkJkm)
                ->setCellValue('AA' . ($rowIdx), $healthBpjs)
                ->setCellValue('AB' . ($rowIdx), - ($unpaidTotal))
                ->setCellValue('AC' . ($rowIdx), $adjustIn)
                ->setCellValue('AD' . ($rowIdx), ($adjustOut))
                ->setCellValue('AE' . ($rowIdx), $brutto)
                ->setCellValue('AF' . ($rowIdx), $terGolongan)
                ->setCellValueExplicit('AG' . ($rowIdx), ($terPersen / 100), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                // ->setCellValue('AC' . ($rowIdx), - ($taxValue))
                // ->setCellValue('AD' . ($rowIdx), - ($penalty))
                ->setCellValue('AH' . ($rowIdx), - ($taxFinal))
                ->setCellValue('AI' . ($rowIdx), - ($jkkJkm))
                ->setCellValue('AJ' . ($rowIdx), - ($empJht))
                ->setCellValue('AK' . ($rowIdx), - ($healthBpjs))
                ->setCellValue('AL' . ($rowIdx), - ($empHealthBpjs))
                ->setCellValue('AM' . ($rowIdx), - ($empJp))
                ->setCellValue('AN' . ($rowIdx), ($thrByUser))
                ->setCellValue('AO' . ($rowIdx), 0)
                ->setCellValue('AP' . ($rowIdx), $workdayAdj)
                ->setCellValue('AQ' . ($rowIdx), ($debtBurden))
                ->setCellValue('AR' . $rowIdx, '=SUM(AE' . $rowIdx . ',AH' . $rowIdx . ':AQ' . $rowIdx . ')');

            $someNumericTotal = $spreadsheet->getActiveSheet()->getCell('AR' . $rowIdx)->getCalculatedValue();

            $spreadsheet->getActiveSheet()->setCellValue('AS' . $rowIdx, ConfigurationHelper::PembulatanTotal($someNumericTotal))
                ->setCellValue('AT' . $rowIdx, '=SUM(AR' . $rowIdx . ':AS' . $rowIdx . ')');

            /* SET ROW COLOR */
            if ($rowIdx % 2 == 1) {
                $spreadsheet->getActiveSheet()->getStyle('A' . $rowIdx . ':' . $lastColumn . '' . $rowIdx)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('EAEBAF');
            }
        }

        $spreadsheet->getActiveSheet()->getStyle("L8:" . $lastColumn . "" . ($rowIdx))->getNumberFormat()->setFormatCode('#,##0.00');
        // $spreadsheet->getActiveSheet()->getStyle("L8:AP" . ($rowIdx))->getNumberFormat()->setFormatCode('#,##0');
        $spreadsheet->getActiveSheet()->getStyle('AG8:AG' . ($rowIdx))->getNumberFormat()->setFormatCode('0.00%');
        $spreadsheet->getActiveSheet()->getStyle("A6:" . $lastColumn . "" . ($rowIdx))->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getColumnDimension('' . $lastColumn . '')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->setTitle('Report Excel ' . date('d-m-Y H'));

        $spreadsheet->setActiveSheetIndex(0);

        $str = 'SummaryPayroll_' . $clientName . '-' . $dataGroup . '-' . $monthPeriod;
        $fileName = preg_replace('/\s+/', '', $str) . '.xlsx';
        return $fileName;
    }


    public function exportSumarryPayrollForAccountingPromincon(
        $clientName,
        $yearPeriod,
        $monthPeriod,
        $dataGroup,
        $spreadsheet,
        $dataSummaryPayroll
    ) {
        $styles = Spreadsheet_template_controller::getStyles();
        $allBorderStyle = $styles['allBorderStyle'];
        $center = $styles['center'];
        $lastColumn = 'AT';


        $spreadsheet->getActiveSheet()->getStyle('A6:' . $lastColumn . '7')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('F2BE6B');

        $queryClient = Client::clientDisplay($clientName);
        $clientDisplay = $queryClient['clientDisplay'];


        // Header
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1', 'PT. SANGATI SOERYA SEJAHTERA')
            ->setCellValue('A2', 'SUMMARY PAYROLL PT. ' . $clientDisplay . ' (' . $dataGroup . ')')
            ->setCellValue('A4', 'Periode : ' . $monthPeriod . '-' . $yearPeriod);

        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true)->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle("A2:D2")->getFont()->setBold(true)->setSize(14)->setUnderline(true);
        $spreadsheet->getActiveSheet()->getStyle("A4:G4")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("A6:" . $lastColumn . "7")->getFont()->setBold(true)->setSize(12);

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

        $spreadsheet->getActiveSheet()->mergeCells("R6:V6"); //regular income
        $spreadsheet->getActiveSheet()->mergeCells("W6:Y6"); // non regular income

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

        $spreadsheet->getActiveSheet()->getStyle("A6:" . $lastColumn . "7")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A6:" . $lastColumn . "7")->applyFromArray($center);
        $spreadsheet->getActiveSheet()
            ->setCellValue('A6', 'NO')
            ->setCellValue('B6', 'YEAR')
            ->setCellValue('C6', 'MONTH')
            ->setCellValue('D6', 'DEPARTEMENT')
            ->setCellValue('E6', 'INTERNAL ID')
            ->setCellValue('F6', 'NAMA')
            ->setCellValue('G6', 'GENDER')
            ->setCellValue('H6', 'MARITAL STATUS')
            ->setCellValue('I6', 'NPWP')
            ->setCellValue('J6', 'NIK')
            ->setCellValue('K6', 'POSITION')
            ->setCellValue('L6', 'ADDRESS')
            ->setCellValue('M6', 'BASIC SALARY')

            ->setCellValue('N6', 'OT 1')
            ->setCellValue('O6', 'OT 2')
            ->setCellValue('P6', 'OT 3')
            ->setCellValue('Q6', 'OT 4')

            ->setCellValue('R6', 'REGULER INCOME')
            ->setCellValue('R7', 'SALARY')
            ->setCellValue('S7', 'OVERTIME TOTAL')
            ->setCellValue('T7', 'ATTENDANCE BONUS')
            ->setCellValue('U7', 'NIGHT SHIFT')
            ->setCellValue('V7', 'TRANSPORT')

            ->setCellValue('W6', 'NON REGULER INCOME')
            ->setCellValue('W7', 'THR')
            ->setCellValue('X7', 'TUNJANGAN')
            ->setCellValue('Y7', 'OTHER ALLOWANCE')
            ->setCellValue('Z6', 'JKK & JKM')
            ->setCellValue('AA6', 'HEALTH BPJS')
            ->setCellValue('AB6', 'UNPAID TOTAL')
            ->setCellValue('AC6', 'ADJUSTMENT IN')
            ->setCellValue('AD6', 'ADJUSTMENT OUT')
            ->setCellValue('AE6', 'GROSS')
            ->setCellValue('AF6', 'GOLONGAN')
            ->setCellValue('AG6', 'PERSENTASE')
            // ->setCellValue('AC6', 'TAX REGULER')
            // ->setCellValue('AD6', 'TAX PENALTY')
            ->setCellValue('AH6', 'TAX PPH21')
            ->setCellValue('AI6', 'JKK & JKM')
            ->setCellValue('AJ6', 'EMP JHT')
            ->setCellValue('AK6', 'HEALTH BPJS')
            ->setCellValue('AL6', 'EMP HEALTH BPJS')
            ->setCellValue('AM6', 'EMP JP')
            ->setCellValue('AN6', 'THR')
            ->setCellValue('AO6', 'CONTRACT BONUS')
            ->setCellValue('AP6', 'ADJUSTMENT')
            ->setCellValue('AQ6', 'DEBT BURDEN')
            ->setCellValue('AR6', 'PAYMENT')
            ->setCellValue('AS6', 'ROUNDED')
            ->setCellValue('AT6', 'NET PAYMENT');

        /* ==== LOOP DATA ===== */
        $rowIdx = 7;
        $rowNo = 0;
        $mSalarySlip = new SalarySlip();

        foreach ($dataSummaryPayroll as $rowBio) {

            // ===== AMBIL DETAIL =====
            $row = $mSalarySlip
                ->getListSPByBioRecId(
                    $rowBio->biodata_id,
                    $clientName,
                    $yearPeriod,
                    $monthPeriod,
                    $dataGroup
                );
            $rowIdx++;
            $rowNo++;

            if ($row) {
                $rowIdx++;
                $rowNo++;

                $yearPeriod = $row->year_period;
                $monthPeriod = $row->month_period;
                $nie = $row->biodata_id;
                $biodataId = $row->biodata_id;
                $fullName = $row->full_name;
                $gender = $row->gender;
                $maritalStatus = ConfigurationHelper::maritalFormat($row->marital_status);
                $npwpNo = '';
                $idCardNo = $row->id_card_no;
                $idCardAddress = $row->id_card_address;
                $position = $row->position;
                $basicSalary = $row->salary_basic;
                // $bsProrate = $row->bs_prorate;
                $bsProrate = $row->bs_prorate;

                $otTotal = $row->ot_total;
                // $thr = $row->thr;
                $jkkJkm = $row->jkkjkm;
                $healthBpjs = $row->bpjs;
                $empHealthBpjs = $row->emp_bpjs;
                $unpaidTotal = $row->unpaid;
                // $adjustIn = $row->adjust_in;
                // $adjustOut = $row->adjust_out;
                $brutto = $row->brutto;
                $healthBpjs = $row->bpjs;
                $empJht = $row->empJht;
                $empJp = $row->empJp;
                $otCount1 = $row->ot_count1;
                $otCount2 = $row->ot_count2;
                $otCount3 = $row->ot_count3;
                $otCount4 = $row->ot_count4;
                //Allowance

                $penalty = $row->tax_pinalty;
                $terGolongan = $row->golongan;
                $isEnd = $row->is_end;
                $terPersen = $row->tarif;
                $taxFinal = $row->tax_val;
                $payrollGroup = $row->payroll_group;

                $allowanceModel = new MtAllowance();
                $allowanceData = $allowanceModel->selectAllowanceAll($biodataId, $clientName, $yearPeriod, $monthPeriod, '');
                $allowances = [];
                foreach ($allowanceData as $item) {
                    $allowances[$item['allowance_name']] = $item['allowance_amount'];
                }

                // Contoh ambil thr
                $thr = $allowances['thr'] ?? 0;
                $attendanceBonus = $allowances['attendance_bonus'] ?? 0;
                $transportBonus = $allowances['transport_bonus'] ?? 0;
                $nightShiftBonus = $allowances['night_shift_bonus'] ?? 0;
                $tunjangan = $allowances['tunjangan'] ?? 0;
                $debtBurden = $allowances['debt_burden'] ?? 0;
                $adjustIn = $allowances['adjustment_in'] ?? 0;
                $adjustOut = $allowances['adjustment_out'] ?? 0;
                $workdayAdj = $allowances['workday_adjustment'] ?? 0;
                $thrByUser = $allowances['thr_by_user'] ?? 0;


                $checkFinal = 0;

                if ($isEnd == '1') {
                    $checkFinal = 0;
                } else if ($monthPeriod == '12' && $isEnd == '1') {
                    $checkFinal = 0;
                } else if ($monthPeriod == '12' && ($isEnd == '' || $isEnd == 0)) {
                    $checkFinal = 1;
                }

                if ($checkFinal == 1) {
                    $terGolongan = 'FINAL';
                }

                $spreadsheet->getActiveSheet()
                    ->getStyle('J' . $rowIdx)
                    ->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

                $spreadsheet->getActiveSheet()
                    ->setCellValue('A' . ($rowIdx), $rowNo)
                    ->setCellValue('B' . ($rowIdx), $yearPeriod)
                    ->setCellValue('C' . ($rowIdx), $monthPeriod)
                    ->setCellValue('D' . ($rowIdx), $payrollGroup)
                    ->setCellValue('E' . ($rowIdx), $nie)
                    ->setCellValue('F' . ($rowIdx), str_replace('\\', '', $fullName))
                    ->setCellValue('G' . ($rowIdx), $gender)
                    ->setCellValue('H' . ($rowIdx), $maritalStatus)
                    ->setCellValueExplicit('I' . $rowIdx, $npwpNo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValueExplicit('J' . $rowIdx, $idCardNo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValue('K' . ($rowIdx), $position)
                    ->setCellValue('L' . ($rowIdx), $idCardAddress)
                    ->setCellValue('M' . ($rowIdx), $basicSalary)

                    ->setCellValue('N' . ($rowIdx), $otCount1)
                    ->setCellValue('O' . ($rowIdx), $otCount2)
                    ->setCellValue('P' . ($rowIdx), $otCount3)
                    ->setCellValue('Q' . ($rowIdx), $otCount4)

                    ->setCellValue('R' . ($rowIdx), $bsProrate)
                    ->setCellValue('S' . ($rowIdx), $otTotal)
                    ->setCellValue('T' . ($rowIdx), $attendanceBonus)
                    ->setCellValue('U' . ($rowIdx), $nightShiftBonus)
                    ->setCellValue('V' . ($rowIdx), $transportBonus)
                    ->setCellValue('W' . ($rowIdx), $thr)
                    ->setCellValue('X' . ($rowIdx), $tunjangan)
                    ->setCellValue('Y' . ($rowIdx), 0)
                    ->setCellValue('Z' . ($rowIdx), $jkkJkm)
                    ->setCellValue('AA' . ($rowIdx), $healthBpjs)
                    ->setCellValue('AB' . ($rowIdx), - ($unpaidTotal))
                    ->setCellValue('AC' . ($rowIdx), $adjustIn)
                    ->setCellValue('AD' . ($rowIdx), ($adjustOut))
                    ->setCellValue('AE' . ($rowIdx), $brutto)
                    ->setCellValue('AF' . ($rowIdx), $terGolongan)
                    ->setCellValueExplicit('AG' . ($rowIdx), ($terPersen / 100), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                    // ->setCellValue('AC' . ($rowIdx), - ($taxValue))
                    // ->setCellValue('AD' . ($rowIdx), - ($penalty))
                    ->setCellValue('AH' . ($rowIdx), - ($taxFinal))
                    ->setCellValue('AI' . ($rowIdx), - ($jkkJkm))
                    ->setCellValue('AJ' . ($rowIdx), - ($empJht))
                    ->setCellValue('AK' . ($rowIdx), - ($healthBpjs))
                    ->setCellValue('AL' . ($rowIdx), - ($empHealthBpjs))
                    ->setCellValue('AM' . ($rowIdx), - ($empJp))
                    ->setCellValue('AN' . ($rowIdx), ($thrByUser))
                    ->setCellValue('AO' . ($rowIdx), 0)
                    ->setCellValue('AP' . ($rowIdx), $workdayAdj)
                    ->setCellValue('AQ' . ($rowIdx), ($debtBurden))
                    ->setCellValue('AR' . $rowIdx, '=SUM(AE' . $rowIdx . ',AH' . $rowIdx . ':AQ' . $rowIdx . ')');

                $someNumericTotal = $spreadsheet->getActiveSheet()->getCell('AR' . $rowIdx)->getCalculatedValue();

                $spreadsheet->getActiveSheet()->setCellValue('AS' . $rowIdx, ConfigurationHelper::PembulatanTotal($someNumericTotal))
                    ->setCellValue('AT' . $rowIdx, '=SUM(AR' . $rowIdx . ':AS' . $rowIdx . ')');

                /* SET ROW COLOR */
                if ($rowIdx % 2 == 1) {
                    $spreadsheet->getActiveSheet()->getStyle('A' . $rowIdx . ':' . $lastColumn . '' . $rowIdx)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('EAEBAF');
                }
            } else {
                $rowBiodata = $mSalarySlip->getListBiodataSPById($rowBio->biodata_id);

                $spreadsheet->getActiveSheet()
                    ->getStyle('K' . $rowIdx)
                    ->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

                $spreadsheet->getActiveSheet()
                    ->setCellValue('A' . ($rowIdx), $rowNo)
                    ->setCellValue('B' . ($rowIdx), $yearPeriod)
                    ->setCellValue('C' . ($rowIdx), $monthPeriod)
                    ->setCellValue('D' . ($rowIdx), $rowBiodata->payroll_group)
                    ->setCellValue('E' . ($rowIdx), $rowBiodata->nie)
                    ->setCellValue('F' . ($rowIdx), str_replace('\\', '', $rowBiodata->full_name))
                    ->setCellValue('G' . ($rowIdx), $rowBiodata->gender)
                    ->setCellValue('H' . ($rowIdx), $rowBiodata->marital_status)
                    ->setCellValue('I' . ($rowIdx), $rowBiodata->npwp_no)
                    ->setCellValueExplicit('J' . ($rowIdx), $rowBiodata->id_card_no, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                    ->setCellValue('K' . $rowIdx, '')
                    ->setCellValue('L' . ($rowIdx), $rowBiodata->position)
                    ->setCellValue('M' . ($rowIdx), $rowBiodata->current_address)
                    ->setCellValue('N' . ($rowIdx), $rowBiodata->basic_salary);

                for ($i = ord('O'); $i <= ord('Z'); $i++) {
                    $letter = chr($i);
                    $spreadsheet->getActiveSheet()->setCellValue(($letter) . ($rowIdx), 0);
                }

                for ($i = ord('A'); $i <= ord('Q'); $i++) {
                    $letter = chr($i);
                    $spreadsheet->getActiveSheet()->setCellValue("A" . ($letter) . ($rowIdx), 0);
                }
            }

            /* SET ROW COLOR */
            if ($rowIdx % 2 == 1) {
                $spreadsheet->getActiveSheet()->getStyle('A' . $rowIdx . ':' . ($lastColumn . $rowIdx))
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('EAEBAF');
            }

            if (!isset($row)) {
                $spreadsheet->getActiveSheet()->getStyle('A' . $rowIdx . ':' . ($lastColumn . $rowIdx))
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('DB8CFF');
            }
        }

        $spreadsheet->getActiveSheet()->getStyle("L8:" . $lastColumn . "" . ($rowIdx))->getNumberFormat()->setFormatCode('#,##0.00');
        // $spreadsheet->getActiveSheet()->getStyle("L8:AP" . ($rowIdx))->getNumberFormat()->setFormatCode('#,##0');
        $spreadsheet->getActiveSheet()->getStyle('AG8:AG' . ($rowIdx))->getNumberFormat()->setFormatCode('0.00%');
        $spreadsheet->getActiveSheet()->getStyle("A6:" . $lastColumn . "" . ($rowIdx))->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getColumnDimension('' . $lastColumn . '')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->setTitle('Report Excel ' . date('d-m-Y H'));

        $spreadsheet->setActiveSheetIndex(0);

        $str = 'SummaryPayroll_AccountingVer_PT_' . $clientName . '-' . $dataGroup . '-' . $monthPeriod;
        $fileName = preg_replace('/\s+/', '', $str) . '.xlsx';
        return $fileName;
    }
}
