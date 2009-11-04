<?php

/*@version $Id*/

if(!function_exists('bcdiv')){
	function bcdiv($denominator, $nominator, $precision = 0){
		$result = $denominator / $nominator;
		$temp = split("\\.",$result);
		$result = $temp[0].".".substr($temp[1], 0, $precision);
		if($precision == 0){
			$result = substr($result, 0, -1);
		}
		if(strlen($temp[1]) == 0 && $precision > 0){
			for($i = 0; $i < $precision; $i++){
				$result .= "0";
			}
		}
		return $result;
	}
}

?>
