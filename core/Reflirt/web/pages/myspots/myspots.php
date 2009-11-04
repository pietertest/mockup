<?php

include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/user/UserProfile.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/mylocation/MyLocationUtils.class.php';
include_once PHP_CLASS.'entities/spot/SpotUtils.class.php';
include_once PHP_CLASS.'entities/location/Country.class.php';
include_once PHP_CLASS.'entities/note/Note.class.php';
include_once PHP_CLASS.'entities/spot/MySpot.class.php';

/** @Login*/
class MyspotsPage extends Page {
	
	/** @WebAction */
	public function add() {
		$spotid = $this->getInt("id", -1);
		if($spotid == -1) {
			throw new UserFriendlyMessageException("Onbekende spot");
		}
		$spot = new MySpot();
		$spot->setUser($this->getUser());
		$spot->put("spotid", $spotid);
		
		$this->overview();
		$this->setTemplate("overview");
		
		try {
			$spot->save();
		} catch(DuplicateException $e) {
				throw new UserFriendlyMessageException("Je hebt deze spot al toegevoegd");
		}
		$this->overview();
		$this->setTemplate("overview");
		throw new UserFriendlyMessage("Je spot is toegevoegd!");
	}

	/** @WebAction */
	public function overview() {
		$user = $this->getUser();
		if($user == null) {
			throw new UserFriendlyMessageException("Deze gebruiker bestaat niet: " + $username);
		}
		
		$myspots = $this->getMySpots($user);
		$this->put("myspots", $myspots);
		$this->put("user", $user);			
	}
	
	/**
	 * Wordt ook gebruikt in account.overview()
	 */
	public static function getMySpots(User $user) {
		// Haal alle categorieen op waarin de user spots heeft
		$oq = ObjectQuery::buildDS(new MySpot(), $user);
		$allSpots = SearchObject::search($oq);

		$myspots = array();
		$totalPerCategory = array();  
		
		// Haal aantal members op per spot
		foreach ($allSpots as $key=>$myspot) {
			$cat = $myspot->getString("category");
			$catname = Spot::$CATEGORIES[$cat];
			$spots = array();
			if(isset($myspots[$catname]["spots"])) {
				$spots = array_merge($spots, $myspots[$catname]["spots"]);
			}
			
			$aantal = SpotUtils::getNoOfMembers($myspot->getString("spotid"));
			$spotInfo = array();
			//DebugUtils::debug($myspot->getFields());
			$spotInfo["title"] = $myspot->getString("name");
			$spotInfo["cicityname"] = $myspot->getString("cicityname");
			$spotInfo["aantal"] = $aantal;
			$spotInfo["category"] = $myspot->getString("category");
			$spotInfo["id"] = $myspot->getString("spotid");
			$spots[] = $spotInfo;
			
			if(isset($myspots[$catname]["aantal"])) {
				$aantal += $myspots[$catname]["aantal"];
			}
			
			$myspots[$catname]["spots"] = $spots;
			$myspots[$catname]["aantal"] = $aantal; 
			$myspots[$catname]["category"] = $myspot->getString("category"); 
		}
		return $myspots;
	}
	
	/** @WebAction */
	public function form() {
		$cat = $this->getString("cat");
		
		$shortName = MyLocationUtils::getShortCatName($cat);
		$friendlyName = MyLocationUtils::getFriendlyCatName($cat);

		$this->put("categoryname", $friendlyName);
		$this->put("countries", Country::createCountryPulldownArray());
		$this->put("country", "1");
		$this->put("lastrelevantsearchers", $this->getLastSearchersByCat($cat));
		$this->put("shortname", $shortName);
		$this->put("select_sex", Utils::getArrayForSex());		
	}
	
	/** @WebAction */
	public function save() {
		$cat = $this->getString("cat");
		
		$systemid = $this->getString("id");
		Utils::assertTrue("Onbekende categorie: ".$cat, isset(MyLocation::$CATEGORIES[$cat]));
		$oproep = MyLocationFactory::newInstance($cat);
		if(!Utils::isEmpty($systemid)) {
			$oproep->setKey($systemid);
		}
		$oproep->putAll($this->getFields());
		
		$oproep->setUser($this->getUser());
		$oproep->save();
		header("location: /?page=myspots&action=created&id=".$oproep->getKey()."&cat=".$cat);
	}
	
	/**
	 *  @WebAction 
	 * 	Nadat een opdract is aangemaakt/gewijzgd
	 */
	public function created() {
		$cat = $this->getString('cat');
		$oproep = MyLocationFactory::newInstance($cat);
		$oproep->setKey($this->getString("id"));
		$oproep->setUser($this->getUser());
		$oproep->load();
		
		$this->put("oproep", $oproep);
	}
	
	/* ------------------------------ private functions ----------------------*/
	
	 /*
	  * Laatste zoekopdrachten van de category waar de gebruiker zich nu in
	  * bevind tijdesn het aanmaken/wijzigen
	  */	
	private function getLastSearchersByCat($cat) {
		$oproep = MyLocationFactory::newInstance($cat);
		$oq = ObjectQuery::build($oproep, $this->getUser());
		$oq->setSearcher($oproep->getDefaultSearcher());
		$oq->addParameter("table", $oproep->getTable()->getTableName());
		$oq->addParameter("category", $cat);
		$list = SearchObject::search($oq);
		return $list;
	}
	
}

?>
