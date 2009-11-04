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


function smarty_function_round_select($params, &$smarty)
{
	require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');
	require_once $smarty->_get_plugin_filepath('shared','unique_id');
	require_once $smarty->_get_plugin_filepath('function','html_options');
	    
    $id = null;
    $name = null;
    $class = 'roundTextfield';
    $field = null;
    $value = '';
    $extra = '';
    $style = '';
    
    foreach($params as $_key=>$_value) {
    	switch($_key) {
    		case 'id':
    		case 'name':
    		case 'field':
    		case 'value':
    			$$_key = $_value;
    			break;
    			
    		case 'width':
    			$style .= 'width: '.$_value.'px;';
    			break;
    			
    		case 'class':
    			$class .= ' '.$_value;
    			break;
    		
    		default:
    			$extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_value).'"';
    			break;
    	}
    }
    
    if ($id == null) {
    	$id = uniqueId();
    }
    
    if($field){
		$name = $field;
		$value = $smarty->get_template_vars($name);
	}
    $params['style'] = $style;
    $params['extra'] = $extra;
	$html = '<div class="'.$class.'"><div class="tfStart">'
			.
			smarty_function_html_options($params, $smarty)
	
				
			.'
			<div style="border: 2px solid white;margin-left: 5px; margin-top: -23px; line-height: 19px;">&nbsp;</div>
			</div>
			<div class="tfEnd"></div></div>';
    
            
    return $html;
    
}

/* vim: set expandtab: */

?>