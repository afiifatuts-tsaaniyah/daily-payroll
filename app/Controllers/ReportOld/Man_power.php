<?php 
namespace App\Controllers\Report;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\Master\M_dept;

class Man_power extends BaseController
{public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->M_dept = new M_dept();
    }
    public function index()
    {
        /* ***Using Valid Path */
        $data['actView']  = 'Report/v_man_power';
        $data['request']  = $this->request;
        $data['mst_dept'] = $this->M_dept->get_dept();
        $data['db'] =  $this->db;
        return view('home', $data);
    }

    public function data()
    {
        $dept = $this->request->getPost('dept');

        $where = '';
        if($dept != 'ALL') {
            $where = 'WHERE dept LIKE "%'.$dept.'%"';
        }
		$rs = $this->db->query("SELECT full_name, dept, emp_position FROM mt_biodata_01 ".$where)->getResultArray();
		$myData = array();
        foreach ($rs as $row) {
            $myData[] = array(
              $row['full_name'], 
              $row['dept'], 
              $row['emp_position'],
            ); 
        }
		
		return json_encode($myData);
    }
}