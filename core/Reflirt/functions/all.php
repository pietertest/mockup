<?php


/**
 * De server draait op een php versie waar je geen beschikking hebt
 * tot sommige functies. Deze heb je niet lokaal (mits Easyphp package
 * van pieter:). Deze functies dus sowieso gebruiken in de reflirt
 * omgeving (op de server dus), maar niet in LOCAL omgeving (dat botst).
 */

if(IS_PRODUCTION){
	include(BASEDIR."functions/function_datetime.php");
	include(BASEDIR."functions/function_bcdiv.php");
	include(BASEDIR."functions/function_json_encode.php");
}

?>
