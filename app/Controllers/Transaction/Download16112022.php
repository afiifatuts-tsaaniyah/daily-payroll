<?php namespace App\Controllers\Transaction;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use App\Models\Transaction\M_download;
use App\Models\Master\M_mt_biodata;


class Download extends BaseController
{
	// private $userModel = null; 
	// public function __construct()
	// {
	// 	$userModel = new UserModel();
	// }
	public function index()
	{
				
		$mtdownload = new M_download();
        // $data['mtBiodata'] = $mtdownload->getAll();
        $data['mtdownload'] = $mtdownload->paginate(1000);		
		$data['actView'] = 'Transaction/download';	
		return view('home', $data);
	}

    public function loadData()
    {        
        $loadData = new M_mt_biodata;
        $rows = $loadData->getAll();
        $myData = array();

        foreach ($rows as $row) {
            $myData[] = array(
                // $row['salary_id'], kek man
                $row['biodata_id'],       
                $row['full_name'],
                $row['emp_position'],
                "<input type='checkbox' id='vehicle1' name='type' value='".$row['biodata_id']."'>",
            );            
        }
        // $this->test($myData,1);
        echo json_encode($myData);    
    }

	public function Download($tahun,$bulan,$startDate,$sm,$biodataId)
	{	
		$mtDownload = new M_mt_biodata();
        $bio_id = explode(',',$biodataId);
        
        
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
                'top' => [
                	'borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                	'color' => ['argb' => '00000000'],
                ], // Set border top dengan garis tipis
                'right' => [
                	'borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                	'color' => ['argb' => '00000000'],
                ],  // Set border right dengan garis tipis
                'bottom' => [
                	'borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                	'color' => ['argb' => '00000000'],
                ],// Set border bottom dengan garis tipis
                'left' => [
                	'borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                	'color' => ['argb' => '00000000'],
                ],
                 // Set border left dengan garis tipis
                'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ]  
            ]

            ];
// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
            $style_row = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
                ],
                'borders' => [
                    'top' => [
                    	'borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    	'color' => ['argb' => '00000000'],
                    ], // Set border top dengan garis tipis
                    'right' => [
                    	'borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    	'color' => ['argb' => '00000000'],
                    ],  // Set border right dengan garis tipis
                    'bottom' => [
                    	'borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    	'color' => ['argb' => '00000000'],
                    ],// Set border bottom dengan garis tipis
                    'left' => [
                    	'borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    	'color' => ['argb' => '00000000'],
                    ],
                     // Set border left dengan garis tipis
                    'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ]  
            ]
            ];
 // Set font size 15 untuk kolom A1
		// echo $this->request->getVar('yearPeriod');
		// exit();
    // tulis header/nama kolom 
            $spreadsheet->setActiveSheetIndex(0)
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
                ->setCellValue('AI1', 'D31');
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


            $column = 2;
// tulis data mobil ke cell
        foreach ($bio_id as $key => $value) {
            $dataId = $mtDownload->getById($value);

            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $column, $dataId['biodata_id'])
                        ->setCellValue('C' . $column, $dataId['full_name'])
                        ->setCellValue('D' . $column, $dataId['dept']);
            $spreadsheet->getActiveSheet()->getStyle('A'.$column.':AI'.$column)->applyFromArray($style_row);
            
            $column++;

                
        }

        $writer = new Xls($spreadsheet);
        $fileName = 'AGRDYL';
        $year = $tahun;
        $month = $bulan;



        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename='.$sm.$fileName.$year.$month.$startDate.'.xls');
        header('Cache-Control: max-age=0');


        $writer->save('php://output');
	
	}


	public function getAll()
     {
          $mtdownload = new M_download();
          $rs = $mtdownload->getAll();
          return json_encode($rs);
     }

}	