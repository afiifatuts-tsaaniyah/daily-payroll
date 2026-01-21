<?php namespace App\Models\Transaction;
use CodeIgniter\Model;

/**
 * 
 */
class M_proses extends Model
{
	 protected $DBGroup = 'hris';
     protected $table = 'tr_timesheet';/*
     protected $primaryKey = 'ts_id';*/
     protected $returnType = 'array';
	
	function Proses()
	{
		$stQuery = "SELECT a.*,b.monthly,daily FROM tr_timesheet a , mt_salary b WHERE a.biodata_id=b.biodata_id";
		$query = $this->db->query($stQuery)->getResultArray();
		return $query;
		/*echo $stQuery;*/
		/*return $query;*/
	}
}