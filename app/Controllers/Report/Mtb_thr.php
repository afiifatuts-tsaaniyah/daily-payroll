<?php namespace App\Controllers\Report;

use CodeIgniter\Controller;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Master\M_dept;
use App\Models\Report\M_mtb_thr;
use CodeIgniter\I18n\Time;



class Mtb_thr extends BaseController
{
    function test($x,$exit=0, $hide=false)
    {
        echo ($hide) ? '<div style="display:none;">' : '';
        echo "<pre>";
        if(is_array($x) || is_object($x)){
            echo print_r($x);
        }elseif(is_string($x)){
            echo $x;
        }else{
            echo var_dump($x);
        }
        echo "</pre><hr />";
        echo ($hide) ? '</div>' : '';
        if($exit==1){ die(); }
    }

    public function index()
    {
        $mtDept = new M_dept();
        $data['data_dept'] = $mtDept->get_dept();
        $data['actView'] = 'Report/v_mtb_thr';
        return view('home', $data);
    }

    public function getPayrolldeptList($startDate,$endDate, $dept)
    {
        $dept = str_replace("%20"," ",$depart);
        $Model  = new M_mtb_thr();
        $data   = $Model->getAlldeptData($startDate, $endDate, $dept);
        // echo $this->db->last_query(); exit(0);
        // echo $this->db->last_query(); exit(0);
        /*return json_encode($query);*/
        $myData = array();

        foreach ($data as $key => $row){
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

    public function getThrListByDept($startDate,$endDate,$depart)
    {
        $dept = str_replace("%20"," ",$depart);
        // dd($dept);
        $Model  = new M_mtb_thr();
        $data   = $Model->getAlldeptData($startDate, $endDate, $dept);
        // echo $this->db->last_query(); exit(0);
        // echo $this->db->last_query(); exit(0);
        /*return json_encode($query);*/
        $myData = array();

        foreach ($data as $key => $row){
            $myData[] = array(
                $row['slip_id'],
                $row['full_name'],         
                $row['dept'],         
                $row['position']        
            );            
        }

          

        echo json_encode($myData);  
    }

    public function getThrListAllDept($startDate, $endDate)
    {
        $Model = new M_mtb_thr();
        $dataDept = $Model->getDataDept($startDate, $endDate);
        $myData = array();
        foreach ($dataDept as $rows) {
            $dept = $rows['dept'];
            $data = $Model->getDataByYears($startDate, $endDate,$dept);
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
        }
          

        echo json_encode($myData); 
    }

    public function PrintAllDept($startDate, $endDate)
    {   
        $Model = new M_mtb_thr();
        $data = $Model->getDataDept($startDate, $endDate);
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

        /* COLOURING FOOTER */
        $spreadsheet->getActiveSheet()->getStyle("A3:G6")
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('FFB403');

        $spreadsheet->getActiveSheet()
            ->setCellValue('A3', 'NO')  
            ->setCellValue('B3', 'Department / User')  
            ->setCellValue('C3', 'Cost Code')  
            ->setCellValue('D3', 'Total Empl.')  
            ->setCellValue('E3', 'Total (Rp.)')
            ->setCellValue('F3', 'MANAGAMENT FEE Kontraktor')
            ->setCellValue('F6', '(Rp)')
            ->setCellValue('G3', 'TOTAL INVOICE')
            ->setCellValue('G6', '(Rp)')
        ;
        $spreadsheet->getActiveSheet()->getStyle("A3:A6")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("B3:B6")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("C3:C6")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("D3:D6")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("E3:E6")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("F3:F6")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("G3:G6")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()
            ->mergeCells("A3:A6")
            ->mergeCells("B3:B6")
            ->mergeCells("C3:C6")
            ->mergeCells("D3:D6")
            ->mergeCells("E3:E6")
            ->mergeCells("F3:F5")
            ->mergeCells("G3:G5")
            ->mergeCells("A7:G7")
        ;
        $spreadsheet->getActiveSheet()->getStyle("A3:A6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("B3:B6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("C3:C6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("D3:D6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("E3:E6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("F3:F6")->applyFromArray($outlineBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("G3:G6")->applyFromArray($outlineBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A3:A6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("B3:B6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("C3:C6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("D3:D6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("E3:E6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("F3:F5")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("F6:F6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("G3:G5")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("G6:G6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A3:A6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("B3:B6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("C3:C6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("D3:D6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("E3:E6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("F3:F6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("G3:G6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5.57);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(38.29);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(8.43);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(17.29);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(17.29);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(17.29);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(17.29);

        $spreadsheet->getActiveSheet()->getStyle('A7:G7')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('C0C0C0');
        $spreadsheet->getActiveSheet()->getStyle("A7:G7")->applyFromArray($outlineBorderStyle);
        

        

        $totalEmpAllDept = 0;
        $thrTotalAllDept = 0;
        $no = 0;
        $rowIdx = 7;
        foreach ($data as $row) {
            $rowIdx++;
            $no++;
            $dept = $row['dept'];
            $dataByYears = $Model->getDataByYears($startDate, $endDate,$dept);
        $thrTotal = 0;
            foreach ($dataByYears as $row1) {
                $bioId = $row1['biodata_id'];
                $RPP = $row1['daily'];
                $dataBio = $Model->getDataByBiodataId($startDate, $endDate,$bioId);
        $workJan = 0;
        $workFeb = 0;
        $workMar = 0;
        $workApr = 0;
        $workMay = 0;
        $workJun = 0;
        $workJul = 0;
        $workAug = 0;
        $workSep = 0;
        $workOct = 0;
        $workNov = 0;
        $workDec = 0;
                foreach ($dataBio as $rowBio) {
                    if ($rowBio['month_period'] == '05') {
                        $workMay = $rowBio['work_day'];
                    }
                    if ($rowBio['month_period'] == '06') {
                        $workJun = $rowBio['work_day'];
                    }
                    if ($rowBio['month_period'] == '07') {
                        $workJul = $rowBio['work_day'];
                    }
                    if ($rowBio['month_period'] == '08') {
                        $workAug = $rowBio['work_day'];
                    }
                    if ($rowBio['month_period'] == '09') {
                        $workSep = $rowBio['work_day'];
                    }
                    if ($rowBio['month_period'] == '10') {
                        $workOct = $rowBio['work_day'];
                    }
                    if ($rowBio['month_period'] == '11') {
                        $workNov = $rowBio['work_day'];
                    }
                    if ($rowBio['month_period'] == '12') {
                        $workDec = $rowBio['work_day'];
                    }
                    if ($rowBio['month_period'] == '01') {
                        $workJan = $rowBio['work_day'];
                    }
                    if ($rowBio['month_period'] == '02') {
                        $workFeb = $rowBio['work_day'];
                    }
                    if ($rowBio['month_period'] == '03') {
                        $workMar = $rowBio['work_day'];
                    }
                    if ($rowBio['month_period'] == '04') {
                        $workApr = $rowBio['work_day'];
                    }
                }
                // echo $workJan ;
                $totalWorkDay = $workMay + $workJun + $workJul + $workAug + $workSep + $workOct + $workNov + $workDec + $workJan + $workFeb + $workMar + $workApr;
                $thr = (($RPP * 20)/240)*$totalWorkDay;
                $totalEmp = sizeof($dataByYears);
                $thrTotal = $thrTotal + $thr;
            }
            // echo "$thrTotal = $thr</br>";

            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.$rowIdx, $no)  
                ->setCellValue('B'.$rowIdx, $dept)  
                ->setCellValue('C'.$rowIdx, '')
                ->setCellValue('D'.$rowIdx, $totalEmp)  
                ->setCellValue('E'.$rowIdx, $thrTotal)
                ->setCellValue('F'.$rowIdx, "=E$rowIdx*9.3%")
                ->setCellValue('G'.$rowIdx, "=E$rowIdx+F$rowIdx")
            ;
            $totalEmpAllDept = $totalEmpAllDept + $totalEmp;
            $thrTotalAllDept = $thrTotalAllDept + $thrTotal;
            $spreadsheet->getActiveSheet()->getStyle("A$rowIdx:G$rowIdx")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("A$rowIdx:G$rowIdx")->getFont()->setBold(true)->setSize(11);
            
        }
        $firstRow = $rowIdx - 1;
        // exit();
        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+1).':G'.($rowIdx+1))
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('C0C0C0');
        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+1).':G'.($rowIdx+1))->applyFromArray($outlineBorderStyle);
        $spreadsheet->getActiveSheet()
            ->setCellValue('A'.($rowIdx+2), 'TOTAL OF INVOICE')  
            ->setCellValue('D'.($rowIdx+2), $totalEmpAllDept)  
            ->setCellValue('E'.($rowIdx+2), "=SUM(E$rowIdx:E$firstRow)")
            ->setCellValue('F'.($rowIdx+2), "=SUM(F$rowIdx:F$firstRow)")
            ->setCellValue('G'.($rowIdx+2), "=SUM(G$rowIdx:G$firstRow)")
        ;
        $spreadsheet->getActiveSheet()
            ->mergeCells('A'.($rowIdx+2).':C'.($rowIdx+2))
        ;
        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+2).':B'.($rowIdx+2))->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle('C'.($rowIdx+2).':G'.($rowIdx+2))->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+2).':B'.($rowIdx+2))->applyFromArray($outlineBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+2).':B'.($rowIdx+2))->getFont()->setBold(true)->setSize(11);
        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+2).':G'.($rowIdx+2))
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('93FF4B');

