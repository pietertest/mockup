<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Include the {@link shared.make_timestamp.php} plugin
 */
require_once $smarty->_get_plugin_filepath('shared','make_timestamp');
/**
 * Smarty date_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     date_format<br>
 * Purpose:  format datestamps via strftime<br>
 * Input:<br>
 *         - string: input date string
 *         - format: strftime format for output
 *         - default_date: default date if $string is empty
 * @link http://smarty.php.net/manual/en/language.modifier.date.format.php
 *          date_format (Smarty online manual)
 * @param string
 * @param string
 * @param string
 * @return string|void
 * @uses smarty_make_timestamp()
 */
function smarty_modifier_date_format($string, $format="%b %e, %Y", $default_date=null)
{
    if($format == 'dutch'){
		$maanden = array();
		$maanden[1] = "januari";
		$maanden[2] = "februari";
		$maanden[3] = "maart";
		$maanden[4] = "april";
		$maanden[5] = "mei";
		$maanden[6] = "juni";
		$maanden[7] = "juli";
		$maanden[8] = "augustus";
		$maanden[9] = "september";
		$maanden[10] = "oktober";
		$maanden[11] = "november";
		$maanden[12] = "december";

		$dagen = array();
		$dagen['Mon'] = 'Maandag';
		$dagen['Tue'] = 'Dinsdag';
		$dagen['Wed'] = 'Woensdag';
		$dagen['Thu'] = 'Donderdag';
		$dagen['Fri'] = 'Vrijdag';
		$dagen['Sat'] = 'Zaterdag';
		$dagen['Sun'] = 'Zondag';


		$tijd = mktime();
		$dag = $dagen[date("D", $tijd)];
		$maand = $maanden[date("m", $tijd)*1];
		$jaar = date("Y", $tijd);

		$date_now = $dag." ".date("d", $tijd)." ".$maand.", ".$jaar;
		return $date_now;
	} else if($string != '') {
        return strftime($format, smarty_make_timestamp($string));
    } elseif (isset($default_date) && $default_date != '') {
        return strftime($format, smarty_make_timestamp($default_date));
    } else {
        return;
    }
}

/* vim: set expandtab: */

?>
