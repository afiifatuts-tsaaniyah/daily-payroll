<?php

namespace App\Controllers\Transaction;

use App\Controllers\BaseController;
use App\Models\Master\Config;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use App\Models\Transaction\M_download;
use App\Models\Master\M_mt_biodata;


class Gov_regulation extends BaseController
{
    protected $mPayrollConfig;

    public function __construct()
    {
        $this->mPayrollConfig = new Config();
    }

    public  function calculateGovernmentRegulation(
        $clientName,
        $basicSalary,
        $maritalStatus,
        $isHealthBPJS,
        $isJHT,
        $isJP,
        $isJKKM
    ) {
        /* This function is for goverment regulation such as
        - NonTaxAllowance =  Nilai Tunjangan Non Pajak
        - TotalPTKP = Penghasilan tidak kena pajak
        - HealthBpjs = BPJS Kesehatan
        - EmpHealthBpjs = BPJS Kesehatan TK
        - JkkJkm = Jaminan Kecelakaan dan Jaminan Kematian
        - Jht = Jaminan Hari Tua
        - EmpJht = Jaminan Hari Tua / JHT BPJS TK
        - Jp = Jaminan Pensiun
        - EmpJp = Jaminan Pensiun TK

        */

        /* MAKE SURE DATA SUDAH ADA DI CONFIG AGAR TIDAK ERROR */
        $payrollConfig = $this->mPayrollConfig->loadByClient($clientName);
        /* END GET CONFIG FROM mst_payroll_config TABLE */

        $nonTaxAllowance = 0;
        /* START GOVERNMENT REGULATION */

        // START POSITIONAL ALLOWANCE/ NON TAX
        $nonTaxAllowance = $payrollConfig['non_tax_allowance'];/* Nilai Tunjangan Non Pajak */
        // var_dump($nonTaxAllowance);
        // exit();
        $nonTaxTmp = $basicSalary * (5 / 100);
        if ($nonTaxTmp < $nonTaxAllowance) {
            $nonTaxAllowance = $nonTaxTmp;
        }


        $ptkpBasic = $payrollConfig['ptkp']; /* Nominal PTKP */
        $ptkpDependent = $payrollConfig['ptkp_dependent']; /* Nominal PTKP Tanggungan Per Orang */
        /* START GET PTKP TOTAL  */
        $ptkpMultiplier = 0;
        switch ($maritalStatus) {
            //Tidak kawin
            case 'TK0':
                $ptkpMultiplier = 0;
                break;
            case 'TK1':
                $ptkpMultiplier = 1;
                break;
            case 'TK2':
                $ptkpMultiplier = 2;
                break;
            case 'TK3':
                $ptkpMultiplier = 3;
                break;
            //Kawin
            case 'K0':
                $ptkpMultiplier = 1;
                break;
            case 'K1':
                $ptkpMultiplier = 2;
                break;
            case 'K2':
                $ptkpMultiplier = 3;
                break;
            case 'K3':
                $ptkpMultiplier = 4;
                break;
            //Kawin Penghasilan Istri Digabung dengan Suami
            // case 'KI0':
            //     $ptkpMultiplier = 13;
            //     break;
            // case 'KI1':
            //     $ptkpMultiplier = 14;
            //     break;
            // case 'KI2':
            //     $ptkpMultiplier = 15;
            //     break;
            // case 'KI3':
            //     $ptkpMultiplier = 16;
            //     break;

            default:
                $ptkpMultiplier = 0;
                break;
        }

        $totalPTKP = $ptkpBasic + ($ptkpDependent * $ptkpMultiplier);

        /* START HEALTH BPJS */
        $healthBpjsConfig = $payrollConfig['health_bpjs'];/* Health BPJS Config Values */
        $maxHealthBpjsConfig = $payrollConfig['max_health_bpjs'];
        $healthBpjs = ($healthBpjsConfig / 100) * $basicSalary; /* Nilai mst_payroll_config.health_bpjs(%) x Basic Salary (Max 8 Juta) */
        if ($healthBpjs > $maxHealthBpjsConfig) {
            $healthBpjs = $maxHealthBpjsConfig;
        }
        /* END HEALTH BPJS */

        /* START JKK-JKM */
        $jkkJkmConfig = $payrollConfig['jkk_jkm']; /* jkkJkm Config Values */
        $jkkJkm = ($jkkJkmConfig / 100) * $basicSalary; /* Nilai mst_payroll_config.jkk_jkm(%) x Basic Salary */
        /* END JKK-JKM */

        /* START JHT */
        $jhtConfig = $payrollConfig['jht']; /* jht Config Values */
        $jht = ($jhtConfig / 100) * $basicSalary; /* Nilai mst_payroll_config.jht(%) x Basic Salary */
        /* END JHT */

        /* START JHT */
        $empJhtConfig = $payrollConfig['emp_jht'];/* empJht Config Values */
        $empJht = ($empJhtConfig / 100) * $basicSalary; /* Nilai mst_payroll_config.emp_jht(%) x Basic Salary */
        /* END JHT */

        /* START JP COMPANY */
        $jpConfig = $payrollConfig['jp'];
        $maxJp = $payrollConfig['max_jp']; /* Nilai Max Untuk Iuran JP Perusahaan  */
        $jp = ($jpConfig / 100) * $basicSalary; /* Nilai mst_payroll_config.jp(%) x Basic Salary */
        if ($jp > $maxJp) {
            $jp = $maxJp;
        }
        /* END JP COMPANY */

        /* START JP EMPLOYEE */
        $empJpConfig = $payrollConfig['emp_jp'];
        $maxEmpJp = $payrollConfig['max_emp_jp']; /* Nilai Max Untuk Iuran JP Karyawan  */
        $empJp = ($empJpConfig / 100) * $basicSalary; /* Nilai mst_payroll_config.emp_jp(%) x Basic Salary */
        if ($empJp > $maxEmpJp) {
            $empJp = $maxEmpJp;
        }
        /* END JP EMPLOYEE */

        /* START BPJS KARYAWAN */
        $empHealthBpjsConfig = $payrollConfig['emp_health_bpjs'];/* empHealthBpjs Config Values */
        $maxEmpHealthBpjs = $payrollConfig['max_emp_bpjs']; /* Nilai Max Gaji Untuk Iuran BPJS Karyawan  */
        $empHealthBpjs = ($empHealthBpjsConfig / 100) * $basicSalary; /* Nilai mst_payroll_config.emp_health_bpjs(%) x Basic Salary */
        if ($empHealthBpjs > $maxEmpHealthBpjs) {
            $empHealthBpjs = $maxEmpHealthBpjs;
        }
        /* END BPJS KARYAWAN */

        if ($isHealthBPJS == '0') {
            $healthBpjs = 0;
            $empHealthBpjs = 0;
        }
        if ($isJHT == '0') {
            $jht = 0;
            $empJht = 0;
        }
        if ($isJP == '0') {
            $empJp = 0;
            $jp = 0;
        }
        if ($isJKKM == '0') {
            $jkkJkm = 0;
        }

        /* END GOVERNMENT REGULATION */
        $governmentRegulations = [
            'nonTaxAllowance' => $nonTaxAllowance,
            'totalPTKP' => $totalPTKP,
            'healthBpjs' => $healthBpjs,
            'jkkJkm' => $jkkJkm,
            'jht' => $jht,
            'empJht' => $empJht,
            'jp' => $jp,
            'empJp' => $empJp,
            'empHealthBpjs' => $empHealthBpjs,
        ];

        return $governmentRegulations;

        // return ResponseFormatter::success($governmentRegulations, "Successfully calculate Goverment Regulation", 200);
    }
}
