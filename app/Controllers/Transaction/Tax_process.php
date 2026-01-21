<?php

namespace App\Controllers\Transaction;

use App\Controllers\BaseController;
use App\Models\Master\Config;
use App\Models\Master\ConfigAllowance;
use App\Models\Master\M_mt_salary;
use App\Models\Master\MtBiodata01;
use App\Models\Master\ProcessClossing;
use App\Models\Master\TaxGolongan;
use App\Models\Transaction\M_tr_overtime;
use App\Models\Transaction\M_tr_timesheet;
use App\Models\Transaction\SalarySlip;
use App\Models\Transaction\Tax;

class Tax_process extends BaseController
{
    protected $trSalarySlip;
    protected $trOvertime;
    protected $mtSalary;
    protected $mtProcessClossing;
    protected $mTimesheet;
    protected $mtPayrollConfig;
    protected $govController;
    protected $mAllowanceConfig;

    public function __construct()
    {
        $this->mtPayrollConfig = new Config();
        $this->trSalarySlip = new SalarySlip();
        $this->mtSalary = new M_mt_salary();
        $this->trOvertime = new M_tr_overtime();
        $this->mtProcessClossing = new ProcessClossing();
        $this->mTimesheet = new M_tr_timesheet();
        $this->govController = new Gov_regulation();
        $this->mAllowanceConfig = new ConfigAllowance();
    }

    public function taxProcess($biodataId, $brutto, $marital, $end, $month, $year, $clientName, $emp_jp, $emp_jht, $user)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $tarif                 = 0;
        $taxableIncome         = 0;
        $pkpTotalPeriod        = 0;
        $roundedTotal          = 0;
        $taxVal1               = 0;
        $taxVal2               = 0;
        $taxVal3               = 0;
        $taxVal4               = 0;
        $taxVal5               = 0;

        /* Define models */
        $mBiodata              = new MtBiodata01();
        $mConfig               = new Config();
        $mTaxGolongan          = new TaxGolongan();
        $mTax                  = new Tax();
        $mSalarySlip           = new SalarySlip();


        /* Cek apakah data tax sudah ada */
        $checkValid =   $mTax->isDataExist($biodataId, $clientName, $year, $month);

        /* Ambil Konfigurasi berdasarkan nama Klien */
        $configData            = $mConfig->loadByClient($clientName);
        // $npwpCharge            = $configData['npwp_charge'];
        $taxPercent1           = $configData['tax_percent1']; /* Persentase Progresif Pajak I */
        $taxPercent2           = $configData['tax_percent2']; /* Persentase Progresif Pajak II */
        $taxPercent3           = $configData['tax_percent3']; /* Persentase Progresif Pajak III */
        $taxPercent4           = $configData['tax_percent4']; /* Persentase Progresif Pajak IV */
        $taxPercent5           = $configData['tax_percent5']; /* Persentase Progresif Pajak V */
        $maxTaxVal1            = $configData['tax_max_val1']; /* Nominal Pajak I */
        $maxTaxVal2            = $configData['tax_max_val2']; /* Nominal Pajak II */
        $maxTaxVal3            = $configData['tax_max_val3']; /* Nominal Pajak III */
        $maxTaxVal4            = $configData['tax_max_val4']; /* Nominal Pajak IV */
        $maxTaxVal5            = $configData['tax_max_val5']; /* Nominal Pajak V */
        $maxTaxAllowance       = $configData['non_tax_allowance']; /* Max Tunjangan Jabatan */

        $data                  = $mTaxGolongan->getDataTer($brutto, $marital);
        $tarif                 = $data['tarif'] ?? 0;

        if ($brutto <= 0) {
            $pajak        = 0;
            $tarif        = 0;
            $dataGolongan = $mTaxGolongan->getGolonganByMarital($marital);
            $golongan              = $dataGolongan->golongan;
        } else {
            $pajak                 = floor($brutto * ($tarif / 100));
            $golongan              = $data['golongan'];
        }
        $tunjanganJabatan      = $brutto * (5 / 100);
        $totalKotor            = $brutto;

