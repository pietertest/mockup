<?php
include("../../../config/init_smarty_config.php");
include_once(PHP_CLASS.'core/ClassLoader.class.php');
include_once PHP_CLASS.'core/PageLoader.class.php';
include_once PHP_CLASS.'entities/location/City.class.php';
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';

$q = strtolower($_GET["q"]);
if (!$q) return;

$extra = "";
if(isset($_GET["cicountryid"])) {
	$countryid = $_GET["cicountryid"];
	if(!empty($countryid) && $countryid != "-1") {
		$extra .= " AND cicountryid = ".$_GET["cicountryid"];
	}
}
$pq = new PreparedQuery("reflirt_nieuw");
$query = "SELECT *, city.systemid AS cityid FROM city JOIN country on city.cicountryid=country.systemid WHERE cicityname LIKE '".DBUtils::dbEscape($q)."%' $extra LIMIT 0,10";
//echo $query;
$pq->setQuery($query);
$cities = $pq->execute();

if($cities == null) return;

foreach ($cities as $city=>$value) {
	$countryname = $value['cocountryname'];
	$countryid = $value['cicountryid']; 
	$cityname = $value['cicityname'];
	$cityid = $value['cityid'];
	if (strpos(strtolower($cityname), $q) !== false) {
//		echo json_encode(array(
//			"cityname" 		=> $cityname,
//			"cityid"		=> $cityid,
//			"countryname"	=> $countryname,
//			"countryid"		=> $countryid
//		));
		echo "$cityname|$cityid|$countryname|$countryid\n";
	}
}
//echo json_encode($a);

?>