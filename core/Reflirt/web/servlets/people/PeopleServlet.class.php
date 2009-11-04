<?php
include_once(SERVLETS_DIR.'Servlet.class.php');
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'entities/spot/MySpot.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';

class PeopleServlet extends Servlet{
	
	public function __construct(DataSource $ds) {
		parent::__construct($ds);
	}
	
	// Key mapping voor gebruik van een user object aan de clientside
	private $fieldMapping = array(
			// <ds-naam>, <naam-die-je-wilt>
			"username" => "user",
			"lat" => "lat",
			"lng" => "lng",
			);
	
	public function byname() {
		$q = $this->getString("q");
		$zipcode = $this->getString("zipcode");
		$cityid = $this->getString("cityid");
		$user = UserFactory::getSystemUser();
		$oq = ObjectQuery::build(new User(), $user);
		
		if(!empty($q)) {
			$oq->addConstraint(Constraint::like("username", $q));
		}
		if(!empty($cityid)) {
			$oq->addConstraint(Constraint::like("cityid", $cityid));
		}
		if(!empty($zipcode)) {
			$oq->addConstraint(Constraint::eq("zipcode", $zipcode));
		}
		$oq->setSearcher(new DefaultUserSearcher());
		$oq->setCountRows(true);
		$list = SearchObject::search($oq);
		
		//$items = EntityUtils::convertKeysForSaveClientSideUse($list, User::clientsideKeyMapping);
		$items = array();
		foreach ($list as $key=>$user) {
			$photo = $user->getString("filename");
			if(empty($photo)) {
				$photo = "johndoe.jpg";
			}
			$item = Utils::doFieldMapping($user, $this->fieldMapping);
			$item["photo"] = $photo;
			$items[] = $item;
		}
		$noOfResultsWithoudLimit = $oq->getCountRows();
		$json["total"] = $noOfResultsWithoudLimit;
		$json["nrofresults"] = count($items);
		$json["template"] = file_get_contents(SERVLETS_TEMPLATE_DIR."/users/userpicture.tpl");
		$json["infohtml"] = file_get_contents(SERVLETS_TEMPLATE_DIR."/users/userinfohtml.tpl");
		$json["items"] = $items; 
		echo json_encode($json);
	}
}	

class ByNameSearcher extends DefaultSearcher {
	
	public function getFields(DataSource $ds) {
		return "username, photoid, ";
	}
	public function getTables(DataSource $ds) {
		return " FROM users ".
				" LEFT JOIN photo ".
				" ON photo.systemid = users.photoid ".
				" LEFT JOIN city ".
				" ON city.systemid= users.cityid ";
	}
}
?>