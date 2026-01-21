<?php
// Load file koneksi.php
include "koneksi.php";
// Load file autoload.php
require 'vendor/autoload.php';
// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
$style_col = [
    'font' => ['bold' => true], // Set font nya jadi bold
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    ],
    'borders' => [
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
    ]
];
// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
$style_row = [
    'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    ],
    'borders' => [
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
    ]
];
$sheet->setCellValue('A1', "DATA SISWA"); // Set kolom A1 dengan tulisan "DATA SISWA"
$sheet->mergeCells('A1:F1'); // Set Merge Cell pada kolom A1 sampai F1
$sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
$sheet->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
// Buat header tabel nya pada baris ke 3
          $sheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'BiodataId')
                ->setCellValue('B1', 'Badge No')
                ->setCellValue('C1', 'Full Name')
                ->setCellValue('D1', 'Dept')
                ->setCellValue('E1', 'D01')
                ->setCellValue('F1', 'D02')
                ->setCellValue('G1', 'D03')
                ->setCellValue('H1', 'D04')
                ->setCellValue('I1', 'D05')
                ->setCellValue('J1', 'D06')
                ->setCellValue('K1', 'D07')
                ->setCellValue('L1', 'D08')
                ->setCellValue('M1', 'D09')
                ->setCellValue('N1', 'D10')
                ->setCellValue('O1', 'D11')
                ->setCellValue('P1', 'D12')
                ->setCellValue('Q1', 'D13')
                ->setCellValue('R1', 'D14')
                ->setCellValue('S1', 'D15')
                ->setCellValue('T1', 'D16')
                ->setCellValue('U1', 'D17')
                ->setCellValue('V1', 'D18')
                ->setCellValue('W1', 'D19')
                ->setCellValue('X1', 'D20')
                ->setCellValue('Y1', 'D21')
                ->setCellValue('Z1', 'D02')
                ->setCellValue('AA1', 'D23')
                ->setCellValue('AB1', 'D24')
                ->setCellValue('AC1', 'D25')
                ->setCellValue('AD1', 'D26')
                ->setCellValue('AE1', 'D27')
                ->setCellValue('AF1', 'D28')
                ->setCellValue('AG1', 'D29')
                ->setCellValue('AH1', 'D30')
                ->setCellValue('AI1', 'D31');// Set kolom F3 dengan tulisan "ALAMAT"
