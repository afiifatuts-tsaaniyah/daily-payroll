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
                $row['dept'],
                $row['emp_position'],
                "<input type='checkbox' id='vehicle1' name='type' data-bioId = '".$row['biodata_id']."' value='".$row['biodata_id']."' onclick='return add_kode(this)'>",
            );            
        }
        // $this->test($myData,1);
        echo json_encode($myData);    
    }

	public function Download($tahun,$bulan,$startDate,$sm,$biodataId)
	{	
		$mtDownload = new M_mt_biodata();
        // $tahun = $_POST['yearPeriod'];
        // $bulan = $_POST['monthPeriod'];
        // $startDate = $_POST['startDate'];
        // $sm = $_POST['sm'];
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
            $start = $startDate;
            $monthPeriod = $bulan;
            $yearPeriod = $tahun;
            $tanggal = 0;
            $alphachar = array_merge(range('A', 'Z'));
            for ($x = 4; $x <= 96; $x+=3) {
                  $tgl = $start+$tanggal;
                  if ($tgl > cal_days_in_month(CAL_GREGORIAN, $monthPeriod, $yearPeriod)){
                    $tgl = $start+$tanggal - cal_days_in_month(CAL_GREGORIAN, $monthPeriod, $yearPeriod);
                    $monthNow = $monthPeriod +1;
                  }else{
                      $tgl = $start+$tanggal;
                      $monthNow = $monthPeriod;
                  }
                  if ($monthNow == 13) {
                      $yearsnow = $yearPeriod +1;
                      $monthNow = 1;
                  } else {
                      $yearsnow = $yearPeriod;
                      // $monthNow = 1;
                  }
                  $yearss = $tgl.'-'. $monthNow.'-'.$yearsnow;
                  $tanggal++;
                // echo $tanggal.$x;
                  // echo $tanggal.$tgl."-". $monthNow."-".$yearsnow.'</br>';
                if ($x>=1 && $x<=25) {
                    // echo "$alphachar[$x]1 </br>";
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("$alphachar[$x]1", $tgl."-". $monthNow."-".$yearsnow);
                }
                if ($x>=26 && $x<=51) {
                    $b = $x - 26;
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("A$alphachar[$b]1", $tgl."-". $monthNow."-".$yearsnow);
                    // echo "A$x$alphachar[$b]1 </br>";
                    $b++;
                }
                IF ($x>=52 && $x<=77) {
                    $c = $x - 52;
                    // echo "B$c$alphachar[$c]1 </br>";
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("B$alphachar[$c]1", $tgl."-". $monthNow."-".$yearsnow);
                    $c++;
                }
                IF ($x>=78 && $x<=103) {
                    $d = $x - 78;
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("C".$alphachar[$d]."1", $tgl."-". $monthNow."-".$yearsnow);
                    $d++;
                }
            }
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A2', 'BiodataId')
                ->setCellValue('B2', 'Badge No')
                ->setCellValue('C2', 'Full Name')
                ->setCellValue('D2', 'Dept')
                ->setCellValue("E2", "D1")
                ->setCellValue("H2", "D2")
                ->setCellValue("K2", "D3")
                ->setCellValue("N2", "D4")
                ->setCellValue("Q2", "D5")
                ->setCellValue("T2", "D6")
                ->setCellValue("W2", "D7")
                ->setCellValue("Z2", "D8")
                ->setCellValue("AC2", "D9")
                ->setCellValue("AF2", "D10")
                ->setCellValue("AI2", "D11")
                ->setCellValue("AL2", "D12")
                ->setCellValue("AO2", "D13")
                ->setCellValue("AR2", "D14")
                ->setCellValue("AU2", "D15")
                ->setCellValue("AX2", "D16")
                ->setCellValue("BA2", "D17")
                ->setCellValue("BD2", "D18")
                ->setCellValue("BG2", "D19")
                ->setCellValue("BJ2", "D20")
                ->setCellValue("BM2", "D21")
                ->setCellValue("BP2", "D22")
                ->setCellValue("BS2", "D23")
                ->setCellValue("BV2", "D24")
                ->setCellValue("BY2", "D25")
                ->setCellValue("CB2", "D26")
                ->setCellValue("CE2", "D27")
                ->setCellValue("CH2", "D28")
                ->setCellValue("CK2", "D29")
                ->setCellValue("CN2", "D30")
                ->setCellValue("CQ2", "D31")
                ->setCellValue("F2", "IN1")
                ->setCellValue("I2", "IN2")
                ->setCellValue("L2", "IN3")
                ->setCellValue("O2", "IN4")
                ->setCellValue("R2", "IN5")
                ->setCellValue("U2", "IN6")
                ->setCellValue("X2", "IN7")
                ->setCellValue("AA2", "IN8")
                ->setCellValue("AD2", "IN9")
                ->setCellValue("AG2", "IN10")
                ->setCellValue("AJ2", "IN11")
                ->setCellValue("AM2", "IN12")
                ->setCellValue("AP2", "IN13")
                ->setCellValue("AS2", "IN14")
                ->setCellValue("AV2", "IN15")
                ->setCellValue("AY2", "IN16")
                ->setCellValue("BB2", "IN17")
                ->setCellValue("BE2", "IN18")
                ->setCellValue("BH2", "IN19")
                ->setCellValue("BK2", "IN20")
                ->setCellValue("BN2", "IN21")
                ->setCellValue("BQ2", "IN22")
                ->setCellValue("BT2", "IN23")
                ->setCellValue("BW2", "IN24")
                ->setCellValue("BZ2", "IN25")
                ->setCellValue("CC2", "IN26")
                ->setCellValue("CF2", "IN27")
                ->setCellValue("CI2", "IN28")
                ->setCellValue("CL2", "IN29")
                ->setCellValue("CO2", "IN30")
                ->setCellValue("CR2", "IN31")
                ->setCellValue("G2", "OUT1")
                ->setCellValue("J2", "OUT2")
                ->setCellValue("M2", "OUT3")
                ->setCellValue("P2", "OUT4")
                ->setCellValue("S2", "OUT5")
                ->setCellValue("V2", "OUT6")
                ->setCellValue("Y2", "OUT7")
                ->setCellValue("AB2", "OUT8")
                ->setCellValue("AE2", "OUT9")
                ->setCellValue("AH2", "OUT10")
                ->setCellValue("AK2", "OUT11")
                ->setCellValue("AN2", "OUT12")
                ->setCellValue("AQ2", "OUT13")
                ->setCellValue("AT2", "OUT14")
                ->setCellValue("AW2", "OUT15")
                ->setCellValue("AZ2", "OUT16")
                ->setCellValue("BC2", "OUT17")
                ->setCellValue("BF2", "OUT18")
                ->setCellValue("BI2", "OUT19")
                ->setCellValue("BL2", "OUT20")
                ->setCellValue("BO2", "OUT21")
                ->setCellValue("BR2", "OUT22")
                ->setCellValue("BU2", "OUT23")
                ->setCellValue("BX2", "OUT24")
                ->setCellValue("CA2", "OUT25")
                ->setCellValue("CD2", "OUT26")
                ->setCellValue("CG2", "OUT27")
                ->setCellValue("CJ2", "OUT28")
                ->setCellValue("CM2", "OUT29")
                ->setCellValue("CP2", "OUT30")
                ->setCellValue("CS2", "OUT31")
                ;
                $sheet->getStyle("A2")->applyFromArray($style_col);
                $sheet->getStyle("B2")->applyFromArray($style_col);
                $sheet->getStyle("C2")->applyFromArray($style_col);
                $sheet->getStyle("D2")->applyFromArray($style_col);
                $sheet->getStyle("E2")->applyFromArray($style_col);
                $sheet->getStyle("F2")->applyFromArray($style_col);
                $sheet->getStyle("G2")->applyFromArray($style_col);
                $sheet->getStyle("H2")->applyFromArray($style_col);
                $sheet->getStyle("I2")->applyFromArray($style_col);
                $sheet->getStyle("J2")->applyFromArray($style_col);
                $sheet->getStyle("K2")->applyFromArray($style_col);
                $sheet->getStyle("L2")->applyFromArray($style_col);
                $sheet->getStyle("M2")->applyFromArray($style_col);
                $sheet->getStyle("N2")->applyFromArray($style_col);
                $sheet->getStyle("O2")->applyFromArray($style_col);
                $sheet->getStyle("P2")->applyFromArray($style_col);
                $sheet->getStyle("Q2")->applyFromArray($style_col);
                $sheet->getStyle("R2")->applyFromArray($style_col);
                $sheet->getStyle("S2")->applyFromArray($style_col);
                $sheet->getStyle("T2")->applyFromArray($style_col);
                $sheet->getStyle("U2")->applyFromArray($style_col);
                $sheet->getStyle("V2")->applyFromArray($style_col);
                $sheet->getStyle("W2")->applyFromArray($style_col);
                $sheet->getStyle("X2")->applyFromArray($style_col);
                $sheet->getStyle("Y2")->applyFromArray($style_col);
                $sheet->getStyle("Z2")->applyFromArray($style_col);
                $sheet->getStyle("AA2")->applyFromArray($style_col);
                $sheet->getStyle("AB2")->applyFromArray($style_col);
                $sheet->getStyle("AC2")->applyFromArray($style_col);
                $sheet->getStyle("AD2")->applyFromArray($style_col);
                $sheet->getStyle("AE2")->applyFromArray($style_col);
                $sheet->getStyle("AF2")->applyFromArray($style_col);
                $sheet->getStyle("AG2")->applyFromArray($style_col);
                $sheet->getStyle("AH2")->applyFromArray($style_col);
                $sheet->getStyle("AI2")->applyFromArray($style_col);
                $sheet->getStyle("AJ2")->applyFromArray($style_col);
                $sheet->getStyle("AK2")->applyFromArray($style_col);
                $sheet->getStyle("AL2")->applyFromArray($style_col);
                $sheet->getStyle("AM2")->applyFromArray($style_col);
                $sheet->getStyle("AN2")->applyFromArray($style_col);
                $sheet->getStyle("AO2")->applyFromArray($style_col);
                $sheet->getStyle("AP2")->applyFromArray($style_col);
                $sheet->getStyle("AQ2")->applyFromArray($style_col);
                $sheet->getStyle("AR2")->applyFromArray($style_col);
                $sheet->getStyle("AS2")->applyFromArray($style_col);
                $sheet->getStyle("AT2")->applyFromArray($style_col);
                $sheet->getStyle("AU2")->applyFromArray($style_col);
                $sheet->getStyle("AV2")->applyFromArray($style_col);
                $sheet->getStyle("AW2")->applyFromArray($style_col);
                $sheet->getStyle("AX2")->applyFromArray($style_col);
                $sheet->getStyle("AY2")->applyFromArray($style_col);
                $sheet->getStyle("AZ2")->applyFromArray($style_col);
                $sheet->getStyle("BA2")->applyFromArray($style_col);
                $sheet->getStyle("BB2")->applyFromArray($style_col);
                $sheet->getStyle("BC2")->applyFromArray($style_col);
                $sheet->getStyle("BD2")->applyFromArray($style_col);
                $sheet->getStyle("BE2")->applyFromArray($style_col);
                $sheet->getStyle("BF2")->applyFromArray($style_col);
                $sheet->getStyle("BG2")->applyFromArray($style_col);
                $sheet->getStyle("BH2")->applyFromArray($style_col);
                $sheet->getStyle("BI2")->applyFromArray($style_col);
                $sheet->getStyle("BJ2")->applyFromArray($style_col);
                $sheet->getStyle("BK2")->applyFromArray($style_col);
                $sheet->getStyle("BL2")->applyFromArray($style_col);
                $sheet->getStyle("BM2")->applyFromArray($style_col);
                $sheet->getStyle("BN2")->applyFromArray($style_col);
                $sheet->getStyle("BO2")->applyFromArray($style_col);
                $sheet->getStyle("BP2")->applyFromArray($style_col);
                $sheet->getStyle("BQ2")->applyFromArray($style_col);
                $sheet->getStyle("BR2")->applyFromArray($style_col);
                $sheet->getStyle("BS2")->applyFromArray($style_col);
                $sheet->getStyle("BT2")->applyFromArray($style_col);
                $sheet->getStyle("BU2")->applyFromArray($style_col);
                $sheet->getStyle("BV2")->applyFromArray($style_col);
                $sheet->getStyle("BW2")->applyFromArray($style_col);
                $sheet->getStyle("BX2")->applyFromArray($style_col);
                $sheet->getStyle("BY2")->applyFromArray($style_col);
                $sheet->getStyle("BZ2")->applyFromArray($style_col);
                $sheet->getStyle("CA2")->applyFromArray($style_col);
                $sheet->getStyle("CB2")->applyFromArray($style_col);
                $sheet->getStyle("CC2")->applyFromArray($style_col);
                $sheet->getStyle("CD2")->applyFromArray($style_col);
                $sheet->getStyle("CE2")->applyFromArray($style_col);
                $sheet->getStyle("CF2")->applyFromArray($style_col);
                $sheet->getStyle("CG2")->applyFromArray($style_col);
                $sheet->getStyle("CH2")->applyFromArray($style_col);
                $sheet->getStyle("CI2")->applyFromArray($style_col);
                $sheet->getStyle("CJ2")->applyFromArray($style_col);
                $sheet->getStyle("CK2")->applyFromArray($style_col);
                $sheet->getStyle("CL2")->applyFromArray($style_col);
                $sheet->getStyle("CM2")->applyFromArray($style_col);
                $sheet->getStyle("CN2")->applyFromArray($style_col);
                $sheet->getStyle("CO2")->applyFromArray($style_col);
                $sheet->getStyle("CP2")->applyFromArray($style_col);
                $sheet->getStyle("CQ2")->applyFromArray($style_col);
                $sheet->getStyle("CR2")->applyFromArray($style_col);
                $sheet->getStyle("CS2")->applyFromArray($style_col);


            $column = 3;
// tulis data mobil ke cell
        foreach ($bio_id as $key => $value) {
            $dataId = $mtDownload->getById($value);

            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $column, $dataId['biodata_id'])
                        ->setCellValue('C' . $column, $dataId['full_name'])
                        ->setCellValue('D' . $column, $dataId['dept']);
            $spreadsheet->getActiveSheet()->getStyle('A'.$column.':CS'.$column)->applyFromArray($style_row);
            
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