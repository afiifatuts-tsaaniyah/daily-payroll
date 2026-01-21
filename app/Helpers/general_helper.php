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

	function encryptionData($emailAdress) {

		 // Store the cipher method 
		$ciphering = "AES-128-CTR"; 
		  
		// Use OpenSSl Encryption method 
		$iv_length = openssl_cipher_iv_length($ciphering); 
		$options = 0; 
		  
		// Non-NULL Initialization Vector for encryption 
		$encryption_iv = '1234567891011121'; 
		  
		// Store the encryption key 
		$encryption_key = "EmailAdress"; 

		// Use openssl_encrypt() function to encrypt the data 
		$encryption = openssl_encrypt($emailAdress, $ciphering, 
            $encryption_key, $options, $encryption_iv); 

		return $encryption;
	} 

	function decryptionData($encryption) {
		// Store the cipher method 
		$ciphering = "AES-128-CTR"; 
		  
		// Non-NULL Initialization Vector for decryption 
		$decryption_iv = '1234567891011121'; 

		// Use OpenSSl Encryption method 
		$iv_length = openssl_cipher_iv_length($ciphering); 
		$options = 0; 
		  
		// Store the decryption key 
		$decryption_key = "EmailAdress";

		// Use openssl_decrypt() function to decrypt the data 
		$decryption=openssl_decrypt ($encryption, $ciphering,  
	        $decryption_key, $options, $decryption_iv); 
		// echo $decryption; exit();	
		return $decryption;

	}


 ?>