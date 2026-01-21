<?php namespace App\Controllers;

header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
   header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
   header('Access-Control-Allow-Headers: Content-Type');
   exit;
}
// use \App\Controllers\BaseController;

use \App\Models\Admin\UserModel;
use \App\Models\Admin\MenuModel;
class Home extends BaseController
{
	public function __construct()
	{
		helper(['url', 'form', 'security']);
	}

	public function index()
	{
		// $this->cachePage(100);
		$data['actView'] = 'Greetings/greetings';
		return view('home', $data);
		// return view('login');
		// $data['welcome'] = 'Hello';
		// $this->template('login', $data); 
	}		

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

	public function login()
	{
		if(isset($_POST['inputUser']) && isset($_POST['inputPassword']))
		{
			$userId   = $_POST['inputUser'];
			$userPass = md5($_POST['inputPassword']);

			$userModel = new UserModel();
			$userRow = $userModel->where('user_name', $userId)
								 ->where('new_user_password', $userPass)
								 ->where('is_active', '1');
			$rowCount = $userRow->countAllResults();
			// $this->test($rowCount,1);

			$userData = $userRow->find($userId); //untuk ambil user_id karena value dan primarykey di model berbeda//
			// $this->test($userData,1);

			// echo 'Hello'; exit(0);						   
	      if($rowCount > 0)
	        {
				$tId = $userData['user_id']; //untuk ngambil user_id//
	        	/*Get User Group*/
	        	$userGroup = $userData['full_name'];
				/*Set Data To View*/
				$data['userGroup'] = $userGroup;
				$data['userName'] = $userId;
				$data['loginStatus'] = 1;
				$data['actView'] = 'Greetings/greetings';
				/*Create User Session*/

				$userMenu  = $this->userMenu($tId);
				$groupMenu = $this->groupMenu($tId);
				/*Get Users Menu*/
				$session = session();
				$session->set('uId', $userId);
				$session->set('tId', $tId);
				$session->set('uGroup', $userGroup);				
				$session->set('loginStatus', 1);				
				$session->set('accessMenu', $userMenu);				
				$data['accessMenu'] = $userMenu;	
				$data['groupMenu'] = $groupMenu;	
				$session->set('groupMenu', $groupMenu);			
				$session->set('accessMenu', $userMenu);			
				return view('home', $data);
	        }
			else
			{
				$this->session->setFlashdata('errors', 'Username Atau Password Anda Salah !!');
				return view('login');		
			}
		}
		else 
		{
			$this->session->setFlashdata('errors', 'Username Atau Password Tidak Boleh Kosong !!');
			return view('login');
		}
	}

	public function logout()
	{
		/*Clear Session*/
		$session = session();
		$data['loginStatus'] = 0;
		unset(
	        $_SESSION['uId'],
	        $_SESSION['uGroup'],
	        $_SESSION['loginStatus'],
	        $_SESSION['actView'],
	        $_SESSION['groupMenu'],
	        $_SESSION['accessMenu']
		);
		// $session->destroy();

		return view('login');	
	}


	public function groupMenu($userId)
	{
		/* PostgreSQL Only  */
		// $strSql  ="SELECT DISTINCT ON (mm.group_no) * ";
		// $strSql .="FROM mst_menu mm, trn_user_menu tu, mst_user mu ";
		// $strSql .="WHERE mm.menu_id = tu.menu_id ";
		// $strSql .="AND tu.user_id = mu.user_id ";
		// $strSql .="AND mu.user_id = '".$userId."' ";

		// $db = \Config\Database::connect('admin');
		// $query = $db->query($strSql);
		// $groupMenu = $query->getResultArray();
		// return $groupMenu;

		
		
		/* MySQL */
		$menuModel = new MenuModel();
		$groupMenu = $menuModel->join('trn_user_menu AS um', 'mst_menu.menu_id = um.menu_id', 'inner')
									  ->join('mst_user AS mu', 'um.user_id = mu.user_id', 'inner')
									  ->where('mu.user_id', $userId)
									  ->where('mst_menu.is_active', 1)
									  ->groupBy('group_no')
									  ->orderBy('group_no, menu_no', 'ASC')
									  ->findAll();
		return $groupMenu; 
	}

	public function userMenu($userId)
	{
		// $userId = $admin->escapeString($userId);	
		$strSql  = "SELECT * FROM  ";
		$strSql .="mst_menu mm, trn_user_menu tu, mst_user mu ";
		$strSql .="WHERE mm.menu_id = tu.menu_id ";
		$strSql .="AND tu.user_id = mu.user_id ";
		$strSql .="AND mu.user_id = '".$userId."' ";
		$strSql .="ORDER BY mm.group_no, mm.menu_no ASC ";
		// echo $strSql; exit();
		$db = \Config\Database::connect();
		$query = $db->query($strSql);
		$userMenu = $query->getResultArray();
		return $userMenu; 		

		// $menuModel = new MenuModel();
		// $userMenu = $menuModel->join('trn_user_menu AS um', 'mst_menu.menu_id = um.menu_id', 'inner')
		// 							  ->join('mst_user AS mu', 'um.user_id = mu.user_id', 'inner')
		// 							  ->where('mu.user_id', $userId)
		// 							  ->where('mst_menu.is_active', 1)
		// 							  ->orderBy('group_no, menu_no', 'ASC')
		// 							  ->findAll();
		// return $userMenu; 
	}

	public function checkValid()
	{
		$this->session->setFlashdata('errors', 'Session Anda Telah Habis Silahkan Login!!');
			return view('login');
	}

	
	public function actMenu($actNenu)
	{
		$data['actView'] = $actView;
		return view('home', $data);
	}

	public function check()
	{
		$userModel = new UserModel();
		$rs = $userModel->getAll();
		// $rs = $userModel->getById('tmk');
		echo "<pre>";
		print_r($rs);
		echo "</pre>";
	}
	
	//--------------------------------------------------------------------

}