        if ($tunjanganJabatan >= $maxTaxAllowance) {
            $tunjanganJabatan  = $maxTaxAllowance;
        }

        /* PTKP SESUAI SLIP */
        $slipPtkp = $mSalarySlip->getPtkpByPeriod($biodataId, $clientName, $year, $month);
        $ptkpTotal = $slipPtkp['ptkp_total'];


        /* Default Variable */
        // $golongan                  = $data['golongan'];
        $taxPinalty                = 0;
        $nettoSetahun              = 0;
        $bruttoSetahun             = 0;
        $bulat                     = 0;
        $jpSetahun                 = 0;
        $jhtSetahun                = 0;
        $tunjanganJabatanSetahun   = 0;
        $totalTaxSebelumnya        = 0;



        // if ($month >= 12 || $end == 1) {
        //     $golongan              = 'END';
        //     $tarif                 = 0;
        //     $dataBio               = $mBiodata->getDataBio($biodataId);
        //     $npwp                  = isset($dataBio['npwp_no']) ? $dataBio['npwp_no'] : '';
        //     /*
        //         Ambil data pajak setahun
        //         1. Ambil data pajak setahun dan informasi terkait dari database menggunakan metode `$mTax->getDataTaxSetahun($biodataId, $month, $year)`.
        //         Data yang diambil meliputi:
        //         - Total bulan dalam periode yang dihitung (`jml_bln`)
        //         - Pajak sebelumnya yang telah dibayarkan (`total_tax_sebelumnya`)
        //         - Total penghasilan kotor setahun (`total_kotor`)
        //         - Total JP
        //         - Total JHT

        //         2. Hitung nilai awal `jml_bulan_final` dengan menambahkan 1 ke total bulan dalam periode pajak jika ini kasus normal.
        //         - `jml_bulan_final = $totalMonthPerPeriode + 1`.

        //         3. Periksa kondisi validasi berdasarkan variabel `$checkValid`, jika data sudah ada:
        //         a. Jika validasi berhasil dan hanya satu bulan yang dihitung (`$totalMonthPerPeriode == 1`):
        //             - Atur ulang `totalTaxSebelumnya = 0`.
        //             - Gunakan nilai langsung dari variabel input untuk `bruttoSetahun`, `jpSetahun`, dan `jhtSetahun`.

        //         b. Jika belum ada datanya namun masa kerja berakhir (`$end`) dan hanya satu bulan yang dihitung:
        //             - Tetapkan nilai `jml_bulan_final = 1`.
        //             - Gunakan nilai langsung dari variabel input untuk `bruttoSetahun`, `jpSetahun`, dan `jhtSetahun`.

        //         c. Jika kedua kondisi di atas tidak memenuhi:
        //             - Tetapkan nilai `jml_bulan_final = $totalMonthPerPeriode + 1`.

        //         4. Variabel yang dihitung dalam akhir adalah:
        //         - `$totalTaxSebelumnya`: jumlah pajak sebelumnya yang perlu dikurangi dari penghasilan.
        //         - `$bruttoSetahun`, `$jpSetahun`, dan `$jhtSetahun`: nilai yang dihitung berdasarkan penghasilan kotor yang dimasukkan dan data dari database.
        //     */

        //     $dataSetahun                  = $mTax->getDataTaxSetahun($biodataId, $month, $year);
        //     $totalMonthPerPeriode         = $dataSetahun['jml_bln'];
        //     // dd($dataSetahun);
        //     // case normal
        //     $jml_bulan_final              = $totalMonthPerPeriode + 1;
        //     $totalTaxSebelumnya           = $dataSetahun['total_tax_sebelumnya'];
        //     $bruttoSetahun                = $dataSetahun['total_kotor'] + $brutto;
        //     $jpSetahun                    = $dataSetahun['total_jp'] + $emp_jp;
        //     $jhtSetahun                   = $dataSetahun['total_jht'] + $emp_jht;