            // echo $thrTotal.'-'.$row['dept'].'-'.$totalEmp.'</br>';
        unset($allBorderStyle);
        unset($center);
        unset($right);
        unset($left);
        
        // Rename worksheet

        $spreadsheet->getActiveSheet()->setTitle($dept);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        //Nama File
        // $str = $rowData['name'].$rowData['bio_rec_id'];
        $str = 'AllDept-'.$endDate.'THR';
        $fileName = preg_replace('/\s+/', '', $str);

        // Redirect output to a clientâ€™s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'.Xlsx"');
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

    public function Print($startDates, $endDates,$depart)
    {
        $Model = new M_mtb_thr();
        $dept = str_replace("%20"," ",$depart);
        // $Model->setpayrollGroup($sm);
        $data = $Model->getDataByYears($startDates, $endDates,$dept);
        $spreadsheet = new Spreadsheet();
        $startDate = new Time($startDates);
        $endDate = new Time($endDates);
        $interval = $startDate->difference($endDate);
        $Countmonths = $interval->getMonths();
        $monthName = $startDate->format('F'); // Mengembalikan nama bulan dalam bentuk teks penuh
        $years = $startDate->format('Y');
        $monthNumber = $startDate->format('m');
        $monthNumber = intval($monthNumber);

        $spreadsheet->getProperties()->setCreator('Maurice - Web - Android')
            ->setLastModifiedBy('Maurice - Web - Android')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');

        if (file_exists(base_url().'/assets/images/logo.png')) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath(base_url().'assets/images/Report_logo.jpg');
            $drawing->setCoordinates('A1');
            $drawing->setHeight(36);
            $drawing->setWorksheet($spreadsheet->getActiveSheet());
        }
        

        // Nama Field Baris Pertama
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1', 'SUMMARY TIMESHEET - PERHITUNGAN THR '.$startDates.'-'.$endDates)
            ->setCellValue('A2', 'Site Project Martabe 2023')
            ->setCellValue('A3', 'Periode : '.$startDates.' -'.$endDates)
            ->setCellValue('A4', 'Department : '.$dept);

        $spreadsheet->getActiveSheet()->getStyle("A1:T1")->getFont()->setBold(true)->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle("A2:T2")->getFont()->setBold(true)->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle("A3:T3")->getFont()->setBold(true)->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle("A4")->getFont()->setBold(true)->setSize(12);
        //$spreadsheet->getActiveSheet()->getStyle("A4:D4")->getFont()->setBold(true)->setSize(13);
        // $spreadsheet->getActiveSheet()->getStyle("A4:G4")->getFont()->setBold(true)->setSize(12); 

        

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

        /* COLOURING FOOTER */
        
        $alphachar = array_merge(range('A', 'Z'));
        $bulan = array(
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        );
        $rowHead = 3;
        $alphachar = array_merge(range('A', 'Z'));
        $spreadsheet->getActiveSheet()
            ->setCellValue('A5', 'NO')  
            ->setCellValue('B5', 'NAME')  
            ->setCellValue('C5', 'NO REKENING')  
            ->setCellValue('D5', 'WORKING STATUS') 
        ;

