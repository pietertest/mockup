<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty singular modifier plugin
 *
 * Type:     modifier<br>
 * Name:     singular<br>
 * Purpose:  append a label to a string that is a number or a number OR 
 * 			in the case of an array, count and append label
 *			return singular or plural form based on numeric input .
 *			also, can count array if @ sign is prepended as in normal smarty usage.
 * 			defaults to appending basic s modifier to singular form for creating plurals.
 * 			but many times the plural is more complicated than a simple s, so plural can be specified too.
 * 			
 * @link http://smarty.incutio.com/?page=SingularPlugin
 *          singular (Smarty Wiki)
 * @param string OR integer OR array
 * @param string
 * @param string
 * @return string
 *
 * @author dc   d a n.cynosure   aht   d b m s c a n    daht   calm
 * 
 * @examples 
 * {assign var='year' value=4}
 * {$year|singular:' year'}
 *
 * {assign var='shelf' value='1'}
 * {$shelf|singular:' shelf':' shelves'}
 *
 * Count ARRAY items using @ sign
 * {$shelf|@singular:' shelf':' shelves'}
 */
function smarty_modifier_singular($arr_or_num, $singular_form='', $plural_form='')
{
 	// no plural form sent, just concat s to singular form
 	if ($plural_form == '') {
 		$plural_form = $singular_form . 's';	
 	}
    // what datatype we are dealing with
 	// if modifier is prepended with @ sign we get an array
 	// count it, concatenate string to the item we counted
 	if (is_array($arr_or_num)) {
    	$num = count($arr_or_num);
    	$form = ($num==1)?$singular_form:$plural_form;
    }
    if (is_numeric($arr_or_num)) {
    	$num = $arr_or_num;
    	$form = ($arr_or_num == 1)?$singular_form:$plural_form;;
    }
    return $num . $form;
}

/* vim: set expandtab: */

?>