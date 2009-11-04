<?php
include("../../../config/config.php");
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/message/Message.class.php';
include_once PHP_CLASS.'entities/mylocation/MyLocation.class.php';

$cat = $_GET["cat"];
$pop_column = $_GET["id"];

$user = UserFactory::getSystemUser();
$type = MyLocationFactory::newInstance($cat);
$oq = ObjectQuery::build(new $type(), $user);
$oq->setSearcher($type->getDefaultSearcher());
$col = $type->getSpotColumn();
$oq->addConstraint(Constraint::eq($col, $pop_column));
$results = array();
$list = SearchObject::search($oq);

$json = array();
$title = null;
$addition = null;
foreach($list as $key=>$value) {
	$photo = $value->getString("filename");
	if(empty($photo)) {
		$photo = "johndoe.jpg";
	}
	$item = array();
	$item["user"] = $value->getString("username");
	$item["photo"] = $photo;
	$title = $value->getTitle();
	$addition = $value->getAddition();
	$results[] = $item;
}
$json["template"] = file_get_contents(SERVLETS_TEMPLATE_DIR."/users/userpicture.tpl");
$json["nrofresults"] = count($results);
$json["items"] = $results;
$json["title"] = $title;
$json["addition"] = $addition;

echo json_encode($json);

?>