        for ($i=1; $i <= $Countmonths ; $i++) { 
            $rowHead++;
            if ($monthNumber > 12) {
                $years = $years  + 1;
                $monthNumber = $monthNumber -12;
            }
            $spreadsheet->getActiveSheet()->setCellValue($alphachar[$rowHead].'5', $bulan[$monthNumber].' '.$years);
            $spreadsheet->getActiveSheet()->getStyle("$alphachar[$rowHead]5:$alphachar[$rowHead]6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("$alphachar[$rowHead]5:$alphachar[$rowHead]6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("$alphachar[$rowHead]5:$alphachar[$rowHead]6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->mergeCells("$alphachar[$rowHead]5:$alphachar[$rowHead]6");
            $monthNumber++;
        }
        $spreadsheet->getActiveSheet()
            ->mergeCells('A1:'.$alphachar[$rowHead+5].'1')
            ->mergeCells('A2:'.$alphachar[$rowHead+5].'2')
            ->mergeCells('A3:'.$alphachar[$rowHead+5].'3');
        foreach(range('B',$alphachar[$rowHead+5]) as $columnID)
        {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $spreadsheet->getActiveSheet()
            ->setCellValue($alphachar[$rowHead+1].'5', 'Total Working')  
            ->setCellValue($alphachar[$rowHead+2].'5', 'Rate / Day')  
            ->setCellValue($alphachar[$rowHead+3].'5', 'BASE SALARY')  
            ->setCellValue($alphachar[$rowHead+4].'5', 'TOTAL THR') 
            ->setCellValue($alphachar[$rowHead+5].'5', 'Keterangan') 
        ;
        $spreadsheet->getActiveSheet()->mergeCells($alphachar[$rowHead+1].'5:'.$alphachar[$rowHead+1].'6');
        $spreadsheet->getActiveSheet()->mergeCells($alphachar[$rowHead+2].'5:'.$alphachar[$rowHead+2].'6');
        $spreadsheet->getActiveSheet()->mergeCells($alphachar[$rowHead+3].'5:'.$alphachar[$rowHead+3].'6');
        $spreadsheet->getActiveSheet()->mergeCells($alphachar[$rowHead+4].'5:'.$alphachar[$rowHead+4].'6');
        $spreadsheet->getActiveSheet()->mergeCells($alphachar[$rowHead+5].'5:'.$alphachar[$rowHead+5].'6');
        

        $spreadsheet->getActiveSheet()->getStyle('A5:'.$alphachar[$rowHead+5].'6')->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle('A5:'.$alphachar[$rowHead+5].'6')->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("A5:A6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("B5:B6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("C5:C6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("D5:D6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("E5:E6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("F5:F6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("G5:G6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("H5:H6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("I5:I6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("J5:J6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("K5:K6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("L5:L6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("M5:M6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("N5:N6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("O5:O6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("P5:P6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("Q5:Q6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("R5:R6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("S5:S6")->applyFromArray($allBorderStyle);
        // $spreadsheet->getActiveSheet()->getStyle("T5:T6")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A5:A6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("B5:B6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("C5:C6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("D5:D6")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+1].'5:'.$alphachar[$rowHead+1].'6')->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+2].'5:'.$alphachar[$rowHead+2].'6')->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+3].'5:'.$alphachar[$rowHead+3].'6')->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+4].'5:'.$alphachar[$rowHead+4].'6')->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+5].'5:'.$alphachar[$rowHead+5].'6')->applyFromArray($center);

        $spreadsheet->getActiveSheet()->getStyle('A1:'.$alphachar[$rowHead+5].'3')->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle('A5:'.$alphachar[$rowHead+5].'6')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('FFFF00'); 
         $spreadsheet->getActiveSheet()->getStyle('A7:'.$alphachar[$rowHead+5].'7')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('C0C0C0');



        $spreadsheet->getActiveSheet()
            ->mergeCells("A5:A6")
            ->mergeCells("B5:B6")
            ->mergeCells("C5:C6")
            ->mergeCells("D5:D6")
            ;

        $spreadsheet->getActiveSheet()->getStyle("B5:B6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("C5:C6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("D5:D6")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+1].'5:'.$alphachar[$rowHead+1].'6')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+2].'5:'.$alphachar[$rowHead+2].'6')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+3].'5:'.$alphachar[$rowHead+3].'6')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+4].'5:'.$alphachar[$rowHead+4].'6')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+5].'5:'.$alphachar[$rowHead+5].'6')->getAlignment()->setWrapText(true);

        $spreadsheet->getActiveSheet()->getStyle('A7:'.$alphachar[$rowHead+5].'7')->applyFromArray($outlineBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle('A7:'.$alphachar[$rowHead+5].'7')->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle('A7:'.$alphachar[$rowHead+5].'7')->getFont()->setBold(true)->setSize(10);
        $spreadsheet->getActiveSheet()->mergeCells('A7:'.$alphachar[$rowHead+5].'7');
        $spreadsheet->getActiveSheet()->setCellValue('A7', 'Formula VlOOKUP to INVOICE EVERY MONTH');

        $rowIdx = 7;
        $startIdx = $rowIdx;
        $rowNo = 0;
        $work = 0;
        $workTotal = 0;
        $numberAlpha = 3;

        foreach ($data as $row) {
            $rowIdx++;
            $rowNo++;

            $spreadsheet->getActiveSheet()
            ->setCellValue('A'.$rowIdx, $rowNo)
            ->setCellValue('B'.$rowIdx, $row['full_name'])
            ->setCellValue('C'.$rowIdx, $row['account_no'])
            ->setCellValue('D'.$rowIdx, $row['status_payroll'])
            ->setCellValue('R'.$rowIdx, $row['daily'])
            ->setCellValue('S'.$rowIdx, $row['monthly']);
            $bioId = $row['biodata_id'];
            $dataBio = $Model->getDataByBiodataIdDept($startDates, $endDates,$bioId);
            $nextYears = $years+1;
            // dd($data);
            foreach ($dataBio as $rowBio) {
                // dd($dataBio);
                

                if ($rowBio['date_process'] >= $years.'-01-16' && $rowBio['date_process'] <= $years.'-02-15') {
                    $work = $rowBio['work_day'];
                    if ($work = '' || $work = "" || $work = null) {
                        $work = 0;
                    }
                    $workTotal = $workTotal + $work;
                    echo $alphachar[$numberAlpha];
                    // exit();
                    $spreadsheet->getActiveSheet()->setCellValue($alphachar[$numberAlpha].$rowIdx, $workTotal);
                }
                if ($rowBio['date_process'] >= $years.'-02-16' && $rowBio['date_process'] <= $years.'-03-15') {
                    $work = $rowBio['work_day'];
                    if ($work = '' || $work = "" || $work = null) {
                        $work = 0;
                    }
                    $workTotal = $workTotal + $work;
                    $spreadsheet->getActiveSheet()->setCellValue($alphachar[$numberAlpha].$rowIdx, $workTotal);
                }
                if ($rowBio['date_process'] >= $years.'-03-16' && $rowBio['date_process'] <= $years.'-04-15') {
                    $work = $rowBio['work_day'];
                    if ($work = '' || $work = "" || $work = null) {
                        $work = 0;
                    }
                    $workTotal = $workTotal + $work;
                    $spreadsheet->getActiveSheet()->setCellValue($alphachar[$numberAlpha].$rowIdx, $workTotal);
                }
                if ($rowBio['date_process'] >= $years.'-04-16' && $rowBio['date_process'] <= $years.'-05-15') {
                    $work = $rowBio['work_day'];
                    if ($work = '' || $work = "" || $work = null) {
                        $work = 0;
                    }
                    $workTotal = $workTotal + $work;
                    $spreadsheet->getActiveSheet()->setCellValue($alphachar[$numberAlpha].$rowIdx, $workTotal);
                }
                if ($rowBio['date_process'] >= $years.'-05-16' && $rowBio['date_process'] <= $years.'-06-15') {
                    $work = $rowBio['work_day'];
                    if ($work = '' || $work = "" || $work = null) {
                        $work = 0;
                    }
                    $workTotal = $workTotal + $work;
                    $spreadsheet->getActiveSheet()->setCellValue($alphachar[$numberAlpha].$rowIdx, $workTotal);
                }
                if ($rowBio['date_process'] >= $years.'-06-16' && $rowBio['date_process'] <= $years.'-07-15') {
                    $work = $rowBio['work_day'];
                    if ($work = '' || $work = "" || $work = null) {
                        $work = 0;
                    }
                    $workTotal = $workTotal + $work;
                    $spreadsheet->getActiveSheet()->setCellValue($alphachar[$numberAlpha].$rowIdx, $workTotal);
                }
                if ($rowBio['date_process'] >= $years.'-07-16' && $rowBio['date_process'] <= $years.'-08-15') {
                    $work = $rowBio['work_day'];
                    if ($work = '' || $work = "" || $work = null) {
                        $work = 0;
                    }
                    $workTotal = $workTotal + $work;
                    $spreadsheet->getActiveSheet()->setCellValue($alphachar[$numberAlpha].$rowIdx, $workTotal);
                }
                if ($rowBio['date_process'] >= $years.'-09-16' && $rowBio['date_process'] <= $years.'-10-15') {
                    $work = $rowBio['work_day'];
                    if ($work = '' || $work = "" || $work = null) {
                        $work = 0;
                    }
                    $workTotal = $workTotal + $work;
                    $spreadsheet->getActiveSheet()->setCellValue($alphachar[$numberAlpha].$rowIdx, $workTotal);
                }
                if ($rowBio['date_process'] >= $years.'-10-16' && $rowBio['date_process'] <= $years.'-02-15') {
                    $work = $rowBio['work_day'];
                    if ($work = '' || $work = "" || $work = null) {
                        $work = 0;
                    }
                    $workTotal = $workTotal + $work;
                    $spreadsheet->getActiveSheet()->setCellValue($alphachar[$numberAlpha].$rowIdx, $workTotal);
                }
                
            }
            // $totalWorkDay = $workMay + $workJun + $workJul + $workAug + $workSep + $workOct + $workNov + $workDec + $workJan + $workFeb + $workMar + $workApr;

            $spreadsheet->getActiveSheet()->setCellValue('Q'.$rowIdx, '');
            $spreadsheet->getActiveSheet()->setCellValue('T'.$rowIdx, '=((R'.$rowIdx.'*20)/240)*Q'.$rowIdx);
            // dd($dataBio);
            if($rowIdx % 2 == 1)
            {
                $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':'.$alphachar[$rowHead+5].$rowIdx)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('EAEBAF');             
            } 
            $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':'.$alphachar[$rowHead+5].$rowIdx)->applyFromArray($allBorderStyle);
        }
        $spreadsheet->getActiveSheet()->setCellValue('E'.($rowIdx+2), '=SUM(E'.$startIdx.':E'.$rowIdx.')');
        $spreadsheet->getActiveSheet()->setCellValue('F'.($rowIdx+2), '=SUM(F'.$startIdx.':F'.$rowIdx.')');
        $spreadsheet->getActiveSheet()->setCellValue('G'.($rowIdx+2), '=SUM(G'.$startIdx.':G'.$rowIdx.')');
        $spreadsheet->getActiveSheet()->setCellValue('H'.($rowIdx+2), '=SUM(H'.$startIdx.':H'.$rowIdx.')');
        $spreadsheet->getActiveSheet()->setCellValue('I'.($rowIdx+2), '=SUM(I'.$startIdx.':I'.$rowIdx.')');
        $spreadsheet->getActiveSheet()->setCellValue('J'.($rowIdx+2), '=SUM(J'.$startIdx.':J'.$rowIdx.')');
        $spreadsheet->getActiveSheet()->setCellValue('K'.($rowIdx+2), '=SUM(K'.$startIdx.':K'.$rowIdx.')');
        $spreadsheet->getActiveSheet()->setCellValue('L'.($rowIdx+2), '=SUM(L'.$startIdx.':L'.$rowIdx.')');
        $spreadsheet->getActiveSheet()->setCellValue('M'.($rowIdx+2), '=SUM(M'.$startIdx.':M'.$rowIdx.')');
        $spreadsheet->getActiveSheet()->setCellValue('N'.($rowIdx+2), '=SUM(N'.$startIdx.':N'.$rowIdx.')');
        $spreadsheet->getActiveSheet()->setCellValue('O'.($rowIdx+2), '=SUM(O'.$startIdx.':O'.$rowIdx.')');
        $spreadsheet->getActiveSheet()->setCellValue('P'.($rowIdx+2), '=SUM(P'.$startIdx.':P'.$rowIdx.')');
        $spreadsheet->getActiveSheet()->setCellValue('Q'.($rowIdx+2), '=SUM(Q'.$startIdx.':Q'.$rowIdx.')');
        $spreadsheet->getActiveSheet()->setCellValue('R'.($rowIdx+2), 'TOTAL ========>');
        $spreadsheet->getActiveSheet()->setCellValue('T'.($rowIdx+2), '=SUM(T'.$startIdx.':T'.$rowIdx.')');

        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+2).':'.$alphachar[$rowHead+5].($rowIdx+2))
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('22FF4F');
       
        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+1).':'.$alphachar[$rowHead+5].($rowIdx+1))
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('C0C0C0');

        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+2).':'.$alphachar[$rowHead+5].($rowIdx+2))->applyFromArray($outlineBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+1).':'.$alphachar[$rowHead+5].($rowIdx+1))->applyFromArray($outlineBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle('R'.($rowIdx+2).':S'.($rowIdx+2))->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle('T'.($rowIdx+2))->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+2).':'.$alphachar[$rowHead+5].($rowIdx+2))->getFont()->setBold(true)->setSize(11);
        $spreadsheet->getActiveSheet()->getStyle('R'.($rowIdx+2).':S'.($rowIdx+2))->applyFromArray($center);
        
        $spreadsheet->getActiveSheet()->mergeCells('R'.($rowIdx+2).':S'.($rowIdx+2));

        $spreadsheet->getActiveSheet()
        ->setCellValue('B'.($rowIdx+4), 'Batangtoru, ...............')
        ->setCellValue('B'.($rowIdx+5), 'PT Sangati Soerya Sejahtera')
        ->setCellValue('B'.($rowIdx+11), 'HERI SUSANTO')
        ->setCellValue('B'.($rowIdx+12), 'Direktur')
        ;
        $spreadsheet->getActiveSheet()->getStyle('B'.($rowIdx+4).':B'.($rowIdx+12))->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle('B'.($rowIdx+5).':B'.($rowIdx+11))->getFont()->setBold(true)->setSize(11);
        $spreadsheet->getActiveSheet()->getStyle("B".($rowIdx+11))->getFont()->setBold(true)->setSize(11)->setUnderline(true);

        $spreadsheet->getActiveSheet()
        ->setCellValue('H'.($rowIdx+5), 'Checked By,')
        ->setCellValue('H'.($rowIdx+11), 'Anny Maryam Nasution')
        ->setCellValue('H'.($rowIdx+12), 'Supt. Benefit Compensation')
        ;
        $spreadsheet->getActiveSheet()->getStyle('H'.($rowIdx+5).':J'.($rowIdx+12))->applyFromArray($center);
        $spreadsheet->getActiveSheet()->mergeCells('H'.($rowIdx+5).':J'.($rowIdx+5));
        $spreadsheet->getActiveSheet()->mergeCells('H'.($rowIdx+11).':J'.($rowIdx+11));
        $spreadsheet->getActiveSheet()->mergeCells('H'.($rowIdx+12).':J'.($rowIdx+12));
        $spreadsheet->getActiveSheet()->getStyle('H'.($rowIdx+5).':J'.($rowIdx+11))->getFont()->setBold(true)->setSize(11);
        $spreadsheet->getActiveSheet()->getStyle("H".($rowIdx+11))->getFont()->setBold(true)->setSize(11)->setUnderline(true);

        $spreadsheet->getActiveSheet()
        ->setCellValue('Q'.($rowIdx+4), 'Aprroved By,')
        ->setCellValue('Q'.($rowIdx+5), 'PT. Agincourt Resources')
        ->setCellValue('Q'.($rowIdx+11), 'SANDRA V. MAKADADA')
        ->setCellValue('Q'.($rowIdx+12), 'Sr. Manager Human Capital Development')
        ;
        $spreadsheet->getActiveSheet()->getStyle('Q'.($rowIdx+4).':S'.($rowIdx+12))->applyFromArray($center);
        $spreadsheet->getActiveSheet()->mergeCells('Q'.($rowIdx+4).':S'.($rowIdx+4));
        $spreadsheet->getActiveSheet()->mergeCells('Q'.($rowIdx+5).':S'.($rowIdx+5));
        $spreadsheet->getActiveSheet()->mergeCells('Q'.($rowIdx+11).':S'.($rowIdx+11));
        $spreadsheet->getActiveSheet()->mergeCells('Q'.($rowIdx+12).':S'.($rowIdx+12));
        $spreadsheet->getActiveSheet()->getStyle('Q'.($rowIdx+5).':S'.($rowIdx+11))->getFont()->setBold(true)->setSize(11);
        $spreadsheet->getActiveSheet()->getStyle("Q".($rowIdx+11))->getFont()->setBold(true)->setSize(11)->setUnderline(true);

        unset($allBorderStyle);
        unset($center);
        unset($right);
        unset($left);
        
        // Rename worksheet

        $spreadsheet->getActiveSheet()->setTitle('thr');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        //Nama File
        // $str = $rowData['name'].$rowData['bio_rec_id'];
        $str = $dept.'-'.$endDates.'THR';
        $fileName = preg_replace('/\s+/', '', $str);

        // Redirect output to a clientâ€™s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'.Xlsx"');
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

    public function print_all_dept($startDates, $endDates, $depart){

        $dept       = str_replace("%20"," ",$depart);
        $start_date = new \DateTime($startDates);
        $end_date   = new \DateTime($endDates);
        

        $Model = new M_mtb_thr();
        $data = $Model->getAllDept($startDates, $endDates, $dept);
        // $this->test($data,1);
        $year_date      = substr($endDates, 0,4);

        $startDateIndo  = strtotime($startDates);
        $formatStartIndo= date('d F Y', $startDateIndo);

        $endDateIndo    = strtotime($endDates);
        $formatEndIndo  = date('d F Y', $endDateIndo);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->createSheet();
        $noNewSheet = 1;

        foreach ($data as $key => $value) {

            $startDate = $start_date->format('d');
            $startMonth = $start_date->format('m');
            $startYear = $start_date->format('Y');
            $endDate = $end_date->format('d');
            $endMonth = $end_date->format('m');
            $endYear = $end_date->format('Y');
            $current_date = clone $start_date;
            $interval = new \DateInterval('P1M');

            $spreadsheet->getActiveSheet()
                ->setCellValue('A1', 'SUMMARY TIMESHEET - PERHITUNGAN THR '.$year_date)
                ->setCellValue('A2', 'Site Project Martabe '.$year_date)
                ->setCellValue('A3', 'Periode : '.$formatStartIndo.' -'.$formatEndIndo)
                ->setCellValue('A4', 'Department : '.$value['dept']);

            $spreadsheet->getActiveSheet()->getStyle("A1:T1")->getFont()->setBold(true)->setSize(16);
            $spreadsheet->getActiveSheet()->getStyle("A2:T2")->getFont()->setBold(true)->setSize(16);
            $spreadsheet->getActiveSheet()->getStyle("A3:T3")->getFont()->setBold(true)->setSize(16);
            $spreadsheet->getActiveSheet()->getStyle("A4")->getFont()->setBold(true)->setSize(12);
            //$spreadsheet->getActiveSheet()->getStyle("A4:D4")->getFont()->setBold(true)->setSize(13);
            // $spreadsheet->getActiveSheet()->getStyle("A4:G4")->getFont()->setBold(true)->setSize(12); 

            

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

            /* COLOURING FOOTER */
            $rowHead = 3;
            $alphachar = array_merge(range('A', 'Z'));
            $spreadsheet->getActiveSheet()
                ->setCellValue('A5', 'NO')  
                ->setCellValue('B5', 'NAME')  
                ->setCellValue('C5', 'NO REKENING')  
                ->setCellValue('D5', 'WORKING STATUS') 
            ;

            // $dateStartLoop      = (new \DateTime($startDates))->format('Y-m-01');
            // $dateEndLoop        = (new \DateTime($endDates))->format('Y-m-t');
            // $datetime1 = new \DateTime($dateStartLoop);
            // $datetime2 = new \DateTime($dateEndLoop);

            // $selisih    = (($datetime1->diff($datetime2)->y * 12) + $datetime1->diff($datetime2)->m);

            // $this->test($dateStartLoop.' - '.$dateEndLoop.' = '.$selisih,1);

            $datetime1 = new \DateTime((new \DateTime($startDates))->format('Y-m-01'));
            $datetime2 = new \DateTime((new \DateTime($endDates))->format('Y-m-t'));

            $selisih    = (($datetime1->diff($datetime2)->y * 12) + $datetime1->diff($datetime2)->m + 1);
            // $this->test($selisih,1);

            $date_for_loop = $startDates;
            for ($i=0; $i < $selisih; $i++) { 
                $rowHead++;
                $month_loop     = new \DateTime($date_for_loop);
                $month_name     = $month_loop->format('M-y');
                $spreadsheet->getActiveSheet()->setCellValue($alphachar[$rowHead].'5', $month_name);
                $spreadsheet->getActiveSheet()->getStyle("$alphachar[$rowHead]5:$alphachar[$rowHead]6")->applyFromArray($allBorderStyle);
                $spreadsheet->getActiveSheet()->getStyle("$alphachar[$rowHead]5:$alphachar[$rowHead]6")->applyFromArray($center);
                $spreadsheet->getActiveSheet()->getStyle("$alphachar[$rowHead]5:$alphachar[$rowHead]6")->getAlignment()->setWrapText(true);
                $spreadsheet->getActiveSheet()->mergeCells("$alphachar[$rowHead]5:$alphachar[$rowHead]6");
                $date_for_loop  = date('Y-m-d', strtotime('+1 month', strtotime($date_for_loop)));
            } 
                
            $spreadsheet->getActiveSheet()
                ->mergeCells('A1:'.$alphachar[$rowHead+5].'1')
                ->mergeCells('A2:'.$alphachar[$rowHead+5].'2')
                ->mergeCells('A3:'.$alphachar[$rowHead+5].'3');

            foreach(range('B',$alphachar[$rowHead+5]) as $columnID){
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }

            $spreadsheet->getActiveSheet()
                ->setCellValue($alphachar[$rowHead+1].'5', 'Total Working')  
                ->setCellValue($alphachar[$rowHead+2].'5', 'Rate / Day')  
                ->setCellValue($alphachar[$rowHead+3].'5', 'BASE SALARY')  
                ->setCellValue($alphachar[$rowHead+4].'5', 'TOTAL THR') 
                ->setCellValue($alphachar[$rowHead+5].'5', 'Keterangan') 
            ;


            $spreadsheet->getActiveSheet()->mergeCells($alphachar[$rowHead+1].'5:'.$alphachar[$rowHead+1].'6');
            $spreadsheet->getActiveSheet()->mergeCells($alphachar[$rowHead+2].'5:'.$alphachar[$rowHead+2].'6');
            $spreadsheet->getActiveSheet()->mergeCells($alphachar[$rowHead+3].'5:'.$alphachar[$rowHead+3].'6');
            $spreadsheet->getActiveSheet()->mergeCells($alphachar[$rowHead+4].'5:'.$alphachar[$rowHead+4].'6');
            $spreadsheet->getActiveSheet()->mergeCells($alphachar[$rowHead+5].'5:'.$alphachar[$rowHead+5].'6');
            

            $spreadsheet->getActiveSheet()->getStyle('A5:'.$alphachar[$rowHead+5].'6')->getFont()->setBold(true)->setSize(12);
            $spreadsheet->getActiveSheet()->getStyle('A5:'.$alphachar[$rowHead+5].'6')->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("A5:A6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("B5:B6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("C5:C6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("D5:D6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+1].'5:'.$alphachar[$rowHead+1].'6')->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+2].'5:'.$alphachar[$rowHead+2].'6')->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+3].'5:'.$alphachar[$rowHead+3].'6')->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+4].'5:'.$alphachar[$rowHead+4].'6')->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+5].'5:'.$alphachar[$rowHead+5].'6')->applyFromArray($center);

            $spreadsheet->getActiveSheet()->getStyle('A1:'.$alphachar[$rowHead+5].'3')->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle('A5:'.$alphachar[$rowHead+5].'6')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('FFFF00'); 
             $spreadsheet->getActiveSheet()->getStyle('A7:'.$alphachar[$rowHead+5].'7')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('C0C0C0');



            $spreadsheet->getActiveSheet()
                ->mergeCells("A5:A6")
                ->mergeCells("B5:B6")
                ->mergeCells("C5:C6")
                ->mergeCells("D5:D6")
                ;

            $spreadsheet->getActiveSheet()->getStyle("B5:B6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("C5:C6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle("D5:D6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+1].'5:'.$alphachar[$rowHead+1].'6')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+2].'5:'.$alphachar[$rowHead+2].'6')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+3].'5:'.$alphachar[$rowHead+3].'6')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+4].'5:'.$alphachar[$rowHead+4].'6')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead+5].'5:'.$alphachar[$rowHead+5].'6')->getAlignment()->setWrapText(true);

            $spreadsheet->getActiveSheet()->getStyle('A7:'.$alphachar[$rowHead+5].'7')->applyFromArray($outlineBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle('A7:'.$alphachar[$rowHead+5].'7')->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle('A7:'.$alphachar[$rowHead+5].'7')->getFont()->setBold(true)->setSize(10);
            $spreadsheet->getActiveSheet()->mergeCells('A7:'.$alphachar[$rowHead+5].'7');
            $spreadsheet->getActiveSheet()->setCellValue('A7', 'Formula VlOOKUP to INVOICE EVERY MONTH');

            $rowIdx     = 7;
            $startIdx   = $rowIdx;
            $rowNo      = 0;
            $work       = 0;
            $workTotal  = 0;
            $workDay1   = 0;
            $numberAlpha= 4;



            $data           = $Model->getDataEmpByDept($startDates, $endDates, $value['dept']);
            $total_wd_thr   = 240;
            $total_thr_keseluruhan = 0;
            $total_data     = count($data);

            foreach ($data as $row) {
                $rowIdx++;
                $rowNo++;

                $spreadsheet->getActiveSheet()
                    ->setCellValue('A'.$rowIdx, $rowNo)
                    ->setCellValue('B'.$rowIdx, $row['full_name'])
                    ->setCellValue('C'.$rowIdx, $row['account_no'])
                    ->setCellValue('D'.$rowIdx, $row['status_payroll']);

                $rowHead = 3;
                $alphachar = array_merge(range('A', 'Z'));
                // $this->test($alphachar,1);
                $spreadsheet->getActiveSheet()
                    ->setCellValue('A5', 'NO')  
                    ->setCellValue('B5', 'NAME')  
                    ->setCellValue('C5', 'NO REKENING')  
                    ->setCellValue('D5', 'WORKING STATUS') 
                ;

                $datetime1 = new \DateTime((new \DateTime($startDates))->format('Y-m-01'));
                $datetime2 = new \DateTime((new \DateTime($endDates))->format('Y-m-t'));

                $selisih    = (($datetime1->diff($datetime2)->y * 12) + $datetime1->diff($datetime2)->m + 1);
                // $this->test($selisih,1);


                $date_for_loop = $startDates;
                $total_wd = 0;
                for ($i=0; $i < $selisih; $i++) { 
                    $rowHead++;
                    $month_loop     = new \DateTime($date_for_loop);
                    $month_name     = $month_loop->format('M-y');

                    $start_loop     = $month_loop->format('Y-m').'-01';
                    if($i==0){
                        $start_loop = $date_for_loop;
                    }

                    $end_loop       = $month_loop->format('Y-m-t');
                    if($i==$selisih){
                        $end_loop   = $endDates;                        
                    }

                    $data_wd    = $Model->get_wd_monthly($row['biodata_id'], $start_loop, $end_loop);

                    if($data_wd['wd']<1){
                        $spreadsheet->getActiveSheet()->getStyle($alphachar[$rowHead].$rowIdx)
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('FFC7CE');  
                    }
                    
                    $spreadsheet->getActiveSheet()->setCellValue($alphachar[$rowHead].$rowIdx, $data_wd['wd']);
                    // $spreadsheet->getActiveSheet()->getStyle("$alphachar[$rowHead]$rowIdx:$alphachar[$rowHead]$rowIdx")->applyFromArray($allBorderStyle);
                    // $spreadsheet->getActiveSheet()->getStyle("$alphachar[$rowHead]$rowIdx:$alphachar[$rowHead]$rowIdx")->applyFromArray($center);
                    $spreadsheet->getActiveSheet()->getStyle("$alphachar[$rowHead]$rowIdx:$alphachar[$rowHead]$rowIdx")->getAlignment()->setWrapText(true);
                    $date_for_loop  = date('Y-m-d', strtotime('+1 month', strtotime($date_for_loop)));
                    $total_wd   = $total_wd + $data_wd['wd'];

                    
                } 

                $total_wd_perhitungan   = $total_wd;
                if($total_wd>$total_wd_thr){
                    $total_wd_perhitungan   = 240;
                }

                $total_thr      = ($row['monthly'] * $total_wd_perhitungan ) / $total_wd_thr;

                $keterangan = '';
                if($row['status_payroll']=='Daily'){
                    $keterangan     = 'Harian';
                }elseif($row['status_payroll']=='Monthly'){
                    $keterangan     = 'Bulanan';
                }

                $spreadsheet->getActiveSheet()
                    ->setCellValue($alphachar[$rowHead+1].$rowIdx, $total_wd)
                    ->setCellValue($alphachar[$rowHead+2].$rowIdx, $row['daily'])
                    ->setCellValue($alphachar[$rowHead+3].$rowIdx, $row['monthly'])
                    ->setCellValue($alphachar[$rowHead+4].$rowIdx, $total_thr)
                    ->setCellValue($alphachar[$rowHead+5].$rowIdx, $keterangan);

                $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':'.$alphachar[$rowHead+5].$rowIdx)->applyFromArray($allBorderStyle);

                $total_thr_keseluruhan  = $total_thr_keseluruhan + $total_thr;
            }

            // Start Untuk akhir looping dengan BG Abu2
            $rowIdxLast = $rowIdx;
            $rowIdx     = $rowIdx+1;
            $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':'.$alphachar[$rowHead+5].$rowIdx)->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':'.$alphachar[$rowHead+5].$rowIdx)
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('D0CECE');  
            // End Untuk akhir looping dengan BG Abu2

            // Start Untuk Sum

            $rowHead = 3;
            $rowHeadSum = 4;
            $datetime1 = new \DateTime((new \DateTime($startDates))->format('Y-m-01'));
            $datetime2 = new \DateTime((new \DateTime($endDates))->format('Y-m-t'));

            $selisih    = (($datetime1->diff($datetime2)->y * 12) + $datetime1->diff($datetime2)->m + 1);
            $rowIdx     = $rowIdx+1;
            $date_for_loop = $startDates;
            $startIdx   = $startIdx+1;

            $spreadsheet->getActiveSheet()
                    ->setCellValue('A'.$rowIdx, '')  
                    ->setCellValue('B'.$rowIdx, '')  
                    ->setCellValue('C'.$rowIdx, '')  
                    ->setCellValue('D'.$rowIdx, $total_data)
                ;

            for ($i=0; $i < $selisih; $i++) { 
                $rowHead++;
                $month_loop     = new \DateTime($date_for_loop);
                $month_name     = $month_loop->format('M-y');
                // $spreadsheet->getActiveSheet()->setCellValue($alphachar[$rowHead].$rowIdx, $month_name);
                $spreadsheet->getActiveSheet()->setCellValue($alphachar[$rowHead].$rowIdx, '=SUM('.$alphachar[$rowHead].$startIdx.':'.$alphachar[$rowHead].$rowIdxLast.')');
                // $spreadsheet->getActiveSheet()->setCellValue('E'.($rowIdx+2), '=SUM(E'.$startIdx.':E'.$rowIdx.')');
                $date_for_loop  = date('Y-m-d', strtotime('+1 month', strtotime($date_for_loop)));
            } 

            // End Untuk SUm
            $spreadsheet->getActiveSheet()->setCellValue($alphachar[$rowHead+1].$rowIdx, '=SUM('.$alphachar[$rowHeadSum].$rowIdx.':'.$alphachar[$rowHead].$rowIdx.')');
            $spreadsheet->getActiveSheet()->setCellValue($alphachar[$rowHead+2].$rowIdx, "TOTAL ---------------------->");
            $spreadsheet->getActiveSheet()->mergeCells($alphachar[$rowHead+2].$rowIdx.':'.$alphachar[$rowHead+3].$rowIdx);
            $spreadsheet->getActiveSheet()->setCellValue($alphachar[$rowHead+4].$rowIdx, $total_thr_keseluruhan);

            $spreadsheet->getActiveSheet()->getStyle('A'.$rowIdx.':'.$alphachar[$rowHead+5].$rowIdx)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('99FF33');

            $spreadsheet->getActiveSheet()
                ->setCellValue('B'.($rowIdx+4), 'Batangtoru, ...............')
                ->setCellValue('B'.($rowIdx+5), 'PT Sangati Soerya Sejahtera')
                ->setCellValue('B'.($rowIdx+11), 'HERI SUSANTO')
                ->setCellValue('B'.($rowIdx+12), 'Direktur')
                ;

            $spreadsheet->getActiveSheet()->getStyle('B'.($rowIdx+4).':B'.($rowIdx+12))->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle('B'.($rowIdx+5).':B'.($rowIdx+11))->getFont()->setBold(true)->setSize(11);
            $spreadsheet->getActiveSheet()->getStyle("B".($rowIdx+11))->getFont()->setBold(true)->setSize(11)->setUnderline(true);

            $spreadsheet->getActiveSheet()
                ->setCellValue('H'.($rowIdx+5), 'Checked By,')
                ->setCellValue('H'.($rowIdx+11), 'Anny Maryam Nasution')
                ->setCellValue('H'.($rowIdx+12), 'Supt. Benefit Compensation')
                ;

            $spreadsheet->getActiveSheet()->getStyle('H'.($rowIdx+5).':J'.($rowIdx+12))->applyFromArray($center);
            $spreadsheet->getActiveSheet()->mergeCells('H'.($rowIdx+5).':J'.($rowIdx+5));
            $spreadsheet->getActiveSheet()->mergeCells('H'.($rowIdx+11).':J'.($rowIdx+11));
            $spreadsheet->getActiveSheet()->mergeCells('H'.($rowIdx+12).':J'.($rowIdx+12));
            $spreadsheet->getActiveSheet()->getStyle('H'.($rowIdx+5).':J'.($rowIdx+11))->getFont()->setBold(true)->setSize(11);
            $spreadsheet->getActiveSheet()->getStyle("H".($rowIdx+11))->getFont()->setBold(true)->setSize(11)->setUnderline(true);

            $spreadsheet->getActiveSheet()
                ->setCellValue('Q'.($rowIdx+4), 'Aprroved By,')
                ->setCellValue('Q'.($rowIdx+5), 'PT. Agincourt Resources')
                ->setCellValue('Q'.($rowIdx+11), 'SANDRA V. MAKADADA')
                ->setCellValue('Q'.($rowIdx+12), 'Sr. Manager Human Capital Development')
                ;

            $spreadsheet->getActiveSheet()->getStyle('Q'.($rowIdx+4).':S'.($rowIdx+12))->applyFromArray($center);
            $spreadsheet->getActiveSheet()->mergeCells('Q'.($rowIdx+4).':S'.($rowIdx+4));
            $spreadsheet->getActiveSheet()->mergeCells('Q'.($rowIdx+5).':S'.($rowIdx+5));
            $spreadsheet->getActiveSheet()->mergeCells('Q'.($rowIdx+11).':S'.($rowIdx+11));
            $spreadsheet->getActiveSheet()->mergeCells('Q'.($rowIdx+12).':S'.($rowIdx+12));
            $spreadsheet->getActiveSheet()->getStyle('Q'.($rowIdx+5).':S'.($rowIdx+11))->getFont()->setBold(true)->setSize(11);
            $spreadsheet->getActiveSheet()->getStyle("Q".($rowIdx+11))->getFont()->setBold(true)->setSize(11)->setUnderline(true);

            foreach(range('B',$alphachar[$rowHead+5]) as $columnID){
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }

            $spreadsheet->getActiveSheet()->setTitle($value['dept']);
            $spreadsheet->setActiveSheetIndex($noNewSheet);
            $spreadsheet->createSheet();
            $noNewSheet++;
        }

        $str = $startDates.'-'.$endDates.'THR';
        $fileName = preg_replace('/\s+/', '', $str);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PAYROLL REDPATH TIMIKA '.$fileName.'.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit(0);
    }
}
