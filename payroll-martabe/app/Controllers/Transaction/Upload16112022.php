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
      $monthProcess = substr($name,14,2);
      $yearProcess = substr($name,10,4);
      $startDate = substr($name,16,2);
	  	$client = substr($name,4,3);
	$sm = substr($name,0,4);
	  	if($ext=='xls')
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
    	foreach($data as $x => $row)
      {
        // dd($row);
  		  if($x == 0)
        {
      	  continue;
    	  }
    		 $upload = new M_upload();
    	   $biodataId = $row[0];
      	 $badgeNo = $row[1];
    		 $fullName = $row[2];
    		 $dept = $row[3];
    		 $d01 = $row[4];
    		 $d02 = $row[5];
    		 $d03 = $row[6];
    		 $d04 = $row[7];
    		 $d05 = $row[8];
    		 $d06 = $row[9];
    		 $d07 = $row[10];
    		 $d08 = $row[11];
    		 $d09 = $row[12];
    		 $d10 = $row[13];
    		 $d11 = $row[14];
    		 $d12 = $row[15];
    		 $d13 = $row[16];
    		 $d14 = $row[17];
    		 $d15 = $row[18];
    		 $d16 = $row[19];
    		 $d17 = $row[20];
    		 $d18 = $row[21];
    		 $d19 = $row[22];
    		 $d20 = $row[23];
    		 $d21 = $row[24];
    		 $d22 = $row[25];
    		 $d23 = $row[26];
    		 $d24 = $row[27];
    		 $d25 = $row[28];
    		 $d26 = $row[29];
    		 $d27 = $row[30];
    		 $d28 = $row[31];
    		 $d29 = $row[32];
    		 $d30 = $row[33];
    		 $d31 = $row[34];		 
    		 

          $headerId = $trTimesheet->generateId('ts_id')['doc_id'];
	  $mtSalary = new M_mt_salary;
	  $mtSalary->setBiodataId($biodataId);
	  $dataSalary =  $mtSalary->getById();
	  $bankcode = $dataSalary['bank_id'];
		
          	$trTimesheet->setbankId($bankcode);
        	$trTimesheet->setTsId($headerId);
         	$trTimesheet->setBiodataId($biodataId);
         	$trTimesheet->setBadgeNo($badgeNo);
         	$trTimesheet->setFullName($fullName);
         	$trTimesheet->setClientName($clientName);
		$trTimesheet->setpayrollGroup($sm);
         	$trTimesheet->setDept($dept);
          	$trTimesheet->setStartDate($startDate);
         	$trTimesheet->setMonthProcess($monthProcess);
         	$trTimesheet->setYearProcess($yearProcess);
         	$trTimesheet->setD01($d01);
         	$trTimesheet->setD02($d02);
         	$trTimesheet->setD03($d03);
         	$trTimesheet->setD04($d04);
         	$trTimesheet->setD05($d05);
         	$trTimesheet->setD06($d06);
         	$trTimesheet->setD07($d07);
         	$trTimesheet->setD08($d08);
         	$trTimesheet->setD09($d09);
         	$trTimesheet->setD10($d10);
         	$trTimesheet->setD11($d11);
         	$trTimesheet->setD12($d12);
         	$trTimesheet->setD13($d13);
         	$trTimesheet->setD14($d14);
         	$trTimesheet->setD15($d15);
         	$trTimesheet->setD16($d16);
         	$trTimesheet->setD17($d17);
         	$trTimesheet->setD18($d18);
         	$trTimesheet->setD19($d19);
         	$trTimesheet->setD20($d20);
         	$trTimesheet->setD21($d21);
         	$trTimesheet->setD22($d22);
         	$trTimesheet->setD23($d23);
         	$trTimesheet->setD24($d24);
         	$trTimesheet->setD25($d25);
         	$trTimesheet->setD26($d26);
         	$trTimesheet->setD27($d27);
         	$trTimesheet->setD28($d28);
         	$trTimesheet->setD29($d29);
         	$trTimesheet->setD30($d30);
         	$trTimesheet->setD31($d31);
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