        //     if ($checkValid && $totalMonthPerPeriode == 1) {
        //         //jika ada datanya dan total bulan masuk hanya 1
        //         $totalTaxSebelumnya = 0;
        //         $jml_bulan_final          = $totalMonthPerPeriode;
        //         $bruttoSetahun            = $brutto;
        //         $jpSetahun                = $emp_jp;
        //         $jhtSetahun               = $emp_jht;
        //     } else if (!$checkValid && $end && $jml_bulan_final == 1) {
        //         //jika bulan masuk langsung final
        //         $jml_bulan_final          = 1;
        //         $bruttoSetahun            = $brutto;
        //         $jpSetahun                = $emp_jp;
        //         $jhtSetahun               = $emp_jht;
        //     }


        //     /*
        //         Menghitung biaya jabatan tahunan:

        //         1. Hitung biaya jabatan awal sebagai 5% dari bruto setahun ($bruttoSetahun).
        //         2. Hitung batas maksimal biaya jabatan sebagai $maxTaxAllowance dikalikan jumlah bulan kerja ($jml_bulan_final).
        //         3. Jika biaya jabatan awal melebihi batas maksimal, gunakan batas maksimal sebagai nilai final.
        //         4. Periksa apakah data NPWP tersedia, jika tidak, set nilai default kosong.
        //         5. Simpan biaya jabatan final ke variabel $tunjanganJabatanSetahun.
        //     */

        //     $biaya_jabatan_final          = (($bruttoSetahun * (5 / 100)));
        //     $biaya_jabatan_max            = $maxTaxAllowance * $jml_bulan_final;

        //     if ($biaya_jabatan_final > $biaya_jabatan_max) {
        //         $biaya_jabatan_final      = $biaya_jabatan_max;
        //     }
        //     $tunjanganJabatanSetahun      = $biaya_jabatan_final;


        //     /* Menghitung Annualisation / Total Bersih / Netto */

        //     $nettoSetahun          = $bruttoSetahun - $jpSetahun - $jhtSetahun - $tunjanganJabatanSetahun;
        //     // dd($nettoSetahun);

        //     $tValEnd  = 0;
        //     $tSisaEnd = 0;

        //     if ($nettoSetahun >= $ptkpTotal) {

        //         /*
        //             Mencari Nilai PKP dan Nilai Hasil Pembulatan

        //             1. Hitung penghasilan yang dikenakan pajak dengan menguranginya dari penghasilan netto tahunan dengan jumlah PTKP (Penghasilan Tidak Kena Pajak).
        //             2. Hitung PKP (Penghasilan Kena Pajak) dalam periode pajak dengan membulatkan penghasilan yang dikenakan pajak:
        //                 - Lakukan pembulatan ke kelipatan 1000 terdekat dengan metode rounding.
        //             3. Buat versi PKP dengan dua angka desimal untuk keperluan perhitungan lebih lanjut:
        //                 - $pkpWithDecimals = round($taxableIncome, 2);
        //             4. Hitung nilai PKP tanpa desimal:
        //                 - $pkpTotalPeriod = round($taxableIncome);
        //             5. Ambil tiga digit terakhir dari PKP yang dihitung sebelumnya untuk mengevaluasi kondisi bulatan:
        //                 - $lastThreeDigits = substr($pkpTotalPeriod, -3, 6);
        //             6. Periksa kondisi pembulatan berdasarkan nilai $lastThreeDigits:
        //                 - Jika nilai $lastThreeDigits > 500:
        //                     - Lakukan penyesuaian dengan menambahkan 1000 ke hasil PKP yang dihitung.
        //                 - Jika nilai $lastThreeDigits < 500:
        //                     - Lakukan penyesuaian dengan menguranginya langsung.
        //                 - Jika nilai $lastThreeDigits == 500:
        //                     - Tetapkan nilai bulat tanpa penyesuaian.
        //             7. Variabel `bulat` dihitung sebagai selisih antara PKP yang sudah dibulatkan dengan nilai desimal dan nilai PKP yang disesuaikan.
        //         */

