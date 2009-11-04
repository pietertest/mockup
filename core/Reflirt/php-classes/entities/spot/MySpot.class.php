<?php
include_once PHP_CLASS."entities/spot/SpotFactory.class.php";
include_once PHP_CLASS."searchers/DefaultSearcher.class.php";
class MySpot extends DatabaseEntity {
	
	private $spot;
	
	public function __construct() {
		parent::__construct("reflirt_nieuw", "myspot");
	}
	
	/**
	 * Zoekt peoples per spot
	 */
	public static function getPeopleSearcher() {
		return new MySpotPeopleSearcher(); 
	}
	
	public function getDefaultSearcher() {
		return new DefaultMySpotSearcher();
	}
	
	public function getSpot() {
		if ($this->spot == null) {
			$this->spot = $this->loadEntityByForeignKey(new Spot(), "spotid");
//			$systemUser = UserFactory::getSystemUser();
//			$this->spot = EntityFactory::loadEntity(new Spot(), $systemUser, $this->getKey());
		}
		return $this->spot;
	}
	
	public static function getMySpots($user) {
		$oq = ObjectQuery::buildACS(new MySpot(), $user);
		//$oq->setSearcher(new MySpotPeopleSearcher());
		$myspots = SearchObject::search($oq);
		return $myspots;
	}
}

class DefaultMySpotSearcher extends DefaultSearcher {
	public function getTables(DataSource $ds) {
		return " FROM myspot ".
			" JOIN spot ".
			" ON myspot.spotid = spot.systemid" .
			" LEFT JOIN city " .
			" ON spot.cityid = city.systemid ";
	}
}

class MySpotPeopleSearcher extends DefaultSearcher {
	
	function getTables(DataSource $ds) {
		return "FROM myspot ".
			" JOIN users ".
			" ON myspot.user = users.systemid".
			" LEFT JOIN photo ".
			" ON photo.systemid = users.photoid";
	}	
}


?>