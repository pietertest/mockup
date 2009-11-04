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
function smarty_function_userpicture($params, &$smarty)
{
    $defaultPhoto = "no.jpg";
    $photo = (isset($params['photo'])) ? $params['photo'] : $defaultPhoto;
    $username = $params['username'];
    
    $html = "<div class=\"result_photo_container\" >".
			"<div class=\"result_photo\" style=\"background: url(/uploaded/photos/".$photo.") 50% no-repeat\" >".
			"<div style=\"background: url(/images/global/photo_frame.gif) no-repeat; height: 58px; height: 58px;\"></div>".
			"</div>".
			"<a class=\"nick\" href=\"/?page=userprofile&action=view&user=".$username."\">".$username."</a>".
			"</div>";
        
    return $html;
    
}

/* vim: set expandtab: */

?>