        //         $taxableIncome         = $nettoSetahun - $ptkpTotal;
        //         $pkpTotalPeriod        = round($taxableIncome / 1000 - 0.5, 0) * 1000;

        //         $pkpWithDecimals       = round($taxableIncome, 2);
        //         $pkpTotalPeriod        = round($taxableIncome);
        //         $lastThreeDigits       = substr($pkpTotalPeriod, -3, 6);

        //         if ($lastThreeDigits > 500) {
        //             $roundedTotal      = $pkpTotalPeriod - $lastThreeDigits + 1000;
        //             $bulat             = $roundedTotal - $pkpWithDecimals;
        //         } else if ($lastThreeDigits < 500) {
        //             $roundedTotal      = $pkpTotalPeriod - $lastThreeDigits;
        //             $bulat             = $roundedTotal - $pkpWithDecimals;
        //         } else if ($lastThreeDigits == 500) {
        //             $roundedTotal      = $pkpTotalPeriod;
        //             $bulat             = 0;
        //         }

        //         /*
        //             Mencari Rumus Pajak Progresif

        //             TAX 1: Perhitungan pajak lapis pertama
        //             - Jika PKP lebih kecil dari atau sama dengan batas maksimal pajak lapis pertama (60.000.000),
        //                 pajak dihitung langsung berdasarkan PKP.
        //             - Jika PKP melebihi batas maksimal pajak lapis pertama, gunakan batas maksimal pajak lapis pertama
        //                 dan kalikan dengan persentase pajak lapis pertama(5%).

        //             TAX 2: Perhitungan pajak lapis kedua
        //             - Jika PKP melebihi batas maksimal pajak lapis 1, sisa PKP akan diperhitungkan.
        //             - Jika sisa PKP lebih kecil dari atau sama dengan batas maksimal pajak lapis kedua (190.000.000),
        //                 pajak dihitung berdasarkan sisa PKP.
        //             - Jika sisa PKP melebihi batas maksimal pajak lapis kedua, gunakan batas maksimal pajak lapis kedua
        //                 dan kalikan dengan persentase pajak lapis kedua(15%).

        //             TAX 3: Perhitungan pajak lapis ketiga
        //             - Jika PKP melebihi total batas maksimal lapis 1 dan lapis 2, sisa PKP akan diperhitungkan.
        //             - Jika sisa PKP lebih kecil dari atau sama dengan batas maksimal pajak lapis ketiga (250.000.000),
        //                 pajak dihitung berdasarkan sisa PKP.
        //             - Jika sisa PKP melebihi batas maksimal pajak lapis ketiga, gunakan batas maksimal pajak lapis ketiga
        //                 dan kalikan dengan persentase pajak lapis ketiga(25%).

        //             TAX 4: Perhitungan pajak lapis keempat
        //              - Jika PKP melebihi total batas maksimal lapis 1, lapis 2 dan lapis 3, sisa PKP akan diperhitungkan.
        //              - Jika sisa PKP lebih kecil dari atau sama dengan batas maksimal pajak lapis keempat (4.500.000.000),
        //                 pajak dihitung berdasarkan sisa PKP.
        //              - Jika sisa PKP melebihi batas maksimal pajak lapis keempat, gunakan batas maksimal pajak lapis keempat
        //                 dan kalikan dengan persentase pajak lapis keempat(30%).

        //             TAX 5: Perhitungan pajak lapis kelima
        //             - Jika PKP melebihi total batas maksimal lapis 1, lapis 2, lapis 3 dan lapis 4, sisa PKP akan diperhitungkan.
        //             - Pajak dihitung langsung dari sisa PKP dikalikan dengan persentase pajak lapis 5(35%).
        //         */

        //         if ($pkpTotalPeriod > 0) {

