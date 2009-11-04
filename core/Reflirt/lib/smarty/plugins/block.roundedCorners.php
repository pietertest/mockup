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


function smarty_block_roundedCorners($params, $content, &$smarty)
{
	
	$width = $params["width"] ? $params["width"] : "100%";
	   $html =  <<<EOD
	   <table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td class="roundedCorners_tl">&nbsp;</td>
		<td class="roundedCorners_tm">&nbsp;</td>
		<td class="roundedCorners_tr">&nbsp;</td>
	</tr>
	<tr>
		<td class="roundedCorners_ml">&nbsp;</td>
		<td class="roundedCorners_mm">
			$content
		</td>
		<td class="roundedCorners_mr">&nbsp;</td>
	</tr>
	<tr>
		<td class="roundedCorners_bl">&nbsp;</td>
		<td class="roundedCorners_bm">&nbsp;</td>
		<td class="roundedCorners_br">&nbsp;</td>
	</tr>
</table>
EOD;
	return $html;
}

/* vim: set expandtab: */

?>