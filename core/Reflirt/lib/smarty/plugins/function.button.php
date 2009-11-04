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


function smarty_function_button($params, &$smarty)
{
	return createButton($params, $smarty);   
}

function createButton($params, &$smarty) {
	require_once $smarty->_get_plugin_filepath('shared','unique_id');
	$id = null;
    $name = null;
    $class = 'button';
    $value = null;
    
    $extra = '';
    
    foreach($params as $_key=>$_value) {
    	switch($_key) {
    		case 'value':
    			$$_key = $_value;
    			break;
    			
    		case 'class':
    			$class .= ' '.$_value;
    			break;

    		default:
    			$extra .= ' '.$_key.'="'.$_value.'"';
    			break;
    	}
    }
    
	$html = '<div class="'.$class.'" '.$extra.'"><div class="btStart">'.$value.'</div>
			<div class="btEnd"></div></div>';
			
    return $html;
}
/* vim: set expandtab: */

?>