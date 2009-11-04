
<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_checkboxes} function plugin
 *
 * File:       function.phpl_checkboxes.php<br>
 * Type:       function<br>
 * Name:       html_checkboxes<br>
 * Date:       24.Feb.2003<br>
 * Purpose:    Prints out a list of checkbox input types<br>
 * Input:<br>
 *           - name       (optional) - string default "checkbox"
 *           - values     (required) - array
 *           - options    (optional) - associative array
 *           - checked    (optional) - array default not set
 *           - separator  (optional) - ie <br> or &nbsp;
 *           - output     (optional) - without this one the buttons don't have names
 * Examples:
 * <pre>
 * {html_checkboxes values=$ids output=$names}
 * {html_checkboxes values=$ids name='box' separator='<br>' output=$names}
 * {html_checkboxes values=$ids checked=$checked separator='<br>' output=$names}
 * </pre>
 * @link http://smarty.php.net/manual/en/language.function.phpl.checkboxes.php {html_checkboxes}
 *      (Smarty online manual)
 * @author     Christopher Kvarme <christopher.kvarme@flashjab.com>
 * @author credits to Monte Ohrt <monte@ispi.net>
 * @version    1.0
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */
function smarty_function_html_text($params, &$smarty)
{
	$params['type'] = "text";
	return createInput($params, $smarty);
}

function createInput($params, &$smarty) {
	
	require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');
	
    $name = null;
    $value = null;
    $type = null;
    $value = null;
    $default = null;
    $field = null;
    $required = null;
    
	$extra = '';

    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'name':
            case 'default':
            case 'required':
            case 'field':
            case 'value':
            case 'type':
                $$_key = $_val;
                break;
            	
            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_input: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }
    
	if($field){
		$name = $field;
		$value = $smarty->get_template_vars($name);
	}
    
    $_html_result = '';

    $_html_result .= smarty_function_html_input_output($type, $name, $value, $extra, $default);

    return $_html_result;
	
}

function smarty_function_html_input_output($type, $name, $value, $extra, $default) {
    $_output = '';
    $color = '';
    $_output .= '<input type="'.$type.'" name="'
        . smarty_function_escape_special_chars($name) . '" value="'
        . smarty_function_escape_special_chars($value) . '"';
    $_output .= $extra . ' />';
    return $_output;
}

?>
