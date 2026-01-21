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
    public function index()
    {
        $mtDept = new M_dept();
        $data['data_dept'] = $mtDept->get_dept();
        $data['actView'] = 'Report/v_mtb_thr';
        return view('home', $data);
    }

    public function getThrListByDept($startDate, $endDate,$depart)
    {
        $dept = str_replace("%20"," ",$depart);
        $Model = new M_mtb_thr();
        $data = $Model->getDataByYears($startDate, $endDate,$dept);
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
        $data = $Model->getAllDept($startDate, $endDate);
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

        /* HEADER */
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath('./assets/images/logo.png');
        $drawing->setCoordinates('G1');
        $drawing->setHeight(48);
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        $spreadsheet->getActiveSheet()
            ->setCellValue('A3', 'PT. SANGATI SOERYA SEJAHTERA')  
            ->setCellValue('A4', 'INVOICES OF OUTSOURCING LABOUR SUPPLY SERVICES IN PT. AGINCOURT RESOURCES')  
            ->setCellValue('A5', 'CONTRACT NO. / KONTRAK NO. ')  
        ;

        /* STYLE HEADER */
        $spreadsheet->getActiveSheet()
            ->mergeCells("A3:G3")
            ->mergeCells("A4:G4")
            ->mergeCells("A5:G5")
        ;

        $spreadsheet->getActiveSheet()->getStyle("A3:A5")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A3:A5")->getFont()->setBold(true)->setSize(12);


        /* COLOURING FOOTER */
        $spreadsheet->getActiveSheet()->getStyle("A10:G13")
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('FFB403');

        $spreadsheet->getActiveSheet()
            ->setCellValue('A10', 'NO')  
            ->setCellValue('B10', 'Department / User')  
            ->setCellValue('C10', 'Cost Code')  
            ->setCellValue('D10', 'Total Empl.')  
            ->setCellValue('E10', 'Total (Rp.)')
            ->setCellValue('F10', 'MANAGAMENT FEE Kontraktor')
            ->setCellValue('F13', '(Rp)')
            ->setCellValue('G10', 'TOTAL INVOICE')
            ->setCellValue('G13', '(Rp)')
        ;
        $spreadsheet->getActiveSheet()->getStyle("A10:A13")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("B10:B13")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("C10:C13")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("D10:D13")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("E10:E13")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("F10:F13")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle("G10:G13")->getFont()->setBold(true)->setSize(12);
        $spreadsheet->getActiveSheet()
            ->mergeCells("A10:A13")
            ->mergeCells("B10:B13")
            ->mergeCells("C10:C13")
            ->mergeCells("D10:D13")
            ->mergeCells("E10:E13")
            ->mergeCells("F10:F12")
            ->mergeCells("G10:G12")
            ->mergeCells("A14:G14")
        ;
        $spreadsheet->getActiveSheet()->getStyle("A10:A13")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("B10:B13")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("C10:C13")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("D10:D13")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("E10:E13")->applyFromArray($allBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("F10:F13")->applyFromArray($outlineBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("G10:G13")->applyFromArray($outlineBorderStyle);
        $spreadsheet->getActiveSheet()->getStyle("A10:A13")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("B10:B13")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("C10:C13")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("D10:D13")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("E10:E13")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("F10:F5")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("F13:F13")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("G10:G5")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("G13:G13")->applyFromArray($center);
        $spreadsheet->getActiveSheet()->getStyle("A10:A13")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("B10:B13")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("C10:C13")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("D10:D13")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("E10:E13")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("F10:F13")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("G10:G13")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5.57);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(38.29);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(8.43);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(17.29);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(17.29);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(17.29);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(17.29);

        $spreadsheet->getActiveSheet()->getStyle('A14:G14')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('C0C0C0');
        $spreadsheet->getActiveSheet()->getStyle("A14:G14")->applyFromArray($outlineBorderStyle);
        

        

        $totalEmpAllDept = 0;
        $thrTotalAllDept = 0;
        $totalBS = 0;
        $no = 0;
        $rowIdx = 14;
        foreach ($data as $row) {
            $rowIdx++;
            $no++;
            $dept = $row['dept'];
            $dataByDept = $Model->getAllEmpByDept($startDate, $endDate,$dept);
            $dataWorkDay = $Model->getTotalWorkDay($startDate, $endDate,$dept);
            $totalWorkDay = $dataWorkDay['WD'];
            $thrTotal = 0;
            foreach ($dataByDept as $row1) {
                // dd($row1);
                $bioId = $row1['biodata_id'];
                $monthly = $row1['monthly'];
                $totalEmp = sizeof($dataByDept);
                $totalBS += $monthly;
            }
            $thr = $totalBS/(240*$totalEmp)*$totalWorkDay;
            // echo $totalBS.'/'.'240x'.$totalEmp.'*'.$totalWorkDay.'='.$thr;
            // exit();
            $thrTotal = $thrTotal + $thr;
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

        /* FOOTER  */
        $spreadsheet->getActiveSheet()
            ->setCellValue('A'.($rowIdx+4), 'Batangtoru, …............................ 2022')  
            ->setCellValue('A'.($rowIdx+5), 'PT Sangati Soerya Sejahtera')  
            ->setCellValue('A'.($rowIdx+12), "HERI SUSANTO")
            ->setCellValue('A'.($rowIdx+13), "Direktur")
        ;

        /* FOOTER STYLE */
        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+4).':B'.($rowIdx+4))->getFont()->setSize(11);
        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+5).':B'.($rowIdx+5))->getFont()->setBold(true)->setSize(11);
        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+12).':B'.($rowIdx+12))->getFont()->setBold(true)->setSize(12)->setUnderline(true);
        $spreadsheet->getActiveSheet()->getStyle('A'.($rowIdx+13).':B'.($rowIdx+13))->getFont()->setSize(11);
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
        $data = $Model->getDataEmpByDept($startDates, $endDates,$dept);
        $spreadsheet = new Spreadsheet();
        $start_date = new \DateTime($startDates);
        $end_date = new \DateTime($endDates);
        $startDate = $start_date->format('d');
        $startMonth = $start_date->format('m');
        $startYear = $start_date->format('Y');
        $endDate = $end_date->format('d');
        $endMonth = $end_date->format('m');
        $endYear = $end_date->format('Y');
        $current_date = clone $start_date;
        $interval = new \DateInterval('P1M');

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
        $rowHead = 3;
        $alphachar = array_merge(range('A', 'Z'));
        $spreadsheet->getActiveSheet()
            ->setCellValue('A5', 'NO')  
            ->setCellValue('B5', 'NAME')  
            ->setCellValue('C5', 'NO REKENING')  
            ->setCellValue('D5', 'WORKING STATUS') 
        ;

        while ($current_date <= $end_date) {
            $month_name = $current_date->format('F');
            $rowHead++;
            $spreadsheet->getActiveSheet()->setCellValue($alphachar[$rowHead].'5', $month_name);
            $spreadsheet->getActiveSheet()->getStyle("$alphachar[$rowHead]5:$alphachar[$rowHead]6")->applyFromArray($allBorderStyle);
            $spreadsheet->getActiveSheet()->getStyle("$alphachar[$rowHead]5:$alphachar[$rowHead]6")->applyFromArray($center);
            $spreadsheet->getActiveSheet()->getStyle("$alphachar[$rowHead]5:$alphachar[$rowHead]6")->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->mergeCells("$alphachar[$rowHead]5:$alphachar[$rowHead]6");
            $current_date->add($interval);
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
        $workDay1 = 0;
        $numberAlpha = 4;

        foreach ($data as $row) {
            $rowIdx++;
            $rowNo++;


            $bioId = $row['biodata_id'];
            if($startDate > 16) {
                $endMonth1 = $startMonth +1;
                $endDate1 = $startYear.'-'.$endMonth1.'-15';
            } else {
                $endDate1 = $startYear.'-'.$startMonth.'-15';
            }
            $dataWd1 = $Model->getWorkDayMonth($startDates, $endDate1, $dept, $bioId);
            $workDay1 = $dataWd1['WD'];
            if ($workDay1 == null) {
                $workDay1 = 0;
            }
            $endMonth = $startMonth + 11;
            $startMonth = $startMonth +1;
            $numberAlpha = 4;
            for ($i = $startMonth; $i < $endMonth; $i++) {
                $numberAlpha++;
                // $startMonth++;
                if ($startMonth > 12) {
                    $startYear = $startYear + 1;
                    $startMonth = $startMonth - 12;
                }
                $start = $startYear.'-'.$startMonth.'-16';
                $startMonth = $startMonth +1;
                if ($startMonth > 12) {
                    $startYear = $startYear + 1;
                    $startMonth = $startMonth - 12;
                }
                $end = $startYear.'-'.$startMonth.'-15';
                $dataWd = $Model->getWorkDayMonth($start, $end, $dept, $bioId);
                $workDay = $dataWd['WD'];
                if ($workDay == null || $workDay == '') {
                    $workDay = 0;
                }
                $spreadsheet->getActiveSheet()->setCellValue($alphachar[$numberAlpha].$rowIdx, $workDay);
            }

            if($startDate > 16) {
                $startMonth1 = $endMonth -1;
                $startDate1 = $endYear.'-'.$startMonth1.'-16';
            } else {
                $startDate1 = $endYear.'-'.$endMonth.'-15';
            }
            $dataWd2 = $Model->getWorkDayMonth($startDate1, $endDates, $dept, $bioId);
            $workDay2 = $dataWd2['WD'];
            if ($workDay2 == null) {
                $workDay2 = 0;
            }
            $spreadsheet->getActiveSheet()
            ->setCellValue('A'.$rowIdx, $rowNo)
            ->setCellValue('B'.$rowIdx, $row['full_name'])
            ->setCellValue('C'.$rowIdx, $row['account_no'])
            ->setCellValue('D'.$rowIdx, $row['status_payroll'])
            ->setCellValue('E'.$rowIdx, $workDay1)
            ->setCellValue('P'.$rowIdx, $workDay2)
            ->setCellValue('Q'.$rowIdx, "=SUM(E$rowIdx:P$rowIdx)")
            ->setCellValue('R'.$rowIdx, $row['daily'])
            ->setCellValue('S'.$rowIdx, $row['monthly']);
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
}
