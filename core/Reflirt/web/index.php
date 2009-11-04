<?php
@session_start();
//include("profile/profile.php");

include("../config/config.php");
include_once(PHP_CLASS.'core/ClassLoader.class.php');
include_once PHP_CLASS.'core/PageLoader.class.php';
include_once PHP_CLASS.'entities/db/DatabaseEntity.class.php';


$starttime = microtime();
$startarray = explode(" ", $starttime);
$starttime = $startarray[1] + $startarray[0];

$ds = new DataSource();
$ds->putAll($_REQUEST);

$page = new PageLoader($ds);

//$isJson = isset($_SERVER["HTTP_X_REQUESTED_WITH"]) ? $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest" : false;
//if ($isJson) {
//	echo "";
//	ob_start();
//	$page->go();
//	$result = ob_get_contents();
//	ob_end_clean();
//	echo $result;
//	$json = array();
//	$json["fail"] = array(
//		"message" => $result
//	);
//	
//	echo json_encode($json);
//	exit(1);
//}
//else {
//	$page->go();
//}
$page->go();
$endtime = microtime();
$endarray = explode(" ", $endtime);
$endtime = $endarray[1] + $endarray[0];
$totaltime = $endtime - $starttime;
$totaltime = round($totaltime,3);

if (!IS_PRODUCTION) {
//	echo PreparedQuery::$query_counter." queries executed!";
//	echo "This page loaded in $totaltime seconds.";
}
?>