<?php
include_once PHP_CLASS.'utils/DebugUtils.class.php';
include_once PHP_CLASS.'exception/ValidationException.class.php';

class Utils{

	
	function parse_line($array){
	   $line = "";
	   foreach($array AS $key => $value){
	       if(is_array($value)){
	           $value = "(". Utils::parse_line($value) . ")";
	       }
	       else
	       {
	           $value = urlencode($value);
	       }
	       $line = $line . urlencode($key) . ":" . $value . '\n';
	   }
	   return $line;
	}

	/**
	 * Sla foutmelding op wanneer deze optreedt en toon een userfriendly exception
	 */
	static function foutafhandeling_opslaan($wrong_query, $mysql_error, $file, $line){
		echo "[foutmelding opslaan implementeren in Utils.php]";
		// Foutafhandeling implementeren
		/*
		db_connect();
		$errorcode = 0;
		$wrong_query = addslashes($wrong_query);
		$mysql_error = addslashes($mysql_error);

		$query = "INSERT INTO foutmelding VALUES('', NOW(), $errorcode, '$wrong_query', '$mysql_error' , '$file', '$line', '0');";
		mysql_query($query) or die(mysql_error());

		db_close();
		global $smarty;
		$smarty->assign('action', 'error');
		$smarty->display('main.tpl');
*/	}

	/**
	 * Van geboortedatum een leeftijd maken
	 */
	static function getAgeByDate($geb_dat){
		$geb_dat = explode('-', $geb_dat);
		$leeftijd = date("Y") - $geb_dat[2];
		$dit_jaar_geb_dat = mktime(0, 0, 0, $geb_dat[1], $geb_dat[0], date("Y"));
		$nu = time();

		if ($nu < $dit_jaar_geb_dat)
		{
			$leeftijd--;
		}
		return $leeftijd;
	}

	static function assertNotEmpty($message, $object, $className = null){
		if($object == null || empty($object) || count($object) == 0){
			$mesg = $message." in '".get_class($className)."'";
			throw new Exception($mesg);
			exit();
		}
	}
	
	static function validateNotEmpty($message, $object, $field = null){
		if($object == null || empty($object) || count($object) == 0){
			throw new ValidationException($message, $field);
		}
	}
	
	static function validateTrue($message, $value, $field = null){
		if(!$value){
			throw new ValidationException($message, $field);
		}
	}

	static function assertNotNull($message, $object, $className = null){
		if($object == null) {
			throw new Exception($message);
		}
	}

	static function assertTrue($message, $value) {
		if(!$value){
			throw new Exception($message);
		}
	}

	static function startsWith($mystring, $findme) {
		$pos = strpos($mystring, $findme);
		return $pos !== FALSE && $pos == 0;
	}

	static function endsWith($mystring, $findme) {
		$mystringLen = strlen($mystring);
		$findmeLen = strlen($findme);
		$pos = strpos($mystring, $findme);
		if(($mystringLen - $findmeLen) == $pos && $pos !== FALSE) {
			return true;
		}
		return false;
	}

	function dateShiftDays($days){
		$date  = mktime(0, 0, 0, date("m")  , date("d")+$days, date("Y"));
		return date("Y-n-d",$date);
	}

	function getPaginationUrl(){
		$url = "";
		$counter = 0;
		foreach($_GET as $key=>$value){
			if($counter == 0){
				$url .= "?".$key."=".$value;
			}
			else if($key != "next"){
				$url .= "&".$key."=".$value;
			}
			$counter++;
		}
		return $url;
	}

	/**
	 * Check of een entity null is
	 */
	static function isEmpty($s) {
		return ($s == "" || strlen($s) == 0 || $s == null);
	}
	
	public static function isInt($val) {
		if($val == "0") {
			return true;
		}
		$temp = intval($val);
		if($temp == 0) { // Er zit een letter in
			return false; 
		}
		return !Utils::isEmpty($val);
	}
	
	public static function getArrayForSex() {
    	$sex = array();
    	
//    	if($plural) {
//	    	$sex = array(
//				"0"		=> "Vrouwen",
//				"1"		=> "Mannen"
//			);
//    	} else {
		$sex = array(
			""	=> "",
			"0"		=> "Vrouw",
			"1"		=> "Man"
		);
//    	}
//    	if($firstRow != null) {
//			$sex = array_merge($firstRow, $sex);
//		}
    	return $sex;
    }
    
    /**
     * Geeft een array terug met waardes van de user een de keys uit de mapping 
     */
    public static function doFieldMapping(Map $ds, array $mapping) {
    	$result = array();
    	$a = $ds->getFields();
    	foreach($mapping as $key=>$value) {
    		if (isset($a[$key])) {
    			$result[$value] = $a[$key];
    		}
    	}
    	return $result;
    }
    
    function generatePassword($length=9, $strength=0) {
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength & 1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEUY";
		}
		if ($strength & 4) {
			$consonants .= '23456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%';
		}
	 
		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
	return $password;
}
    



}

?>
