<?php
$_db 		= "reflirt_nieuw";
$_host 		= "localhost";
$_username 	= "reflirt_user";
$_password 	= "reflirt_pass123";
@$link = mysql_connect($_host, $_username, $_password);
if(!$link) {
	exit("No db connection available");
}
mysql_select_db($_db) or die("No connection to database");
//TODO: emailnotificatie versturen als er hier iets misgaat

?>
