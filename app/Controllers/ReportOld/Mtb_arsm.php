<?php 
namespace App\Controllers\Report;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Transaction\M_tr_timesheet;
use App\Models\Transaction\M_upload;
use App\Models\Transaction\M_proses;
use App\Models\Transaction\M_tr_overd;
use App\Models\Transaction\M_tr_slip;
use App\Models\Master\M_dept;
use App\Models\Transaction\M_rp_db;


class Mtb_arsm extends BaseController
{
    public function index()
    {
         /* ***Using Valid Path */
        $data['actView'] = 'Report/v_mtb_arsm';
        return view('home', $data);
    }

    public function getMtbArSMList($yearPeriod, $monthPeriod)
    {
        $trTimesheet = new M_tr_timesheet;
        $data = $trTimesheet->getDataAllSM($yearPeriod, $monthPeriod);
        // echo $this->db->last_query(); exit(0);
        // echo $this->db->last_query(); exit(0);
        /*return json_encode($query);*/
        $myData = array();
        foreach ($data as $key => $row) 
        {
               $myData[] = array(
                $row['slip_id'],
                $row['full_name'],         
                // $row['production_bonus'],         
                // $row['workday_adj'],         
                // $row['adjust_in'],         
                // $row['adjust_out'],         
                $row['dept'],         
                $row['position']        
                // $row['attendance_bonus'],         
                // $row['other_allowance1'],         
                // $row['other_allowance2'],         
                // $row['cc_payment'],         
                // $row['thr'],         
                // $row['debt_burden'],
                // $row['debt_explanation']
            );            
        }
          

        echo json_encode($myData);  
        // echo $this->db->last_query(); 
    }

