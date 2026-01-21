<?php

	function GetCurrentDate() {

		$db = \Config\Database::connect();

		$strSql  = "SELECT DATE_FORMAT(NOW(),'%Y') CurrentYear,DATE_FORMAT(NOW(),'%m') CurrentMonth,DATE_FORMAT(NOW(),'%d') CurrentDay,";
		$strSql .= "DATE_FORMAT(NOW(),'%Y-%m-%d') CurrentDate, ";
		$strSql .= "DATE_FORMAT(NOW(),'%Y-%m-%d %H:%i:%s') CurrentDateTime, ";
		$strSql .= "DATE_FORMAT(NOW(),'%h-%i-%s') CurrentTime ";
		$query = $db->query($strSql);
        return $query->getRowArray();
	}

	function GetDbVersion() {
		$db = \Config\Database::connect();
		
		$strSql  = "SELECT VERSION() dbVersion";
		$query = $db->query($strSql)->getRowArray();
        return $query['dbVersion'];
	}

	function GetDbLastDayOfMonth($date) {
		$db = \Config\Database::connect();
		
		$strSql  = "SELECT DAY(LAST_DAY('".$date."')) lastDate";
		$query = $ci->db->query($strSql)->getRowArray();
        return $query['lastDate'];
	}

	function left($str, $length) {
	     return substr($str, 0, $length);
	}

	function right($str, $length) {
	     return substr($str, -$length);
	}

 ?>