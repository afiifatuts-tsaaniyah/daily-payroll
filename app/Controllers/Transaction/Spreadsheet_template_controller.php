<?php

namespace App\Controllers\Transaction;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class Spreadsheet_template_controller extends BaseController
{
    /**
     * Menambahkan logo ke lembar kerja spreadsheet.
     *
     * @param \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet
     * @param string $coordinate
     * @return void
     */
    public static function addLogo($spreadsheet, $coordinate = 'A1')
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(FCPATH . 'assets/images/report_logo.jpg');
        $drawing->setCoordinates($coordinate);
        $drawing->setHeight(38);
        $drawing->setWorksheet($spreadsheet->getActiveSheet());
    }

    /**
     * Mengembalikan daftar gaya (styles) standar untuk laporan spreadsheet.
     *
     * @return array
     */
    public static function getStyles(): array
    {
        return [
            'boldFont' => [
                'font' => [
                    'bold' => true,
                ],
            ],
            'totalStyle' => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => '0000FF'],
                ],
            ],
            'underlineText' => [
                'font' => [
                    'underline' => true,
                ],
            ],
            'allBorderStyle' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ],
            'outlineBorderStyle' => [
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ],
            'topBorderStyle' => [
                'borders' => [
                    'top' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ],
            'bottomBorderStyle' => [
                'borders' => [
                    'bottom' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ],
            'center' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ],
            'italicFont' => [
                'font' => [
                    'italic' => true,
                ],
            ],
            'right' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ],
            'left' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
