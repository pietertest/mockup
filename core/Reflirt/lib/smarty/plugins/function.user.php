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
function smarty_function_user($params, &$smarty)
{
    $defaultPhoto = "no.jpg";
    $photo = (isset($params['photo'])) ? $params['photo'] : $defaultPhoto;
    $username = $params['username'];
    $city = $params['city'];
    
    $html = '<div class="userAndCityContainer">' .
    			'<div class="photo" style="background: url(/uploaded/photos/'.$photo.') 50% no-repeat"></div>' .
    			'<div><a class="username" href="/?page=userprofile&action=view&user='.$username.'">'.$username.'</a></div>' .
    			'<div class="city">'.$city.'</div>' .
    			'<div class="left"></div>' .
    		'</div>';
    		
        
    return $html;
    
}

/* vim: set expandtab: */

?>