        //             if ($maxTaxVal1 > 0) {
        //                 $tValEnd = $pkpTotalPeriod / $maxTaxVal1; // 60.000.000
        //                 if ($tValEnd >= 1) {
        //                     $taxVal1 = $maxTaxVal1 * ($taxPercent1 / 100);
        //                 } else {
        //                     $taxVal1 = $pkpTotalPeriod * ($taxPercent1 / 100); //5%
        //                 }
        //             }

        //             if ($maxTaxVal2 > 0) {
        //                 if ($pkpTotalPeriod > $maxTaxVal1) {
        //                     $tSisaEnd = $pkpTotalPeriod - $maxTaxVal1;
        //                     $tValEnd = $tSisaEnd / $maxTaxVal2; // 190.000.000
        //                     if ($tValEnd >= 1) {
        //                         $taxVal2 = $maxTaxVal2 * ($taxPercent2 / 100);
        //                     } else {
        //                         $taxVal2 = $tSisaEnd * ($taxPercent2 / 100); //15
        //                     }
        //                 }
        //             }


        //             if ($maxTaxVal3 > 0) {
        //                 if ($pkpTotalPeriod > ($maxTaxVal1 + $maxTaxVal2)) {
        //                     $tSisaEnd = $pkpTotalPeriod - $maxTaxVal1 - $maxTaxVal2;
        //                     $tValEnd = $tSisaEnd / $maxTaxVal2; //250.000.000
        //                     if ($tValEnd >= 1) {
        //                         $taxVal3 = $maxTaxVal3 * ($taxPercent3 / 100);
        //                     } else {
        //                         $taxVal3 = $tSisaEnd * ($taxPercent3 / 100); //25%
        //                     }
        //                 }
        //             }

        //             if ($maxTaxVal4 > 0) {
        //                 if ($pkpTotalPeriod > ($maxTaxVal1 + $maxTaxVal2 + $maxTaxVal3)) {
        //                     $tSisaEnd = $pkpTotalPeriod - $maxTaxVal1 - $maxTaxVal2 - $maxTaxVal3;
        //                     $tValEnd = $tSisaEnd / $maxTaxVal4; //4.500.000.000
        //                     if ($tValEnd >= 1) {
        //                         $taxVal4 = $maxTaxVal4 * ($taxPercent4 / 100);
        //                     } else {
        //                         $taxVal4 = $tSisaEnd * ($taxPercent4 / 100); //30%
        //                     }
        //                 }
        //             }

        //             if ($pkpTotalPeriod > ($maxTaxVal1 + $maxTaxVal2 + $maxTaxVal3 + $maxTaxVal4)) {
        //                 $tSisaEnd = $pkpTotalPeriod - $maxTaxVal1 - $maxTaxVal2 - $maxTaxVal3 - $maxTaxVal4;
        //                 $taxVal5 = $tSisaEnd * ($taxPercent5 / 100); //35%
        //             }
        //         }
        //     }

        //     $pajakGajiSthn  = $taxVal1 + $taxVal2 + $taxVal3 + $taxVal4 + $taxVal5;
        //     $pajakSebulan   = $pajakGajiSthn - $totalTaxSebelumnya;
        //     $taxPinalty     = 0;

        //     //belum dipakai
        //     // if ($npwp == '') {
        //     //     $taxPinalty = $pajakSebulan * 0.20;
        //     // }

        //     $pajak          = floor($pajakSebulan);
        // }
        $newID      = $mTax->generateID();

        date_default_timezone_set('Asia/Jakarta');
        $dateNow    = date('Y-m-d H:i:s');



