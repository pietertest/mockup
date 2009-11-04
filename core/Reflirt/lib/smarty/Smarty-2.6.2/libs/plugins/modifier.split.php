<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty truncate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     split<br>
 * Purpose:  Maak een array van een string. Doe dit door
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php
 *          truncate (Smarty online manual)
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @return string
 */
function smarty_modifier_split($string, $split1, $split2="")
{	
		$string_array = explode($split1, $string);
		if($split2!=""){
			$return_array = array();
			foreach($string_array as $waarde){
					$temp = explode($split2, $waarde);
					$return_array[$temp[0]] = $temp[1];
			}
			return $return_array;
		}
		else{
			return $string_array;
		}
}
?>