// Apply style header yang telah kita buat tadi ke masing-masing kolom header
$sheet->getStyle('A1')->applyFromArray($style_col);
$sheet->getStyle('B1')->applyFromArray($style_col);
$sheet->getStyle('C1')->applyFromArray($style_col);
$sheet->getStyle('D1')->applyFromArray($style_col);
$sheet->getStyle('E1')->applyFromArray($style_col);
$sheet->getStyle('F1')->applyFromArray($style_col);
$sheet->getStyle('G1')->applyFromArray($style_col);
$sheet->getStyle('H1')->applyFromArray($style_col);
$sheet->getStyle('I1')->applyFromArray($style_col);
$sheet->getStyle('J1')->applyFromArray($style_col);
$sheet->getStyle('K1')->applyFromArray($style_col);
$sheet->getStyle('L1')->applyFromArray($style_col);
$sheet->getStyle('M1')->applyFromArray($style_col);
$sheet->getStyle('N1')->applyFromArray($style_col);
$sheet->getStyle('O1')->applyFromArray($style_col);
$sheet->getStyle('P1')->applyFromArray($style_col);
$sheet->getStyle('Q1')->applyFromArray($style_col);
$sheet->getStyle('R1')->applyFromArray($style_col);
$sheet->getStyle('S1')->applyFromArray($style_col);
$sheet->getStyle('T1')->applyFromArray($style_col);
$sheet->getStyle('U1')->applyFromArray($style_col);
$sheet->getStyle('V1')->applyFromArray($style_col);
$sheet->getStyle('W1')->applyFromArray($style_col);
$sheet->getStyle('X1')->applyFromArray($style_col);
$sheet->getStyle('Y1')->applyFromArray($style_col);
$sheet->getStyle('Z1')->applyFromArray($style_col);
$sheet->getStyle('AA1')->applyFromArray($style_col);
$sheet->getStyle('AB1')->applyFromArray($style_col);
$sheet->getStyle('AC1')->applyFromArray($style_col);
$sheet->getStyle('AD1')->applyFromArray($style_col);
$sheet->getStyle('AE1')->applyFromArray($style_col);
$sheet->getStyle('AF1')->applyFromArray($style_col);
$sheet->getStyle('AG1')->applyFromArray($style_col);
$sheet->getStyle('AH1')->applyFromArray($style_col);
$sheet->getStyle('AI1')->applyFromArray($style_col);
// Set height baris ke 1, 2 dan 3
$sheet->getRowDimension('1')->setRowHeight(20);
$sheet->getRowDimension('2')->setRowHeight(20);
$sheet->getRowDimension('3')->setRowHeight(20);
// Buat query untuk menampilkan semua data siswa
$sql = mysqli_query($connect, "SELECT * FROM siswa");
$no = 1; // Untuk penomoran tabel, di awal set dengan 1
$row = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
while ($data = mysqli_fetch_array($sql)) { // Ambil semua data dari hasil eksekusi $sql
    $sheet->setCellValue('A' . $row, $no);
    $sheet->setCellValue('B' . $row, $data['nis']);
    $sheet->setCellValue('C' . $row, $data['nama']);
    $sheet->setCellValue('D' . $row, $data['jenis_kelamin']);
    // Khusus untuk no telepon. kita set type kolom nya jadi STRING
    $sheet->setCellValueExplicit('E' . $row, $data['telp'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->setCellValue('F' . $row, $data['alamat']);
    // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
    $sheet->getStyle('A1')->applyFromArray($style_row);
$sheet->getStyle('B1')->applyFromArray($style_row);
$sheet->getStyle('C1')->applyFromArray($style_row);
$sheet->getStyle('D1')->applyFromArray($style_row);
$sheet->getStyle('E1')->applyFromArray($style_row);
$sheet->getStyle('F1')->applyFromArray($style_row);
$sheet->getStyle('G1')->applyFromArray($style_row);
$sheet->getStyle('H1')->applyFromArray($style_row);
$sheet->getStyle('I1')->applyFromArray($style_row);
$sheet->getStyle('J1')->applyFromArray($style_row);
$sheet->getStyle('K1')->applyFromArray($style_row);
$sheet->getStyle('L1')->applyFromArray($style_row);
$sheet->getStyle('M1')->applyFromArray($style_row);
$sheet->getStyle('N1')->applyFromArray($style_row);
$sheet->getStyle('O1')->applyFromArray($style_row);
$sheet->getStyle('P1')->applyFromArray($style_row);
$sheet->getStyle('Q1')->applyFromArray($style_row);
$sheet->getStyle('R1')->applyFromArray($style_row);
$sheet->getStyle('S1')->applyFromArray($style_row);
$sheet->getStyle('T1')->applyFromArray($style_row);
$sheet->getStyle('U1')->applyFromArray($style_row);
$sheet->getStyle('V1')->applyFromArray($style_row);
$sheet->getStyle('W1')->applyFromArray($style_row);
$sheet->getStyle('X1')->applyFromArray($style_row);
$sheet->getStyle('Y1')->applyFromArray($style_row);
$sheet->getStyle('Z1')->applyFromArray($style_row);
$sheet->getStyle('AA1')->applyFromArray($style_row);
$sheet->getStyle('AB1')->applyFromArray($style_row);
$sheet->getStyle('AC1')->applyFromArray($style_row);
$sheet->getStyle('AD1')->applyFromArray($style_row);
$sheet->getStyle('AE1')->applyFromArray($style_row);
$sheet->getStyle('AF1')->applyFromArray($style_row);
$sheet->getStyle('AG1')->applyFromArray($style_row);
$sheet->getStyle('AH1')->applyFromArray($style_row);
$sheet->getStyle('AI1')->applyFromArray($style_row);
    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom No
    $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); // Set text left untuk kolom NIS
    $sheet->getRowDimension($row)->setRowHeight(20); // Set height tiap row
    $no++; // Tambah 1 setiap kali looping
    $row++; // Tambah 1 setiap kali looping
}
// Set width kolom
$sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
$sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
$sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
$sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
$sheet->getColumnDimension('E')->setWidth(15); // Set width kolom E
$sheet->getColumnDimension('F')->setWidth(30); // Set width kolom F
// Set orientasi kertas jadi LANDSCAPE
$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
// Set judul file excel nya
$sheet->setTitle("Laporan Data Siswa");
// Proses file excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename='.$fileName.$year.$month.$periode.'.xlsx'); // Set nama file excel nya
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>