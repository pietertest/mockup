<?php
include_once("../db.php");
$username = isset($_GET["username"])? trim(strtolower($_GET['username'])) : "";
if(!$username) {
	exit("missing parameters");
}
$query = "SELECT count(username) AS rows FROM users where username = '$username'";
$rs = mysql_query($query);
$line = mysql_fetch_array($rs, MYSQL_ASSOC);
mysql_close($link);
$valid = "true";
if($line["rows"] > 0) {
	$valid = "false";
}
echo $valid;


?>
