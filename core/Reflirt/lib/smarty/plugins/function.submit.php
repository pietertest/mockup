<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {counter} function plugin
 *
 * Type:     function<br>
 * Name:     counter<br>
 * Purpose:  print out a counter value
 * @link http://smarty.php.net/manual/en/language.function.counter.php {counter}
 *       (Smarty online manual)
 * @param array parameters
 * @param Smarty
 * @return string|null
 */


function smarty_function_submit($params, &$smarty)
{
	require_once $smarty->_get_plugin_filepath('function','button');
	require_once $smarty->_get_plugin_filepath('shared','unique_id');
	
	
	
	$id = uniqueId();
	$params['onclick'] = 'document.getElementById(\''.$id.'\').click()';
	
	$html = createButton($params, $smarty);
	$html .= '<input type="submit" style="margin: -3000px;" id="'.$id.'" />';
	return $html;   
}

/* vim: set expandtab: */

?>