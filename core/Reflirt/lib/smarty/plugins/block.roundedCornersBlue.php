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


function smarty_block_roundedCornersBlue($params, $content, &$smarty)
{
	$style= "";
	$class = "roundedPanelBlue";
	foreach($params as $_key=>$_value) {
		switch($_key) {
			case 'style' : 
				$style = $_value;
				break;
				
			case 'class' : 
				$class .= " " .	$_value;
				break;
		}
	}
	$html =  <<<EOD
	  		<table cellpassing="0" cellspacing="0" class="$class" border="0" style="$style">
			<tr>
				<td class="panelLeftTop">&nbsp;</td>
				<td class="panelMiddleTop">&nbsp;</td>
				<td class="panelRightTop">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					$content
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="panelLeftBottom">&nbsp;</td>
				<td class="panelMiddleTop">&nbsp;</td>
				<td class="panelRightBottom">&nbsp;</td>
			</tr>
		</table>
EOD;
	return $html;
}

/* vim: set expandtab: */

?>