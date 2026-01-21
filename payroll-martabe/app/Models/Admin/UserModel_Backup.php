<?php namespace App\Models\Admin;

use CodeIgniter\Model;
// use CodeIgniter\Database\ConnectionInterface;

class UserModel extends Model
{
	protected $DBGroup = 'admin';
	protected $table      = 'mst_user';
    protected $primaryKey = 'user_id';
    protected $returnType     = 'array';

    public function getAll()
    {
        $db = \Config\Database::connect($DBGroup);
        // $strSql = "SELECT * FROM mst_user";
        $strSql  = "SELECT user_id, user_password, full_name, user_level FROM mst_user ";
        $query = $db->query($strSql);
        $arrayList = $query->getResultArray();
        return $arrayList;
    }

    public function getById($id)
    {
        $db = \Config\Database::connect($DBGroup);
        $strSql  = "SELECT * FROM mst_user ";
        $strSql .= "WHERE user_id = '".$id."' ";
        // echo $strSql; exit();
        $query = $db->query($strSql);
        $arrayRow = $query->getRowArray();
        return $arrayRow;
    }

}