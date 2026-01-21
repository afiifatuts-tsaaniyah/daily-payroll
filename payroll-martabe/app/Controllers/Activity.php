<?php namespace App\Controllers;

use \App\Models\Admin\UserModel;
use \App\Models\Admin\MenuModel;

class Activity extends BaseController
{
	public function __construct()
	{
		helper(['url', 'form', 'security']);
	}

	public function index()
	{
		return view('login');
	}

	public function login()
	{
		if(isset($_POST['inputUser']) && isset($_POST['inputPassword']))
		{
			$userId   = $_POST['inputUser'];
			$userPass = $_POST['inputPassword'];	

			$userModel = new UserModel();
			$userRow = $userModel->where('user_id', $userId)
								 ->where('user_password', $userPass)
								 ->where('is_active', '1');
			$rowCount = $userRow->countAllResults();
			// echo $userModel->getCompiledSelect();
			// echo $this->db->getLastQuery();
			// exit(0);		
			$userData = $userRow->find($userId);  						   
	        if($rowCount > 0)
	        {
	        	/*Get User Group*/
	        	$userGroup = $userData['user_group'];
				/*Set Data To View*/
				$data['userGroup'] = $userGroup;
				$data['userName'] = $userId;
				$data['loginStatus'] = 1;
				$data['actView'] = 'Greetings/greetings';
				/*Create User Session*/
				// $session = \Config\Services::session();

				/*Get Users Menu*/
				// $menuModel = new MenuModel();
				

				// $userMenu = $menuModel->join('trn_user_menu AS um', 'mst_menu.menu_id = um.menu_id', 'inner')
				// 					  ->join('mst_user AS mu', 'um.user_id = mu.user_id', 'inner')
				// 					  ->where('mu.user_id', $userId)
				// 					  ->where('mst_menu.is_active', 1)
				// 					  ->orderBy('group_no, menu_no', 'ASC')
				// 					  ->findAll();

				$userMenu  = $this->userMenu($userId);
				$groupMenu = $this->groupMenu($userId);
				
				$session = session();
				$session->set('uId', $userId);
				$session->set('uGroup', $userGroup);				
				$session->set('uLoginStatus', 1);				
				$session->set('accessMenu', $userMenu);				
				$session->set('actMenu', '');			
				$data['accessMenu'] = $userMenu;	
				$data['groupMenu'] = $groupMenu;	
				$session->set('groupMenu', $groupMenu);			
				$session->set('accessMenu', $userMenu);			
	        	// $this->session->set_userdata('uId', $userId);
	        	// $this->session->set_userdata('uGroup', $userGroup);
				return view('home', $data);
	        }
			else
			{
				return view('login');		
			}
		}
		else 
		{
			return view('login');
		}
	}

	public function logout()
	{
		/*Clear Session*/
		unset(
	        $_SESSION['uId'],
	        $_SESSION['uGroup'],
	        $_SESSION['loginStatus'],
	        $_SESSION['actView']
		);
		// $session->destroy();

		return view('login');	
	}

	


	public function groupMenu($userId)
	{
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
		$menuModel = new MenuModel();
		$userMenu = $menuModel->join('trn_user_menu AS um', 'mst_menu.menu_id = um.menu_id', 'inner')
									  ->join('mst_user AS mu', 'um.user_id = mu.user_id', 'inner')
									  ->where('mu.user_id', $userId)
									  ->where('mst_menu.is_active', 1)
									  ->orderBy('group_no, menu_no', 'ASC')
									  ->findAll();
		return $userMenu; 
	}

	
	public function actMenu($actNenu)
	{
		$data['actView'] = $actView;
		return view('home', $data);
	}
	
	//--------------------------------------------------------------------

}