        if ($checkValid) {
            // Jika data sudah ada → update
            $mTax->update($checkValid['tx_id'], [
                'biodata_id' => $biodataId,
                'year' => $year,
                'month' => $month,
                'company_name' => $clientName,
                'marital_status' => $marital,
                'tax_val' => $pajak,
                'tax_pinalty' => $taxPinalty,
                'netto_setahun' => $nettoSetahun,
                'golongan' => $golongan,
                'tunjangan_jabatan' => $tunjanganJabatan,
                'tarif' => $tarif,
                'emp_jp' => $emp_jp,
                'emp_jht' => $emp_jht,
                'jp_setahun' => $jpSetahun,
                'jht_setahun' => $jhtSetahun,
                'penghasilan_pajak' => $taxableIncome,
                'brutto' => $totalKotor,
                'pic_input' => $user,
                'update_time' => $dateNow,
                'brutto_setahun' => $bruttoSetahun,
                'tunjangan_jabatan_setahun' => $tunjanganJabatanSetahun,
                'penghasilan_pajak_bulat' => $roundedTotal,
                'total_tax_sebelumnya' => $totalTaxSebelumnya,
                'tax_val_1' => $taxVal1,
                'tax_val_2' => $taxVal2,
                'tax_val_3' => $taxVal3,
                'tax_val_4' => $taxVal4,
                'tax_val_5' => $taxVal5
            ]);
        } else {
            // Jika data belum ada → insert baru
            $mTax->insert([
                'tx_id' => $newID,
                'biodata_id' => $biodataId,
                'year' => $year,
                'month' => $month,
                'company_name' => $clientName,
                'marital_status' => $marital,
                'tax_val' => $pajak,
                'tax_pinalty' => $taxPinalty,
                'netto_setahun' => $nettoSetahun,
                'golongan' => $golongan,
                'tunjangan_jabatan' => $tunjanganJabatan,
                'tarif' => $tarif,
                'emp_jp' => $emp_jp,
                'emp_jht' => $emp_jht,
                'jp_setahun' => $jpSetahun,
                'jht_setahun' => $jhtSetahun,
                'penghasilan_pajak' => $taxableIncome,
                'brutto' => $totalKotor,
                'pic_input' => $user,
                'brutto_setahun' => $bruttoSetahun,
                'tunjangan_jabatan_setahun' => $tunjanganJabatanSetahun,
                'penghasilan_pajak_bulat' => $roundedTotal,
                'total_tax_sebelumnya' => $totalTaxSebelumnya,
                'tax_val_1' => $taxVal1,
                'tax_val_2' => $taxVal2,
                'tax_val_3' => $taxVal3,
                'tax_val_4' => $taxVal4,
                'tax_val_5' => $taxVal5
            ]);
        }

        $json['tax']                             = $pajak;
        $json['tax_pinalty']                     = $taxPinalty;
        $json['netto_setahun']                   = $nettoSetahun;
        $json['marital']                         = $marital;
        $json['golongan']                        = $golongan;
        $json['tunjangan_jabatan']               = $tunjanganJabatan;
        $json['tunjangan_jabatan_setahun']       = $tunjanganJabatanSetahun;
        $json['brutto_setahun']                  = $bruttoSetahun;
        $json['jp_setahun']                      = $jpSetahun;
        $json['jht_setahun']                     = $jhtSetahun;
        $json['penghasilan_pajak']               = $taxableIncome;
        $json['penghasilan_pajak_bulat']         = $roundedTotal;
        $json['pembulatan']                      = $bulat;
        $json['tax_val_1']                       = $taxVal1;
        $json['tax_val_2']                       = $taxVal2;
        $json['tax_val_3']                       = $taxVal3;
        $json['tax_val_4']                       = $taxVal4;
        $json['tax_val_5']                       = $taxVal5;
        $json['tax_percent_1']                   = $taxPercent1;
        $json['tax_percent_2']                   = $taxPercent2;
        $json['tax_percent_3']                   = $taxPercent3;
        $json['tax_percent_4']                   = $taxPercent4;
        $json['tax_percent_5']                   = $taxPercent5;
        $json['nilai_ptkp']                      = $ptkpTotal;
        $json['tarif']                           = $tarif;
        $json['total_kotor']                     = $totalKotor;
        $json['total_tax_sebelumnya']            = $totalTaxSebelumnya;


        return $json;
    }
}
