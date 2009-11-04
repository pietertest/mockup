<?php
include_once(SERVLETS_DIR.'Servlet.class.php');
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'entities/spot/MySpot.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';

class SpotsServlet extends Servlet{
	
	public function __construct(DataSource $ds) {
		parent::__construct($ds);
	}
	
	public function getsimilarbyaddres() {
		$cat = $this->getString("cat");	
		$limitStart = $this->getInt("start", 0);
		$limitEnd = $this->getInt("end", 10);
		if($limitEnd - $limitStart > 10) {
			$limitEnd = $limitStart + 10;
		}
		$name = strtolower($this->getString("name"));
		$street = strtolower($this->getString("street"));
		$cityid = strtolower($this->getString("cityid"));
		$zipcode = strtolower($this->getString("zipcode"));
		$houseno = strtolower($this->getString("houseno"));
		$housenoext = strtolower($this->getString("housenoext"));
		$user = UserFactory::getSystemUser();
		$oq = ObjectQuery::build(new Spot(), $user);
		$oq->setSearcher(Spot::getDefaultSearcher());
		$oq->addConstraint(Constraint::like("name", $name));
		$oq->addIf(Constraint::like("street", $street), $street);
		$oq->addIf(Constraint::eq("category", $cat), $cat);
		$oq->addIf(Constraint::eq("cityid", $cityid),  $cityid);
		$oq->addIf(Constraint::like("zipcode", $zipcode), $zipcode);
		$oq->addIf(Constraint::eq("housenoext", $housenoext), $housenoext);
		$oq->setLimit($limitStart, $limitEnd);
		$oq->setCountRows(true);
		$list = SearchObject::search($oq);
		
		$items = array();
		foreach ($list as $key=>$spot) {
			array_push($items, $spot->getAll());
		}
		$noOfResultsWithoudLimit = $oq->getCountRows();
		$json["total"] = $noOfResultsWithoudLimit;
		$json["items"] = $items; 
		echo json_encode($json);
	}
	
	/**
	 * Retourneer een lijst met gebruikers uit de spot
	 */
	 public function people() {
	 	$spotid = $this->getInt("id", -1);
	 	Utils::assertTrue("No valid spot: ".$spotid, $spotid != -1);
	 	$systemUser = UserFactory::getSystemUser();
	 	$spot = EntityFactory::loadEntity(new Spot(), $systemUser, $spotid);

	 	$oq = ObjectQuery::build(new MySpot(), $systemUser);
	 	$oq->setSearcher(MySpot::getPeopleSearcher());
	 	$oq->addConstraint(Constraint::eq("myspot.spotid", $spotid));
	 	
		$list = SearchObject::search($oq);
		
		
		$json = array();
		foreach($list as $key=>$value) {
			$photo = $value->getString("filename");
			if(empty($photo)) {
				$photo = "johndoe.jpg";
			}
			
			$item = $value->getFields();
			$item["user"] = $value->getString("username");
			$item["photo"] = $photo;
			
			$results[] = $item;
		}
		$json["spotid"] = $spot->getKey();
		$json["title"] = ucfirst($spot->getString("name"));
		$json["nrofresults"] = count($results);
		$json["items"] = $results;
		$json["template"] = file_get_contents(SERVLETS_TEMPLATE_DIR."/users/userpicture.tpl");
		$json["infohtml"] = file_get_contents(SERVLETS_TEMPLATE_DIR."/users/userinfohtml.tpl");
		
		echo json_encode($json);
	
	 }
	
	/**
	 * Zoeken op spotnaam
	 */
	public function simplesearch() {
		$q = $this->getString("q");
		$user = UserFactory::getSystemUser();
		$oq = ObjectQuery::build(new Spot(), $user);
		$oq->addParameter("q", $q);
		$oq->setSearcher(new SpotsSearcher());
		$list = SearchObject::search($oq);
		$items = array();
		foreach ($list as $key=>$spot) {
			$item = array();
			$item['title'] = $spot->getString('title');
			$item['cat'] = $spot->getString('cat');
			$item['id'] = $spot->getString('id');
			$item['descr'] = $spot->getString('descr');
			array_push($items, $item);
		}
		$data = array();
		$data['items'] = $items;
		
		global $smarty;
		$smarty->assign("q", $q);
		$smarty->fetch("servlets/spots/spots.tpl");
		
		echo json_encode($data);
	}
		
}

class SpotsSearcher extends Searcher {
	function getFields(DataSource $ds) {
		return "*";
	}

    function getTables(DataSource $ds) {
    	return "FROM spot";
    }

    function getFilter(DataSource $ds) {
    	$q = $ds->getString("q");
    	$list = new QueryConstraintList();
//    	$list->addMatch("descr", $q);
    	$list->addLike("", $q);
    	return $list;
    }
}
?>