<?php

namespace App\Helpers;

class ConfigurationHelper
{
    /**
     * Dapatkan shortname client untuk file timesheet
     */
    public static function getShortnameForTimesheet(string $ptName): string
    {
        $map = [
            'Agincourt_Martabe'   => 'AGCMTB',
            'Indodrill_Martabe'   => 'IDRMTB',
            'Promincon_Indonesia' => 'PMCMTB',
        ];

        return $map[$ptName] ?? '';
    }

    /**
     * Dapatkan shortname client untuk file allowance
     */
    public static function getShortnameForAllowance(string $ptName): string
    {
        $map = [
            'Agincourt_Martabe'   => 'ALWAGCMTB',
            'Indodrill_Martabe'   => 'ALWIDRMTB',
            'Promincon_Indonesia' => 'ALWPMCMTB',
        ];

        return $map[$ptName] ?? '';
    }

    /**
     * Dapatkan start date periode client 
     */
    public static function getStartDatePeriode(string $ptName): string
    {
        $map = [
            'Agincourt_Martabe'   => 0,
            'Indodrill_Martabe'   => 0,
            'Promincon_Indonesia' => 15,
        ];

        return $map[$ptName] ?? '';
    }

    public static function getClientNameByTimesheet($shortName)
    {
        $clients = [
            'AGCMTB' => 'Agincourt_Martabe',
            'IDRMTB' => 'Indodrill_Martabe',
            'PMCMTB' => 'Promincon_Indonesia', // ✅ Client baru
        ];

        return $clients[$shortName] ?? '';
    }

    public static function getClientValueByTimesheetUpload($shortName)
    {
        $clients = [
            'AGM' => 'Agincourt_Martabe',
            'IDM' => 'Indodrill_Martabe',
            'PMC' => 'Promincon_Indonesia', // ✅ Client baru
        ];

        return $clients[$shortName] ?? '';
    }

    public static function getAllowanceConfig($clientName, $rowData)
    {
        $allowanceConfig = [
            'Promincon_Indonesia' => [
                "tunjangan" => -$rowData[0][4] ?? '0',
                "thr" => $rowData[0][5] ?? '0',
                "adjustment_in" => ($rowData[0][6] ?? '0'),
                // Bagian (-)
                "adjustment_out" => - ($rowData[0][7] ?? '0'),
                "workday_adjustment" => ($rowData[0][8] ?? '0'), //(bisa +/-)
                "thr_by_user" => - ($rowData[0][9] ?? '0'),
                "debt_burden" => - ($rowData[0][10] ?? '0'),
                // 'tunjangan',
                // 'thr',
                // 'adjustment_in',
                // 'adjustment_out',
                // 'workday_adjustment',
                // 'thr_by_user',
                // 'debt_burden'
            ],
        ];
        return $allowanceConfig[$clientName] ?? [];
    }

    public static function getAllowanceConfigOptimized($clientName, $sheet, $row)
    {
        switch ($clientName) {

            case 'Promincon_Indonesia':

                return [
                    "tunjangan"            =>  (float) $sheet->getCell("E{$row}")->getCalculatedValue(),
                    "thr"                  =>  (float) $sheet->getCell("F{$row}")->getCalculatedValue(),
                    "adjustment_in"        =>  (float) $sheet->getCell("G{$row}")->getCalculatedValue(),
                    "adjustment_out"       => -(float) $sheet->getCell("H{$row}")->getCalculatedValue(),
                    "workday_adjustment"   =>  (float) $sheet->getCell("I{$row}")->getCalculatedValue(),
                    "thr_by_user"          => -(float) $sheet->getCell("J{$row}")->getCalculatedValue(),
                    "debt_burden"          => -(float) $sheet->getCell("K{$row}")->getCalculatedValue(),
                ];

                break;

            case 'Agincourt_Martabe':

                return [
                    "thr"               =>  (float) $sheet->getCell("E{$row}")->getCalculatedValue(),
                    "adjustment_in"     =>  (float) $sheet->getCell("F{$row}")->getCalculatedValue(),
                    "adjustment_out"    =>  -(float) $sheet->getCell("G{$row}")->getCalculatedValue(),
                    "workday_adjustment"  => (float) $sheet->getCell("H{$row}")->getCalculatedValue(),
                    // "remarks"           =>  (float) $sheet->getCell("I{$row}")->getValue(),
                    "thr_by_user"       => -(float) $sheet->getCell("J{$row}")->getCalculatedValue(),
                    "lain_lain"       => (float) $sheet->getCell("K{$row}")->getCalculatedValue(),
                ];

                break;

            default:
                return [];
        }
    }

    public static function mapAllowanceValues($rowData)
    {
        return [
            "tunjangan" => -$rowData[0][4] ?? '0',
            "thr" => $rowData[0][5] ?? '0',
            "adjustment_in" => ($rowData[0][6] ?? '0'),
            // Bagian (-)
            "adjustment_out" => - ($rowData[0][7] ?? '0'),
            "workday_adjustment" => ($rowData[0][8] ?? '0'), //(bisa +/-)
            "thr_by_user" => - ($rowData[0][9] ?? '0'),
            "debt_burden" => - ($rowData[0][10] ?? '0'),
        ];
    }

