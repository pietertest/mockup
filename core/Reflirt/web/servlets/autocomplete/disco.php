<?php

include_once("../db.php");
$q = isset($_GET["q"])? strtolower($_GET["q"]) : "";
if(!$q) {
	exit("missing parameters");
}
$extra = "";
if(isset($_GET["cityid"])) {
	if(!empty($_GET["cityid"])){
		$extra .= " AND cityid = ".$_GET["cityid"];		
	}
}

if(isset($_GET["cicountryid"])) {
	$countryid = $_GET["cicountryid"];
	if(!empty($countryid) && $countryid != "-1") {
		$extra .= " AND cicountryid = ".$_GET["cicountryid"];
	}
}

$query = "SELECT city.*, spot.* FROM spot JOIN city on cityid = city.systemid WHERE spot.name LIKE '$q%' $extra LIMIT 0,10";
$rs = mysql_query($query) or die(mysql_error());
mysql_close($link);
if($rs == null) {
	return;	
}
while($disco = mysql_fetch_array($rs, MYSQL_ASSOC)) {
	$disconame = $disco['name'];
	$discoid = $disco['systemid'];
	$city = $disco['cicityname'];
	$cityid = $disco['cityid'];
	$countryid = $disco['cicountryid'];
	if (strpos(strtolower($disconame), $q) !== false) {
		echo "$disconame|$discoid|$city|$cityid|$countryid\n";
	}
}

?>