<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty lower modifier plugin
 *
 * Type:     modifier<br>
 * Name:     lower<br>
 * Purpose:  convert string to lowercase
 * @link http://smarty.php.net/manual/en/language.modifier.lower.php
 *          lower (Smarty online manual)
 * @param string
 * @return string
 */
function smarty_modifier_br2nl($string)
{
    /* Remove XHTML linebreak tags. */
   $string = str_replace("<br />","",$string);
   /* Remove HTML 4.01 linebreak tags. */
   $string = str_replace("<br>","",$string);
   /* Return the result. */
   return $string;
}

?>