    public function exportReportMtbArsm($yearPeriod, $monthPeriod)
        {
            $trTimesheet = new M_tr_timesheet;
            $data = $trTimesheet->getDataAllSM($yearPeriod, $monthPeriod);
            $sm   = $trTimesheet->getSumData($yearPeriod, $monthPeriod);
            // exit();
            // Create new Spreadsheet object

            // $summary = new M_tr_timesheet();
            // $depart = str_replace("%20"," ",$dept);
            // // echo $depart;
            // // exit();
            // $summary->setYearProcess($yearPeriod);
            // $summary->setMonthProcess($monthPeriod);
            // $summary->setpayrollGroup($sm);
            // $summary->setdept($depart);
            // echo ($dept);
            // exit();
            // $data = $summary->summary();
             $spreadsheet = new Spreadsheet();  

             $spreadsheet->getProperties()->setCreator('Maurice - Web - Android')
                ->setLastModifiedBy('Maurice - Web - Android')
                ->setTitle('Office 2007 XLSX Test Document')
                ->setSubject('Office 2007 XLSX Test Document')
                ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('Test result file');

            $boldFont = [
                'font' => [
                    'bold' => true
                    // 'color' => ['argb' => '0000FF'],
                ],
            ];

            $totalStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => '0000FF'],
                ],
            ];

            $allBorderStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ];

            $outlineBorderStyle = [
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ];

            $topBorderStyle = [
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ];

            $bottomBorderStyle = [
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ];

            $center = array();
            $center['alignment'] = array();
            $center['alignment']['horizontal'] = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER; 
            $center['alignment']['vertical'] = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER; 

            $right = array();
            $right['alignment'] = array();
            $right['alignment']['horizontal'] = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT; 
            $right['alignment']['vertical'] = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;

            $left = array();
            $left['alignment'] = array();
            $left['alignment']['horizontal'] = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT; 
            $left['alignment']['vertical'] = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;

            

            // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'SUMMARY INVOICE PT SANGATI SOERYA SEJAHTERA - PT. Agincourt Resources Martabe Gold Mine');
            $spreadsheet->getActiveSheet()->mergeCells("A1:F1");
            // $spreadsheet->getActiveSheet()->getStyle("A1:C1")->applyFromArray($center);
            // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'PT. PONTIL');
            // $spreadsheet->getActiveSheet()->mergeCells("A2:U2");
            // $spreadsheet->getActiveSheet()->getStyle("A2:U2")->applyFromArray($center);
            // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'PERIOD : '.$monthPeriod.'-'.$yearPeriod);
            $spreadsheet->getActiveSheet()->mergeCells("A2:F2");
            // $spreadsheet->getActiveSheet()->getStyle("A2:C2")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true)->setSize(14);
            $spreadsheet->getActiveSheet()->getStyle("A2:D2")->getFont()->setBold(true)->setSize(14);
            // $spreadsheet->getActiveSheet()->getStyle("A3:G5")->getFont()->setBold(true)->setSize(12); 
            // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'DATE        : ');
            // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'INVOICE NO  : ');
            // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(0, 5, 'CONTRACT NO : ');        

            /* SET HEADER BG COLOR*/
            $spreadsheet->getActiveSheet()->getStyle('E6:P6')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('F2BE6B');
            $spreadsheet->getActiveSheet()->getStyle('Q6:AM6')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('949494'); 
            $spreadsheet->getActiveSheet()->getStyle('AN5:AR5')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('949494');        

            /* START INVOICE TITLE */
            $spreadsheet->getActiveSheet()->getStyle("A6:AR6")->getFont()->setSize(12);

            $spreadsheet->getActiveSheet()->getStyle("A6:A6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("B6:B6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("C6:C6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("D6:D6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("E6:E6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("F6:F6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("G6:G6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("H6:H6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("I6:I6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("J6:J6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("K6:K6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("L6:L6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("M6:M6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("N6:N6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("O6:O6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("P6:P6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("Q6:Q6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("R6:R6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("S6:S6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("T6:T6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("U6:U6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("V6:V6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("W6:W6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("X6:X6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("Y6:Y6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("Z6:Z6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AA6:AA6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AB6:AB6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AC6:AC6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AD6:AD6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AE6:AE6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AF6:AF6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AG6:AG6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AH6:AH6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AI6:AI6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AJ6:AJ6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AK6:AK6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AL6:AL6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AM6:AM6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AN6:AN6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AO6:AO6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AP6:AP6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AQ6:AQ6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AR6:AR6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AN5:AN5")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AO5:AO5")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AP5:AP5")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AQ5:AQ5")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("AR5:AR5")->applyFromArray($allBorderStyle);

            $spreadsheet->getActiveSheet()->getStyle("A6:A6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("B6:B6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("C6:C6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("D6:D6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("F6:F6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("G6:G6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("H6:H6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("I6:I6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("J6:J6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("K6:K6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("L6:L6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("M6:M6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("N6:N6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("O6:O6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("P6:P6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("Q6:Q6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("R6:R6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("S6:S6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("T6:T6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("U6:U6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("V6:V6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("W6:W6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("X6:X6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("Y6:Y6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("Z6:Z6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AA6:AA6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AB6:AB6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AC6:AC6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AD6:AD6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AE6:AE6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AF6:AF6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AG6:AG6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AH6:AH6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AI6:AI6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AJ6:AJ6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AK6:AK6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AL6:AL6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AM6:AM6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AN6:AN6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AO6:AO6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AP6:AP6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AQ6:AQ6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AR6:AR6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AN5:AN5")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AO5:AO5")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AP5:AP5")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AQ5:AQ5")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("AR5:AR5")->applyFromArray($center);

            $spreadsheet->getActiveSheet()->getStyle("B6:B6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("C6:C6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("E6:E6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("F6:F6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("G6:G6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("H6:H6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("I6:I6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("J6:J6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("K6:K6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("L6:L6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("M6:M6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("N6:N6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("O6:O6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("P6:P6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("Q6:Q6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("R6:R6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("S6:S6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("T6:T6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("U6:U6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("V6:V6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("W6:W6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("X6:X6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("Y6:Y6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("Z6:Z6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AA6:AA6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AB6:AB6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AC6:AC6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AD6:AD6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AE6:AE6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AF6:AF6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AG6:AG6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AH6:AH6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AI6:AI6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AJ6:AJ6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AK6:AK6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AM6:AM6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AN6:AN6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AO6:AO6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AP6:AP6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AQ6:AQ6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AR6:AR6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AN5:AN5")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AO5:AO5")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AP5:AP5")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AQ5:AQ5")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("AR5:AR5")->getAlignment()->setWrapText(true);

            $spreadsheet->getActiveSheet()->getStyle("A6:AR6")->applyFromArray($outlineBorderStyle);

            $spreadsheet->getActiveSheet()->mergeCells("A6:A6");
            $spreadsheet->getActiveSheet()->mergeCells("B6:B6");
            $spreadsheet->getActiveSheet()->mergeCells("C6:C6");
            $spreadsheet->getActiveSheet()->mergeCells("D6:D6");
            $spreadsheet->getActiveSheet()->mergeCells("E6:E6");
            $spreadsheet->getActiveSheet()->mergeCells("F6:F6");
            $spreadsheet->getActiveSheet()->mergeCells("G6:G6");
            $spreadsheet->getActiveSheet()->mergeCells("H6:H6");
            $spreadsheet->getActiveSheet()->mergeCells("I6:I6");
            $spreadsheet->getActiveSheet()->mergeCells("J6:J6");
            $spreadsheet->getActiveSheet()->mergeCells("K6:K6");
            $spreadsheet->getActiveSheet()->mergeCells("M6:M6");
            $spreadsheet->getActiveSheet()->mergeCells("N6:N6");
            $spreadsheet->getActiveSheet()->mergeCells("O6:O6");
            $spreadsheet->getActiveSheet()->mergeCells("P6:P6");
            $spreadsheet->getActiveSheet()->mergeCells("Q6:Q6");
            $spreadsheet->getActiveSheet()->mergeCells("R6:R6");
            $spreadsheet->getActiveSheet()->mergeCells("S6:S6");
            $spreadsheet->getActiveSheet()->mergeCells("T6:T6");
            $spreadsheet->getActiveSheet()->mergeCells("U6:U6");
            $spreadsheet->getActiveSheet()->mergeCells("V6:V6");
            $spreadsheet->getActiveSheet()->mergeCells("W6:W6");
            $spreadsheet->getActiveSheet()->mergeCells("X6:X6");
            $spreadsheet->getActiveSheet()->mergeCells("Y6:Y6");
            $spreadsheet->getActiveSheet()->mergeCells("Z6:Z6");
            $spreadsheet->getActiveSheet()->mergeCells("AA6:AA6");
            $spreadsheet->getActiveSheet()->mergeCells("AB6:AB6");
            $spreadsheet->getActiveSheet()->mergeCells("AC6:AC6");
            $spreadsheet->getActiveSheet()->mergeCells("AD6:AD6");
            $spreadsheet->getActiveSheet()->mergeCells("AE6:AE6");
            $spreadsheet->getActiveSheet()->mergeCells("AF6:AF6");
            $spreadsheet->getActiveSheet()->mergeCells("AG6:AG6");
            $spreadsheet->getActiveSheet()->mergeCells("AH6:AH6");
            $spreadsheet->getActiveSheet()->mergeCells("AI6:AI6");
            $spreadsheet->getActiveSheet()->mergeCells("AJ6:AJ6");
            $spreadsheet->getActiveSheet()->mergeCells("AK6:AK6");
            $spreadsheet->getActiveSheet()->mergeCells("AL6:AL6");
            $spreadsheet->getActiveSheet()->mergeCells("AM6:AM6");
            $spreadsheet->getActiveSheet()->mergeCells("AN6:AN6");
            $spreadsheet->getActiveSheet()->mergeCells("AO6:AO6");
            $spreadsheet->getActiveSheet()->mergeCells("AP6:AP6");
            $spreadsheet->getActiveSheet()->mergeCells("AQ6:AQ6");
            $spreadsheet->getActiveSheet()->mergeCells("AR6:AR6");
            $spreadsheet->getActiveSheet()->mergeCells("AN5:AN5");
            $spreadsheet->getActiveSheet()->mergeCells("AO5:AO5");
            $spreadsheet->getActiveSheet()->mergeCells("AP5:AP5");
            $spreadsheet->getActiveSheet()->mergeCells("AQ5:AQ5");
            $spreadsheet->getActiveSheet()->mergeCells("AR5:AR5");

            $total = 0;
            $rounded = 0;
            $gaji = 0;
            $dana = 0;
            

            $spreadsheet->getActiveSheet()
                    ->setCellValue('A6', 'NO')
                    ->setCellValue('B6', 'Masa')
                    ->setCellValue('C6', 'NO. SM')
                    ->setCellValue('D6', 'Periode')
                    ->setCellValue('E6', 'No. NPWP')
                    ->setCellValue('F6', 'NO. KTP')
                    ->setCellValue('G6', 'Alamat')
                    ->setCellValue('H6', 'Jabatan')
                    ->setCellValue('I6', 'P/L')
                    ->setCellValue('J6', 'Status')
                    ->setCellValue('K6', 'No. ID')
                    ->setCellValue('L6', 'DEPARTEMEN')
                    ->setCellValue('M6', 'Basic Salary')
                    ->setCellValue('N6', 'Nama Penerima')
                    ->setCellValue('O6', 'Bank')
                    ->setCellValue('P6', 'No. Rek')
                    ->setCellValue('Q6', 'Nama')
                    ->setCellValue('R6', 'Jabatan dan Lokasi Kerja')
                    ->setCellValue('S6', 'BANK')
                    ->setCellValue('T6', 'No. Rek')
                    ->setCellValue('U6', 'SALARY THIS MONTH')
                    ->setCellValue('V6', 'OT')
                    ->setCellValue('W6', 'N SHIFT')
                    ->setCellValue('X6', 'JKK/JKM')
                    ->setCellValue('Y6', 'SHIFT')
                    ->setCellValue('Z6', 'BPJS KES 4%')
                    ->setCellValue('AA6', 'THR')
                    ->setCellValue('AB6', 'Dana Konpensasi')
                    ->setCellValue('AC6', 'JKK/JKM')
                    ->setCellValue('AD6', 'BPJS KES 5% (4% + 1%)')
                    ->setCellValue('AE6', 'JHT & JP')
                    ->setCellValue('AF6', 'PPH 21')
                    ->setCellValue('AG6', 'ADJ')
                    ->setCellValue('AH6', 'TOTAL')
                    ->setCellValue('AI6', 'ROUNDED')
                    ->setCellValue('AJ6', 'Gaji')
                    ->setCellValue('AK6', 'Keterangan')
                    ->setCellValue('AL6', 'Basic Salary')
                    ->setCellValue('AM6', '')
                    ->setCellValue('AN6', 'Salary This Mounth')
                    ->setCellValue('AO6', 'Tax Allow Rounded')
                    ->setCellValue('AP6', 'OT/Allow Rounded')
                    ->setCellValue('AQ6', 'Bonus/THR Rounded')
                    ->setCellValue('AR6', 'Dana Kompensasi')
                    ;
            $spreadsheet->getActiveSheet()
                    ->setCellValue('AN5', $sm['totalgajipokok'])
                    ->setCellValue('AO5', $sm['totalpph21'])
                    ->setCellValue('AP5', $sm['totalot'])
                    ->setCellValue('AQ5', $sm['totalthr'])
                    ;

            /* START TOTAL WORK HOUR */     

            $rowIdx = 7;
            $startIdx = $rowIdx; 
            $rowNo = 0;
            

            foreach ($data as $row) {
                
                $rowIdx++;
                $rowNo++;

                $startDate = $row['date_process'];
                $periode = date('Y-m-d', strtotime('+7 days', strtotime($startDate))); 
                $jabatan = $row['emp_position'];
                $lokasiKerja = $row['placement'];
                $jkkJkm = $row['jkkjkm'];
                $jhtjp = $row['jhtjp'];
                $pph21 = $row['pph21'];
                $adjustment = $row['adjustment'];
                $rounded = $row['round'];
                $totalgaji = $row['totalgaji'];
                $basicSalary = $row['gajipokok'];
                $pengurangan = $totalgaji - $basicSalary;
                if ($adjustment < 0) {
                   $adjustment = "($adjustment)";
                }
                if ($rounded < 0) {
                    $rounded = str_replace("-"," ",$rounded);
                    $rounded = "($rounded)";
                }
                if ($pengurangan < 0) {
                    $pengurangan = "($pengurangan)";
                }
                
                // $basic_salary = array_sum($row['gajipokok']);
                // // echo $basic_salary;
                // var_dump($row['gajipokok']);
                // echo $row['gajipokok'].'<br>';


            

            


                $spreadsheet->getActiveSheet()
                    // ->setCellValue('AN5', $sum)
                    ->setCellValue('A'.$rowIdx, $rowNo)
                    ->setCellValue('B'.$rowIdx, $row['month_period']) 
                    ->setCellValue('C'.$rowIdx, $row['payroll_group'])
                    ->setCellValue('D'.$rowIdx, "")
                    ->setCellValue('E'.$rowIdx, $row['tax_no'])
                    ->setCellValue('F'.$rowIdx, $row['id_card_no'])
                    ->setCellValue('G'.$rowIdx, $row['current_address'])
                    ->setCellValue('H'.$rowIdx, $row['emp_position'])
                    ->setCellValue('I'.$rowIdx, $row['gender'])
                    ->setCellValue('J'.$rowIdx, $row['marital_status'])
                    ->setCellValue('K'.$rowIdx, $row['biodata_id'])
                    ->setCellValue('L'.$rowIdx, $row['dept'])
                    ->setCellValue('M'.$rowIdx, $row['monthly'])
                    ->setCellValue('N'.$rowIdx, $row['account_name'])
                    ->setCellValue('O'.$rowIdx, $row['bank_name'])
                    ->setCellValue('P'.$rowIdx, $row['account_no'])
                    ->setCellValue('Q'.$rowIdx, $row['full_name'])
                    ->setCellValue('R'.$rowIdx, "$jabatan  $lokasiKerja")
                    ->setCellValue('S'.$rowIdx, $row['bank_name'])
                    ->setCellValue('T'.$rowIdx, $row['account_no'])
                    ->setCellValue('U'.$rowIdx, $row['gajipokok'])
                    ->setCellValue('V'.$rowIdx, $row['totalot'])
                    ->setCellValue('W'.$rowIdx, $row['allowance_02'])
                    ->setCellValue('X'.$rowIdx, $row['jkkjkm'])
                    ->setCellValue('Y'.$rowIdx, $row['allowance_01']) 
                    ->setCellValue('Z'.$rowIdx, $row['bpjs'])
                    ->setCellValue('AA'.$rowIdx, $row['thr'])
                    ->setCellValue('AB'.$rowIdx, '')
                    ->setCellValue('AC'.$rowIdx, "($jkkJkm)")
                    ->setCellValue('AD'.$rowIdx, $row['emp_bpjs'])
                    ->setCellValue('AE'.$rowIdx, "($jhtjp)")
                    ->setCellValue('AF'.$rowIdx, "($pph21)")
                    ->setCellValue('AG'.$rowIdx, $adjustment)
                    ->setCellValue('AH'.$rowIdx, $row['gaji'])
                    ->setCellValue('AI'.$rowIdx, $rounded)
                    ->setCellValue('AJ'.$rowIdx, $row['totalgaji'])
                    ->setCellValue('AK'.$rowIdx, $row['status_remarks'])
                    ->setCellValue('AL'.$rowIdx, '('.$row['monthly'].')')
                    ->setCellValue('AM'.$rowIdx, $pengurangan)
                    ->setCellValue('AN'.$rowIdx, $basicSalary)
                    ->setCellValue('AO'.$rowIdx, round($pph21))
                    ->setCellValue('AP'.$rowIdx, round($row['totalot']))
                    ->setCellValue('AQ'.$rowIdx, round($row['thr']))
                    ->setCellValue('AR'.$rowIdx, '')

                    ;

                $spreadsheet->getActiveSheet()->getStyle("A".$rowIdx.":AR".$rowIdx)->applyFromArray($outlineBorderStyle);
                $spreadsheet->getActiveSheet()->getStyle("A".$rowIdx.":AR".$rowIdx)->applyFromArray($allBorderStyle);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rowIdx, $rowNo);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $rowIdx, $row['name']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $rowIdx, $row['job_desc']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $rowIdx, $row['basic_salary']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $rowIdx, $row['rate']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $rowIdx, $row['normal_d']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $rowIdx, $row['ot_count1']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $rowIdx, $row['ot_count2']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $rowIdx, $row['ot_count3']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $rowIdx, $row['ot_count4']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $rowIdx, $row['worked_hours']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $rowIdx, $row['current_salary']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $rowIdx, $row['ot_total']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $rowIdx, $row['travel_bonus']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $rowIdx, $gross);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $rowIdx, $row['uplift']);
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $rowIdx, '=IF(K'.$rowIdx.'<=312,((E'.$rowIdx.'*K'.$rowIdx.')*64%),((E'.$rowIdx.'*312)*64%))');
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $rowIdx, '1');
                // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $rowIdx, $chargeTotal);
                /* SET ROW COLOR */
                if($rowIdx % 2 == 1)
                {
                    $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':AR'.$rowIdx)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('EAEBAF');             
                } 
                /* END UPDATE TAX */
                

            
            $spreadsheet->getActiveSheet()
                    ->setCellValue('A1', 'REKAP - PT '.'AGINCOURT MARTABE RESOURCES')
                    ->setCellValue('A2', "SUMMARY PEMBAYARAN GAJI $yearPeriod");
        } /* end foreach ($query as $row) */
        //     $spreadsheet->getActiveSheet()
        //         ->setCellValue('G'.($rowIdx+1), $totalbasic)
        //         ->setCellValue('H'.($rowIdx+1), $totalott)
        //         ->setCellValue('I'.($rowIdx+1), $totalnshift)
        //         ->setCellValue('J'.($rowIdx+1), $totaljkkjkm)
        //         ->setCellValue('K'.($rowIdx+1), $totalshift)
        //         ->setCellValue('L'.($rowIdx+1), $totalbpjs1)
        //         ->setCellValue('M'.($rowIdx+1), $totalthr)
        //         ->setCellValue('N'.($rowIdx+1), $totaldanakonpen)
        //         ->setCellValue('O'.($rowIdx+1), $totaljkkjkm1)
        //         ->setCellValue('P'.($rowIdx+1), $totalbpjs)
        //         ->setCellValue('Q'.($rowIdx+1), $totaljhtjp)
        //         ->setCellValue('R'.($rowIdx+1), $totalpph21)
        //         ->setCellValue('S'.($rowIdx+1), $totaladjustmen)
        //         ->setCellValue('T'.($rowIdx+1), $totaltotal)
        //         ->setCellValue('U'.($rowIdx+1), $totalround)
        //         ->setCellValue('F'.($rowIdx+1), 'TOTAL')
        //         ->setCellValue('V'.($rowIdx+1), $totalgaaji)
        //         ;
           

            // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, ($rowIdx+2), "TOTAL");
            // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(16, ($rowIdx+2), "=SUM(Q".$startIdx.":Q".$rowIdx.")");
            
            /* SET NUMBERS FORMAT*/
            
            unset($allBorderStyle);
            unset($center);
            unset($right);
            unset($left);
            
            $spreadsheet->setActiveSheetIndex(0);

            $str = 'ARSM';
            $fileName = preg_replace('/\s+/', '', $str);

            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$fileName.$monthPeriod.$yearPeriod.'.Xlsx"');
            // header('Content-Disposition: attachment;filename="Report Excel.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            /* BY COMPOSER */
            // $writer = new Xlsx($spreadsheet);
            /* OFFLINE/ BY COPY EXCEL FOLDER  */
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit(0);   
        }
}