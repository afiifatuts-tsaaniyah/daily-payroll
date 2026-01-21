<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use \App\Models\Admin\UserModel;

class Users extends BaseController
{
	// private $userModel = null; 
	// public function __construct()
	// {
	// 	$userModel = new UserModel();
	// }
	public function index()
	{				
		$data['actView'] = 'Admin/users';
		return view('home', $data);
	}

	public function insData()
	{
		$userModel = new UserModel();
		$userModel->resetValues();
		$userModel->setUserId('adm');
		$userModel->setNip('2112');
		$userModel->setFullName('Administrator');
		$userModel->setUserName('Admin');
		$userModel->setUserPassword('asd');
		$userModel->setIsActive(1);
		echo $userModel->ins();
	}

	public function getAll()
	{
		$userModel = new UserModel();
		$rs = $userModel->getAll();
		return json_encode($rs);
	}

	public function getAllDataTable()
	{
		$userModel = new UserModel();
		$rs = $userModel->getAll();
		
		$myData = array();
        foreach ($rs as $row) {
            $myData[] = array(
              $row['user_id'], 
              $row['user_password'], 
              $row['full_name'],
              $row['user_level']
            ); 
        }
		
		return json_encode($myData);
	}

}
