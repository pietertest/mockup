<?php
//$sLastModified = "Fri, 07 Mar 2008 01:19:00 GMT";
//header("Expires: Mon, 27 Jul 2020 05:00:00 GMT");
//header('Cache-Control: max-age=290304000, public');
//if ($_SERVER['HTTP_IF_MODIFIED_SINCE'] == $sLastModified) {
//	if (php_sapi_name()=='CGI') {
//		header("Status: 304 Not Modified");
//	} else {
//	header("HTTP/1.0 304 Not Modified");
//	}
//	exit;
//}
//header("Last-Modified: $sLastModified");
header("Content-type: text/javascript");
readfile("maps.js");
readfile("jquery/jquery.js");
readfile("jquery/jquery.below.js");
readfile("jquery/jquery.autocomplete.min.js");
readfile("jquery/date.js");
readfile("jquery/jquery.datepicker.js");
readfile("jquery/jquery.bgiframe.min.js");
readfile("jquery/jquery.form.js");
readfile("jquery/jquery-validate/jquery.validate.pack.js");
readfile("jquery/jquery-ui-personalized-1.5b3.min.js");
readfile("jquery/jquery.center.js");
//readfile("jquery/jquery-tooltip/jquery.tooltip.pack.js");
//readfile("jquery/highlight/jquery.highlight.js");
?>