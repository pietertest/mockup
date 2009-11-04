<?php
include_once PHP_CLASS.'entities/oproep/DiscoOproep.class.php';
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/tasks/Task.class.php';
include_once PHP_CLASS.'entities/zoekopdracht/Zoekopdracht.class.php';
include_once PHP_CLASS.'entities/location/City.class.php';
include_once PHP_CLASS.'entities/location/Country.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'entities/spot/MyNeighborhood.class.php';
include_once PHP_CLASS.'entities/mylocation/MyLocationUtils.class.php';
include_once PHP_CLASS.'entities/oproep/OproepUtils.class.php';

class SearchPage extends Page {

	
	/** @WebAction */
	public function simplesearch() {
	}
	
	/**
	 * Via: Zoeken, tab 'spots'.
	 * Template: search.ajax.spot.tpl
	 * @Ajax
	 */
	public function _spots() {
		$q = $this->getString("q");
		if(empty($q)) {
			$this->put("spots", SpotUtils::getLatest());			
		} else {
			$user = UserFactory::getSystemUser();
			$oq = ObjectQuery::build(new Spot(), $user);
			$oq->addParameter("q", $q);
			$oq->setSearcher(new SpotsSearcher());
			$list = SearchObject::search($oq);
			$this->put("spots", $list);
		}
		
	}
	
	/**
	 * Via: Zoeken, tab 'people'
	 * @WebAction
	 */
	public function people() {}
		
	
	/** @WebAction */
	public function overview() {
		$categories = array_merge_recursive(array("-1" => "Alles"), MyLocation::$CATEGORIES);
		$this->put("categories", $categories);
		$this->put("lastsearchers", $this->getLastSearchers());
	}
	
	/**************************Niet meer nodig?*******************************/
	
	/** @WebAction */
	public function compose() {
		$cat = $this->getString("cat");
		if(!$cat) {
			header("location: /?page=search");
		}
		$friendlyName = MyLocationUtils::getFriendlyCatName($cat);
		$this->put("categoryname", $friendlyName);
		$this->put("lastrelevantsearchers", $this->getLastSearchersByCat($cat));
		$this->initValues();
		$this->put("cat_template", "search/search.".MyLocationUtils::getShortCatName($cat).".tpl");
	}
	
		/** @WebAction */
	public function inmyneighborhood() {
		$hood = new MyNeighborhood();
		$zipcode = $this->getString("zipcode");
		$cityid = $this->getString("cityid");
		if(!empty($zipcode) && strlen($zipcode) >= 4) {
			$zipcode = substr($zipcode, 0, 4);	
		}
		if(empty($zipcode) && empty($cityid)) {
			return;
		}
		$people = $hood->getPeople($this);
		$this->put("people", $people);
	}
	
	private function initValues() {
		$categories = MyLocation::$CATEGORIES;
		$categories = array_merge_recursive(array("-1" => "Selecteer..."), $categories);
		$this->put("countries", Country::createCountryPulldownArray());
		$this->put("country", "1");
		$this->put("categories", $categories);
		$this->put("sex_options", Utils::getArrayForSex());
		$this->put("sex_checkboxes", Utils::getArrayForSex(null, true));
	}
	
	/** @WebAction*/
	public function dosearch() {
		$cat = $this->getString("cat");
		Utils::assertNotNull("Invalid category: ".$cat, $cat);
		$oproep = MyLocationFactory::newInstance($cat);
		$searcher = $oproep->getMatchSearcher();

		$user = UserFactory::getSystemUser();
		$oq = ObjectQuery::build($oproep, $user);
		$oq->addParameters($this);
		$oq->setSearcher($searcher);
		$list = SearchObject::search($oq);
		$this->compose();

		$this->put("cat_template", "search/search.".MyLocationUtils::getShortCatName($cat).".tpl");
		$this->put("searchresults", $list);
	}
	
	/** @WebAction*/
	public function dosimplesearch() {
		$cat = $this->getString("cat");
		$simplesearch = $this->getString("simplesearch");
		$user = UserFactory::getSystemUser();
		
		// maak van 'disco amsterdam' => '+disco +amsterdam' 
		$keywords = preg_replace("/(\w+)/i", "+$1", $simplesearch); 
		$oq = ObjectQuery::build(new OproepEntity(), $user);		
		$oq->addParameter("keywords", $keywords);
		$oq->addIfParameter("category", $cat);
		$oq->setSearcher(AbstractMyLocation::getSimpleSearchMatcher());
		if(!empty($sex)) {
			$oq->addConstraint(Constraint::in("sex", $sex));
		}
		
		$list = SearchObject::search($oq);
		$list2 = array();
		foreach($list as $key=>$oproep) {
			$tempCat = $oproep->getString("category");	
			$type = OproepFactory::getOproep($tempCat);
			$o = EntityFactory::loadEntity($type, $user, $oproep->getString("oproepid"));
			$list2[] =$o;
		}

		$template = "";
		if($cat) {
			$template = OproepUtils::getShortCatName($cat);
			$this->compose();
		} else {
			$template = "overview";
			$this->initValues();
		}
		$this->put("cat_template", "search/search.".$template.".tpl");
		$this->put("searchresults", $list2);
		$this->setTemplate("dosearch");
	}
	
	public function getLastSearchersByCat($cat) {
		$oproep = OproepFactory::getOproep($cat);
		$user = UserFactory::getSystemUser();
		$oq = ObjectQuery::build($oproep, $user);
		$oq->setSearcher($oproep->getDefaultSearcher());
		if(isset($_SESSION['uid'])) {
			$oq->addConstraint(Constraint::neq("users.systemid", $_SESSION["uid"]));	
		}
		$oq->setLimit(10);
		$list = SearchObject::search($oq);
		return $list;
	}
	
	private function getLastSearchers() {
		$user = UserFactory::getSystemUser();
		$oq = ObjectQuery::buildACS(new MyLocation(), $user, 10);
		if(isset($_SESSION['uid'])) {
			$oq->addConstraint(Constraint::neq("mylocation.user", $_SESSION["uid"]));	
		}
		$list = SearchObject::search($oq);
		return MyLocationUtils::convertToOproep($list);
	}
	
}

class SpotsSearcher extends Searcher {
	function getFields(DataSource $ds) {
		return "COUNT(*) AS aantal, spot.*";
	}

    function getTables(DataSource $ds) {
    	return "FROM `myspot` 
				JOIN spot
				ON myspot.spotid = spot.systemid";
    }

    function getFilter(DataSource $ds) {
    	$q = $ds->getString("q");
    	$list = new QueryConstraintList();
    	$list->addLike("name", $q);
    	return $list;
    }
    
    function getGroupBy(DataSource $ds) {
    	 return "spot.systemid";
    }
    
    function getOrderBy(DataSource $ds) {
    	return "aantal DESC";
    }
}
?>
