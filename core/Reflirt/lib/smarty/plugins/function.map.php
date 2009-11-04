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
function smarty_function_map($params, &$smarty)
{
    $id = $params['id'];
    $style = $params['style'];
    $class = 'mapsTable';
    
    $aAttributes = array();
    if(!empty($style)) {
    	$aAttributes['style'] = $style;
    }
    if (!empty($params['class'])) {
    	$aAttributes['class'] = $params['class'];
    }
    $sAttr = "";
    foreach($aAttributes as $key=>$value) {
    	$sAttr .= $key.'="'.$value.'"';
    }
    
    $html = '<table class="mapsTable" cellpadding="0" cellspacing="0" border="0" id="table_'.$id.'"'.$sAttr.' >
			<tr>
				<td class="mapsCorner_tl"></td>
				<td></td>
				<td class="mapsCorner_tr"></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<div id="'.$id.'" class="userProfileSummaryMap"></div>
				</td>
				<td></td>
			</tr>
			<tr>
				<td class="mapsCorner_bl"></td>
				<td></td>
				<td class="mapsCorner_br"></td>
			</tr>
		</table>';
        
    return $html;
    
}

/* vim: set expandtab: */

?>