    public static function getAllowanceConfig2($clientName, $rowData)
    {
        // Daftar mapping sesuai urutan kolom
        // 'field' → index kolom
        // 'minus' → apakah dibuat negatif
        $map = [
            ['name' => 'tunjangan',           'index' => 4,  'minus' => true],
            ['name' => 'thr',                 'index' => 5,  'minus' => false],
            ['name' => 'adjustment_in',       'index' => 6,  'minus' => false],
            ['name' => 'adjustment_out',      'index' => 7,  'minus' => true],
            ['name' => 'workday_adjustment',  'index' => 8,  'minus' => false],
            ['name' => 'thr_by_user',         'index' => 9,  'minus' => true],
            ['name' => 'debt_burden',         'index' => 10, 'minus' => true],
        ];

        $allowances = [];

        foreach ($map as $item) {
            $value = $rowData[0][$item['index']] ?? 0;
            $value = $item['minus'] ? -abs($value) : $value;   // otomatis buat minus kalau perlu

            $allowances[$item['name']] = $value;
        }

        return [
            'Promincon_Indonesia' => $allowances
        ][$clientName] ?? [];
    }


    public static function getClientNameByAllowance($shortName)
    {
        $map = [
            'ALWAGCMTB'   => 'Agincourt_Martabe',
            'ALWIDRMTB'   => 'Indodrill_Martabe',
            'ALWPMCMTB' => 'Promincon_Indonesia',
        ];

        return $map[$shortName] ?? '';
    }

    public static function getAllowanceDetailType($clientName)
    {
        // contoh isi config per client
        return [
            'Promincon_Indonesia' => [
                'plus' => [
                    'attendance_bonus',
                    'transport_bonus',
                    'night_shift_bonus',
                    'tunjangan',
                    'thr',
                ],
                'adjs' => [
                    'adjustment_in',
                    'adjustment_out',
                ],
                'mins' => [
                    'workday_adjustment',
                    'thr_by_user',
                    'debt_burden',
                ],
            ],
            // tambahkan client lain di sini...
        ][$clientName] ?? [];
    }

    /**
     * Dapatkan allowance_type berdasarkan nama allowance dan client.
     */
    public static function getAllowanceType(string $allowanceName): ?string
    {
        $config = [
            'plus' => [
                'attendance_bonus',
                'transport_bonus',
                'night_shift_bonus',
                'tunjangan',
                'thr',
            ],
            'adjs' => [
                'adjustment_in',
                'adjustment_out',
            ],
            'mins' => [
                'workday_adjustment',
                'thr_by_user',
                'debt_burden',
            ],
        ];

        if (in_array($allowanceName, $config['plus'])) {
            return 'plus';
        }

        if (in_array($allowanceName, $config['adjs'])) {
            return 'adjs';
        }

        if (in_array($allowanceName, $config['mins'])) {
            return 'mins';
        }

        return null;
    }

    public static function getDbLastDayOfMonth($date)
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT DAY(LAST_DAY(?)) AS lastDate", [$date]);
        $row = $query->getRowArray();
        return $row['lastDate'] ?? null;
    }

    public static function pembulatanTotal($total)
    {
        // Cek jika total tidak sama dengan 0
        if ($total != 0) {

            // Cek nilai di belakang koma
            $roundTotal = $total;
            $checkComma = explode('.', $roundTotal);

            if (isset($checkComma[1])) {
                if (strlen($checkComma[1]) > 1) {
                    $bulatan = substr($roundTotal, -5);
                } else {
                    $bulatan = substr($roundTotal, -4);
                }
            } else {
                $bulatan = substr($roundTotal, -2);
            }

            // Inisialisasi nilai pembulatan
            $get_round = 0;
            $final_round = 0;

            // Total di atas 100
            if ($total > 99) {
                if ($bulatan >= 50) {
                    $get_round = 100 - $bulatan;
                    $final_round = ($total > 0) ? $get_round : -$get_round;
                } else {
                    if ($bulatan < 10) {
                        $get_round = substr($bulatan, 1);
                    } else {
                        $get_round = $bulatan;
                    }
                    $final_round = ($total > 0) ? -$get_round : $get_round;
                }
            } else {
                // Total di bawah 100 dibulatkan ke 0
                $final_round = 0;
            }

            return $final_round;
        }

        return 0;
    }

    public static function maritalFormat($status)
    {
        $map = [
            'TK0' => 'TK/0',
            'TK1' => 'TK/1',
            'TK2' => 'TK/2',
            'TK3' => 'TK/3',
            'K0'  => 'K/0',
            'K1'  => 'K/1',
            'K2'  => 'K/2',
            'K3'  => 'K/3',
        ];

        return $map[$status] ?? $status;
    }


    public static function getShortClientTsCode($clientName)
    {
        $shortName = [
            'Promincon_Indonesia' => 'PMC',
            'Indodrill_Martabe' => 'IDM',
            'Agincourt_Martabe' => 'AGM'
        ];

        return $shortName[$clientName] ?? '';
    }
}
