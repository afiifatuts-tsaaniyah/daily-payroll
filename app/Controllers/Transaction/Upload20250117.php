<?php namespace App\Controllers\Transaction;

require 'vendor/autoload.php';
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use App\Models\Transaction\M_upload;
use App\Models\Transaction\M_proses;
use App\Models\Transaction\M_tr_overtime;
use App\Models\Transaction\M_tr_timesheet;
use App\Models\Master\M_mt_salary;
use App\Models\Transaction\M_tr_slip;



	class Upload extends BaseController
	{

		public function __construct()
		{
			helper('form');
			$this->proses = new M_proses();
			/*$this->load->model('Transaction\M_upload');
			$this->load->model('M_mt_salary');*/
		}
		
		public function index()
		{
			$upload = new M_upload();
			$data['upload'] = [];
			$data['actView'] = 'Transaction/upload';	
			return view('home', $data);
		}

		public function getAll()
     {
          $upload = new M_upload();
          $rs = $upload->getAll();
          return json_encode($rs);
     }
  public function Upload()
  {
    $trTimesheet = new M_tr_timesheet();
		$userId = $_SESSION['uId'];
    $currDateTm = date("Y-m-d H:i:s");
		$validation = \Config\Services::validation();
    
    // $trTimesheet->deleteByIdPeriod($monthProcess, $yearProcess, $startDate);
		$valid = $this->validate
    (
  		[
  		  'fileimport' => 
        [
      		'label' => 'Inputan File',
      		'rules' => 'uploaded[fileimport]|ext_in[fileimport,xls,xlsx]',
      		'errors' => 
          [
        		'uploaded' =>'{field} wajib diisi',
        		'ext_in' => '{field} harus ekstensi xls $ xlsx'
  		    ]
  		  ]
  		]
		);

		if (!$valid) 
    {	  	
  		$this->session->setFlashdata('errors', $validation->getError('fileimport'));
  		return redirect()->to(base_url('Transaction/Upload'));
		}
    else
    {
	  	$file_excel = $this->request->getFile('fileimport');
	  	$ext = $file_excel->getClientExtension();
	  	$name = $file_excel->getClientName();
      $sub = substr($name,2,3);
      // echo $sub;
      // exit();
      if ($sub > 99) {
        $sm = substr($name,0,5);
        $monthProcess = substr($name,15,2);
        $yearProcess = substr($name,11,4);
        $startDate = substr($name,17,2);
        $client = substr($name,5,3);
      } else if ($sub < 100) {
        $sm = substr($name,0,4);
        $monthProcess = substr($name,14,2);
        $yearProcess = substr($name,10,4);
        $startDate = substr($name,16,2);
        $client = substr($name,4,3);
      }	  	if($ext=='xls')
      {	
	  		$render 	= new \PhpOffice\PhpSpreadsheet\Reader\Xls();
		  } 
      else 
      {
  			$render 	= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
  		}

	  	$spreadsheet = $render->load($file_excel);

	  	$data = $spreadsheet->getActiveSheet()->toArray();
    	$dana['upload'] = $data;
      $dana['actView'] = 'Transaction/upload';
      // dd($data);
  		if($client == "AGR") 
      {
  		 $clientName = "agincourt";
  		}

      $out01 = 0;
      $out02 = 0;
      $out03 = 0;
      $out04 = 0;
      $out05 = 0;
      $out06 = 0;
      $out07 = 0;
      $out08 = 0;
      $out09 = 0;
      $out010 = 0;
      $out011 = 0;
      $out012 = 0;
      $out013 = 0;
      $out014 = 0;
      $out015 = 0;
      $out016 = 0;
      $out017 = 0;
      $out018 = 0;
      $out019 = 0;
      $out020 = 0;
      $out021 = 0;
      $out022 = 0;
      $out023 = 0;
      $out024 = 0;
      $out025 = 0;
      $out026 = 0;
      $out027 = 0;
      $out028 = 0;
      $out029 = 0;
      $out030 = 0;
      $out031 = 0;
         // dd($data);
    	foreach($data as $x => $row)
      {

  		  if($x == 0 || $x == 1)
        {
      	  continue;
    	  }
        // dd($row);
    		 $upload = new M_upload();
         $dateProcess = "$yearProcess-$monthProcess-$startDate";
         // echo "$dateProcess";
         // exit();
    	   $biodataId = $row[0];
      	 $badgeNo = $row[1];
    		 $fullName = $row[2];
    		 $dept = $row[3];
    		 $in01 = $row[5] ;
         $d01 = $row[4] ;
        $d02 = $row[7] ;
        $d03 = $row[10] ;
        $d04 = $row[13] ;
        $d05 = $row[16] ;
        $d06 = $row[19] ;
        $d07 = $row[22] ;
        $d08 = $row[25] ;
        $d09 = $row[28] ;
        $d10 = $row[31] ;
        $d11 = $row[34] ;
        $d12 = $row[37] ;
        $d13 = $row[40] ;
        $d14 = $row[43] ;
        $d15 = $row[46] ;
        $d16 = $row[49] ;
        $d17 = $row[52] ;
        $d18 = $row[55] ;
        $d19 = $row[58] ;
        $d20 = $row[61] ;
        $d21 = $row[64] ;
        $d22 = $row[67] ;
        $d23 = $row[70] ;
        $d24 = $row[73] ;
        $d25 = $row[76] ;
        $d26 = $row[79] ;
        $d27 = $row[82] ;
        $d28 = $row[85] ;
        $d29 = $row[88] ;
        $d30 = $row[91] ;
        $d31 = $row[94] ;
        $in02 = $row[8] ;
        $in03 = $row[11] ;
        $in04 = $row[14] ;
        $in05 = $row[17] ;
        $in06 = $row[20] ;
        $in07 = $row[23] ;
        $in08 = $row[26] ;
        $in09 = $row[29] ;
        $in10 = $row[32] ;
        $in11 = $row[35] ;
        $in12 = $row[38] ;
        $in13 = $row[41] ;
        $in14 = $row[44] ;
        $in15 = $row[47] ;
        $in16 = $row[50] ;
        $in17 = $row[53] ;
        $in18 = $row[56] ;
        $in19 = $row[59] ;
        $in20 = $row[62] ;
        $in21 = $row[65] ;
        $in22 = $row[68] ;
        $in23 = $row[71] ;
        $in24 = $row[74] ;
        $in25 = $row[77] ;
        $in26 = $row[80] ;
        $in27 = $row[83] ;
        $in28 = $row[86] ;
        $in29 = $row[89] ;
        $in30 = $row[92] ;
        $in31 = $row[95] ;
         $out1= $row[6];
          $out2= $row[9];
          $out3= $row[12];
          $out4= $row[15];
          $out5= $row[18];
          $out6= $row[21];
          $out7= $row[24];
          $out8= $row[27];
          $out9= $row[30];
          $out10= $row[33];
          $out11= $row[36];
          $out12= $row[39];
          $out13= $row[42];
          $out14= $row[45];
          $out15= $row[48];
          $out16= $row[51];
          $out17= $row[54];
          $out18= $row[57];
          $out19= $row[60];
          $out20= $row[63];
          $out21= $row[66];
          $out22= $row[69];
          $out23= $row[72];
          $out24= $row[75];
          $out25= $row[78];
          $out26= $row[81];
          $out27= $row[84];
          $out28= $row[87];
          $out29= $row[90];
          $out30= $row[93];
          $out31= $row[96];		
         // $hours01 = substr($day01,2,4);
         // $hours02 = substr($day02,2,4);
         // $hours03 = substr($day03,2,4);
         // $hours04 = substr($day04,2,4);
         // $hours05 = substr($day05,2,4);
         // $hours06 = substr($day06,2,4);
         // $hours07 = substr($day07,2,4);
         // $hours08 = substr($day08,2,4);
         // $hours09 = substr($day09,2,4);
         // $hours10 = substr($day10,2,4);
         // $hours11 = substr($day11,2,4);
         // $hours12 = substr($day12,2,4);
         // $hours13 = substr($day13,2,4);
         // $hours14 = substr($day14,2,4);
         // $hours15 = substr($day15,2,4);
         // $hours16 = substr($day16,2,4);
         // $hours17 = substr($day17,2,4);
         // $hours18 = substr($day18,2,4);
         // $hours19 = substr($day19,2,4);
         // $hours20 = substr($day20,2,4);
         // $hours21 = substr($day21,2,4);
         // $hours22 = substr($day22,2,4);
         // $hours23 = substr($day23,2,4);
         // $hours24 = substr($day24,2,4);
         // $hours25 = substr($day25,2,4);
         // $hours26 = substr($day26,2,4);
         // $hours27 = substr($day27,2,4);
         // $hours28 = substr($day28,2,4);
         // $hours29 = substr($day29,2,4);
         // $hours30 = substr($day30,2,4);
         // $hours31 = substr($day31,2,4);

         // $hoursin01 = substr($in01,0,2);
         // $hoursin02 = substr($in02,0,2);
         // $hoursin03 = substr($in03,0,2);
         // $hoursin04 = substr($in04,0,2);
         // $hoursin05 = substr($in05,0,2);
         // $hoursin06 = substr($in06,0,2);
         // $hoursin07 = substr($in07,0,2);
         // $hoursin08 = substr($in08,0,2);
         // $hoursin09 = substr($in09,0,2);
         // $hoursin10 = substr($in10,0,2);
         // $hoursin11 = substr($in11,0,2);
         // $hoursin12 = substr($in12,0,2);
         // $hoursin13 = substr($in13,0,2);
         // $hoursin14 = substr($in14,0,2);
         // $hoursin15 = substr($in15,0,2);
         // $hoursin16 = substr($in16,0,2);
         // $hoursin17 = substr($in17,0,2);
         // $hoursin18 = substr($in18,0,2);
         // $hoursin19 = substr($in19,0,2);
         // $hoursin20 = substr($in20,0,2);
         // $hoursin21 = substr($in21,0,2);
         // $hoursin22 = substr($in22,0,2);
         // $hoursin23 = substr($in23,0,2);
         // $hoursin24 = substr($in24,0,2);
         // $hoursin25 = substr($in25,0,2);
         // $hoursin26 = substr($in26,0,2);
         // $hoursin27 = substr($in27,0,2);
         // $hoursin28 = substr($in28,0,2);
         // $hoursin29 = substr($in29,0,2);
         // $hoursin30 = substr($in30,0,2);
         // $hoursin31 = substr($in31,0,2);
         
//          if ($hoursin01||$hoursin02||$hoursin03||$hoursin04||$hoursin05||$hoursin06||$hoursin07||$hoursin08||$hoursin09||$hoursin10||$hoursin11||$hoursin12||$hoursin13||$hoursin14||$hoursin15||$hoursin16||$hoursin17||$hoursin18||$hoursin19|
// $hoursin2||$hoursin21||$hoursin22||$hoursin23||$hoursin24||$hoursin25||$hoursin26||$hoursin27||$hoursin28||$hoursin29||$hoursin30||$hoursin31) {
//            $hoursin01 = '0';
// $hoursin02 = '0';
// $hoursin03 = '0';
// $hoursin04 = '0';
// $hoursin05 = '0';
// $hoursin06 = '0';
// $hoursin07 = '0';
// $hoursin08 = '0';
// $hoursin09 = '0';
// $hoursin10 = '0';
// $hoursin11 = '0';
// $hoursin12 = '0';
// $hoursin13 = '0';
// $hoursin14 = '0';
// $hoursin15 = '0';
// $hoursin16 = '0';
// $hoursin17 = '0';
// $hoursin18 = '0';
// $hoursin19 = '0';
// $hoursin20 = '0';
// $hoursin21 = '0';
// $hoursin22 = '0';
// $hoursin23 = '0';
// $hoursin24 = '0';
// $hoursin25 = '0';
// $hoursin26 = '0';
// $hoursin27 = '0';
// $hoursin28 = '0';
// $hoursin29 = '0';
// $hoursin30 = '0';
// $hoursin31 = '0';
//          }

//         $out01 = $hoursin01 - $hours01;
//         $out02 = $hoursin02 - $hours02;
//         $out03 = $hoursin03 - $hours03;
//         $out04 = $hoursin04 - $hours04;
//         $out05 = $hoursin05 - $hours05;
//         $out06 = $hoursin06 - $hours06;
//         $out07 = $hoursin07 - $hours07;
//         $out08 = $hoursin08 - $hours08;
//         $out09 = $hoursin09 - $hours09;
//         $out10 = $hoursin10 - $hours10;
//         $out11 = $hoursin11 - $hours11;
//         $out12 = $hoursin12 - $hours12;
//         $out13 = $hoursin13 - $hours13;
//         $out14 = $hoursin14 - $hours14;
//         $out15 = $hoursin15 - $hours15;
//         $out16 = $hoursin16 - $hours16;
//         $out17 = $hoursin17 - $hours17;
//         $out18 = $hoursin18 - $hours18;
//         $out19 = $hoursin19 - $hours19;
//         $out20 = $hoursin20 - $hours20;
//         $out21 = $hoursin21 - $hours21;
//         $out22 = $hoursin22 - $hours22;
//         $out23 = $hoursin23 - $hours23;
//         $out24 = $hoursin24 - $hours24;
//         $out25 = $hoursin25 - $hours25;
//         $out26 = $hoursin26 - $hours26;
//         $out27 = $hoursin27 - $hours27;
//         $out28 = $hoursin28 - $hours28;
//         $out29 = $hoursin29 - $hours29;
//         $out30 = $hoursin30 - $hours30;
//         $out31 = $hoursin31 - $hours31;

          $out01 = $out1;
          $out02 = $out2;
          $out03 = $out3;
          $out04 = $out4;
          $out05 = $out5;
          $out06 = $out6;
          $out07 = $out7;
          $out08 = $out8;
          $out09 = $out9;
          $out010 = $out10;
          $out011 = $out11;
          $out012 = $out12;
          $out013 = $out13;
          $out014 = $out14;
          $out015 = $out15;
          $out016 = $out16;
          $out017 = $out17;
          $out018 = $out18;
          $out019 = $out19;
          $out020 = $out20;
          $out021 = $out21;
          $out022 = $out22;
          $out023 = $out23;
          $out024 = $out24;
          $out025 = $out25;
          $out026 = $out26;
          $out027 = $out27;
          $out028 = $out28;
          $out029 = $out29;
          $out030 = $out30;
          $out031 = $out31;

          if($out1 < $in01) { $out01 = $out1 + 24; }
          if($out2 < $in02) { $out02 = $out2 + 24; }
          if($out3 < $in03) { $out03 = $out3 + 24; }
          if($out4 < $in04) { $out04 = $out4 + 24; }
          if($out5 < $in05) { $out05 = $out5 + 24; }
          if($out6 < $in06) { $out06 = $out6 + 24; }
          if($out7 < $in07) { $out07 = $out7 + 24; }
          if($out8 < $in08) { $out08 = $out8 + 24; }
          if($out9 < $in09) { $out09 = $out9 + 24; }
          if($out10 < $in10) { $out010 = $out10 + 24; }
          if($out11 < $in11) { $out011 = $out11 + 24; }
          if($out12 < $in12) { $out012 = $out12 + 24; }
          if($out13 < $in13) { $out013 = $out13 + 24; }
          if($out14 < $in14) { $out014 = $out14 + 24; }
          if($out15 < $in15) { $out015 = $out15 + 24; }
          if($out16 < $in16) { $out016 = $out16 + 24; }
          if($out17 < $in17) { $out017 = $out17 + 24; }
          if($out18 < $in18) { $out018 = $out18 + 24; }
          if($out19 < $in19) { $out019 = $out19 + 24; }
          if($out20 < $in20) { $out020 = $out20 + 24; }
          if($out21 < $in21) { $out021 = $out21 + 24; }
          if($out22 < $in22) { $out022 = $out22 + 24; }
          if($out23 < $in23) { $out023 = $out23 + 24; }
          if($out24 < $in24) { $out024 = $out24 + 24; }
          if($out25 < $in25) { $out025 = $out25 + 24; }
          if($out26 < $in26) { $out026 = $out26 + 24; }
          if($out27 < $in27) { $out027 = $out27 + 24; }
          if($out28 < $in28) { $out028 = $out28 + 24; }
          if($out29 < $in29) { $out029 = $out29 + 24; }
          if($out30 < $in30) { $out030 = $out30 + 24; }
          if($out31 < $in31) { $out031 = $out31 + 24; } 

          $time1 = $out01 - $in01 - 1;
          $time2 = $out02 - $in02 - 1;
          $time3 = $out03 - $in03 - 1;
          $time4 = $out04 - $in04 - 1;
          $time5 = $out05 - $in05 - 1;
          $time6 = $out06 - $in06 - 1;
          $time7 = $out07 - $in07 - 1;
          $time8 = $out08 - $in08 - 1;
          $time9 = $out09 - $in09 - 1;
          $time10 = $out010 - $in10 - 1;
          $time11 = $out011 - $in11 - 1;
          $time12 = $out012 - $in12 - 1;
          $time13 = $out013 - $in13 - 1;
          $time14 = $out014 - $in14 - 1;
          $time15 = $out015 - $in15 - 1;
          $time16 = $out016 - $in16 - 1;
          $time17 = $out017 - $in17 - 1;
          $time18 = $out018 - $in18 - 1;
          $time19 = $out019 - $in19 - 1;
          $time20 = $out020 - $in20 - 1;
          $time21 = $out021 - $in21 - 1;
          $time22 = $out022 - $in22 - 1;
          $time23 = $out023 - $in23 - 1;
          $time24 = $out024 - $in24 - 1;
          $time25 = $out025 - $in25 - 1;
          $time26 = $out026 - $in26 - 1;
          $time27 = $out027 - $in27 - 1;
          $time28 = $out028 - $in28 - 1;
          $time29 = $out029 - $in29 - 1;
          $time30 = $out030 - $in30 - 1;
          $time31 = $out031 - $in31 - 1;

          if ($time1 <= 0) {
            $time1 = '';
          }
          if ($time2 <= 0) {
            $time2 = '';
          }
          if ($time3 <= 0) {
            $time3 = '';
          }
          if ($time4 <= 0) {
            $time4 = '';
          }
          if ($time5 <= 0) {
            $time5 = '';
          }
          if ($time6 <= 0) {
            $time6 = '';
          }
          if ($time7 <= 0) {
            $time7 = '';
          }
          if ($time8 <= 0) {
            $time8 = '';
          }
          if ($time9 <= 0) {
            $time9 = '';
          }
          if ($time10 <= 0) {
            $time10 = '';
          }
          if ($time11 <= 0) {
            $time11 = '';
          }
          if ($time12 <= 0) {
            $time12 = '';
          }
          if ($time13 <= 0) {
            $time13 = '';
          }
          if ($time14 <= 0) {
            $time14 = '';
          }
          if ($time15 <= 0) {
            $time15 = '';
          }
          if ($time16 <= 0) {
            $time16 = '';
          }
          if ($time17 <= 0) {
            $time17 = '';
          }
          if ($time18 <= 0) {
            $time18 = '';
          }
          if ($time19 <= 0) {
            $time19 = '';
          }
          if ($time20 <= 0) {
            $time20 = '';
          }
          if ($time21 <= 0) {
            $time21 = '';
          }
          if ($time22 <= 0) {
            $time22 = '';
          }
          if ($time23 <= 0) {
            $time23 = '';
          }
          if ($time24 <= 0) {
            $time24 = '';
          }
          if ($time25 <= 0) {
            $time25 = '';
          }
          if ($time26 <= 0) {
            $time26 = '';
          }
          if ($time27 <= 0) {
            $time27 = '';
          }
          if ($time28 <= 0) {
            $time28 = '';
          }
          if ($time29 <= 0) {
            $time29 = '';
          }
          if ($time30 <= 0) {
            $time30 = '';
          }
          if ($time31 <= 0) {
            $time31 = '';
          }
          // dd($out01);
          $day1 = "$d01".$time1;
          $day2 = "$d02".$time2;
          $day3 = "$d03".$time3;
          $day4 = "$d04".$time4;
          $day5 = "$d05".$time5;
          $day6 = "$d06".$time6;
          $day7 = "$d07".$time7;
          $day8 = "$d08".$time8;
          $day9 = "$d09".$time9;
          $day10 = "$d10".$time10;
          $day11 = "$d11".$time11;
          $day12 = "$d12".$time12;
          $day13 = "$d13".$time13;
          $day14 = "$d14".$time14;
          $day15 = "$d15".$time15;
          $day16 = "$d16".$time16;
          $day17 = "$d17".$time17;
          $day18 = "$d18".$time18;
          $day19 = "$d19".$time19;
          $day20 = "$d20".$time20;
          $day21 = "$d21".$time21;
          $day22 = "$d22".$time22;
          $day23 = "$d23".$time23;
          $day24 = "$d24".$time24;
          $day25 = "$d25".$time25;
          $day26 = "$d26".$time26;
          $day27 = "$d27".$time27;
          $day28 = "$d28".$time28;
          $day29 = "$d29".$time29;
          $day30 = "$d30".$time30;
          $day31 = "$d31".$time31;


          $headerId = $trTimesheet->generateId('ts_id')['doc_id'];
          $mtSalary = new M_mt_salary;
        	$trTimesheet->setTsId($headerId);
         	$trTimesheet->setBiodataId($biodataId);
          $mtSalary->setBiodataId($biodataId);
          $dataSalary =  $mtSalary->getByBioId($biodataId);
          $bankcode = $dataSalary['bank_id'];
          $trTimesheet->setbankId($bankcode);
         	$trTimesheet->setBadgeNo($badgeNo);
         	$trTimesheet->setFullName($fullName);
         	$trTimesheet->setClientName($clientName);
		      $trTimesheet->setpayrollGroup($sm);
         	$trTimesheet->setDept($dept);
          $trTimesheet->setdateProcess($dateProcess);
          $trTimesheet->setStartDate($startDate);
         	$trTimesheet->setMonthProcess($monthProcess);
         	$trTimesheet->setYearProcess($yearProcess);
         	$trTimesheet->setD01($day1);
         	$trTimesheet->setIN01($in01);
         	$trTimesheet->setD02($day2);
         	$trTimesheet->setIN02($in02);
         	$trTimesheet->setD03($day3);
         	$trTimesheet->setIN03($in03);
         	$trTimesheet->setD04($day4);
         	$trTimesheet->setIN04($in04);
         	$trTimesheet->setD05($day5);
         	$trTimesheet->setIN05($in05);
         	$trTimesheet->setD06($day6);
         	$trTimesheet->setIN06($in06);
         	$trTimesheet->setD07($day7);
         	$trTimesheet->setIN07($in07);
         	$trTimesheet->setD08($day8);
         	$trTimesheet->setIN08($in08);
         	$trTimesheet->setD09($day9);
         	$trTimesheet->setIN09($in09);
         	$trTimesheet->setD10($day10);
         	$trTimesheet->setIN10($in10);
         	$trTimesheet->setD11($day11);
         	$trTimesheet->setIN11($in11);
         	$trTimesheet->setD12($day12);
         	$trTimesheet->setIN12($in12);
         	$trTimesheet->setD13($day13);
         	$trTimesheet->setIN13($in13);
         	$trTimesheet->setD14($day14);
         	$trTimesheet->setIN14($in14);
         	$trTimesheet->setD15($day15);
         	$trTimesheet->setIN15($in15);
         	$trTimesheet->setD16($day16);
          $trTimesheet->setIN16($in16);
          $trTimesheet->setD17($day17);
          $trTimesheet->setIN17($in17);
          $trTimesheet->setD18($day18);
          $trTimesheet->setIN18($in18);
          $trTimesheet->setD19($day19);
          $trTimesheet->setIN19($in19);
          $trTimesheet->setD20($day20);
          $trTimesheet->setIN20($in20);
          $trTimesheet->setD21($day21);
          $trTimesheet->setIN21($in21);
          $trTimesheet->setD22($day22);
          $trTimesheet->setIN22($in22);
          $trTimesheet->setD23($day23);
          $trTimesheet->setIN23($in23);
          $trTimesheet->setD24($day24);
          $trTimesheet->setIN24($in24);
          $trTimesheet->setD25($day25);
          $trTimesheet->setIN25($in25);
          $trTimesheet->setD26($day26);
          $trTimesheet->setIN26($in26);
          $trTimesheet->setD27($day27);
          $trTimesheet->setIN27($in27);
          $trTimesheet->setD28($day28);
          $trTimesheet->setIN28($in28);
          $trTimesheet->setD29($day29);
          $trTimesheet->setIN29($in29);
          $trTimesheet->setD30($day30);
          $trTimesheet->setIN30($in30);
          $trTimesheet->setD31($day31);
          $trTimesheet->setIN31($in31);
          $trTimesheet->setout01($out1);
          $trTimesheet->setout02($out2);
          $trTimesheet->setout03($out3);
          $trTimesheet->setout04($out4);
          $trTimesheet->setout05($out5);
          $trTimesheet->setout06($out6);
          $trTimesheet->setout07($out7);
          $trTimesheet->setout08($out8);
          $trTimesheet->setout09($out9);
          $trTimesheet->setout10($out10);
          $trTimesheet->setout11($out11);
          $trTimesheet->setout12($out12);
          $trTimesheet->setout13($out13);
          $trTimesheet->setout14($out14);
          $trTimesheet->setout15($out15);
          $trTimesheet->setout16($out16);
          $trTimesheet->setout17($out17);
          $trTimesheet->setout18($out18);
          $trTimesheet->setout19($out19);
          $trTimesheet->setout20($out20);
          $trTimesheet->setout21($out21);
          $trTimesheet->setout22($out22);
          $trTimesheet->setout23($out23);
          $trTimesheet->setout24($out24);
          $trTimesheet->setout25($out25);
          $trTimesheet->setout26($out26);
          $trTimesheet->setout27($out27);
          $trTimesheet->setout28($out28);
          $trTimesheet->setout29($out29);
          $trTimesheet->setout30($out30);
          $trTimesheet->setout31($out31);
         	$trTimesheet->setPicProcess($userId);
         	$trTimesheet->setProcessTime($currDateTm);
          $trTimesheet->ins();

          

         /* echo $trTimesheet->setDept($dept);*/


		  }
      // exit();
    		 session()->setFlashdata('pesan', 'Data Berhasil Di Import !!');
    		 return view('home',$dana);
		}
  }
}
