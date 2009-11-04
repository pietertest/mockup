<?php
include_once(PHP_CLASS."entities/spot/SpotInterface.class.php");
include_once PHP_CLASS."entities/spot/SpotFactory.class.php";
include_once PHP_CLASS."entities/spot/MySpot.class.php";
include_once PHP_CLASS."entities/spot/SpotUtils.class.php";
include_once PHP_CLASS."html/HTML.class.php";
include_once PHP_CLASS."address/Address.class.php";
include_once PHP_CLASS."searchers/DefaultSearcher.class.php";

class Spot extends DatabaseEntity implements SpotInterface {
    
    function __construct() {
    	parent::__construct("reflirt_nieuw", "spot");
    }
    
    public function getLoadSearcher() {
    	return new SpotLoadSearcher($this);
    }
    
    public function getName() {
    	return $this->getString("name");
    }
    
    public function getDefaultSearcher() {
    	return new DefaultSpotSearcher();
    }
    
    //TODO: Zware query! Als dit alleen te doen is om het aantal members op te 
    // halen maak dan een nieuwe methode aan die een simple COUNT query doet
    public function getMembers($limitStart = null, $limitEnd = null) {
    	//return $this->getObjectsByForeignKey(new MySpot(), "spotid", $limitStart, $limitEnd);
    	$oq = ObjectQuery::build(new Spot(), $this->getUser());
    	$oq->addParameter("spotid", $this->getKey());
    	$oq->setSearcher(new SpotMemberSearcher());
		return SearchObject::search($oq);
    }
    
    public function getNoOfMembers() {
    	return SpotUtils::getNoOfMembers($this->getKey());
    }
    
    public function getNoOfPhotos() {
    	return SpotUtils::getNoOfPhotos($this->getKey());
    }
    
    public function getCity() {
    	return $this->loadEntityByForeignKey(new City, "cityid");
    }
    
    public function getAddress() {
    	return new Address(
    		$this->getString("street"),
    		$this->getString("houseno"),
    		$this->getString("housenoext"),
    		$this->getString("zipcode"),
    		$this->getString("cicityname"),
    		$this->getString("cocountryname")
    	);    	
    }
    
    /**
     * Het eerstkomende evenement
     */
     public function getComingAgendas($limit = 1) {
     	return $this->getAgendas(Constraint::gt("start", DateUtils::now()), $limit);
     }
     
     public function getPassedAgendas($limit = 1) {
     	return $this->getAgendas(Constraint::lt("start", DateUtils::now()), $limit);
     }
     
     private function getAgendas(QueryConstraint $c, $limit) {
     	$systemUser = UserFactory::getSystemUser();
     	$oq = ObjectQuery::build(new SpotAgenda(), $systemUser, $limit);
		$oq->addParameter("spotid", $this->getKey());
		$oq->addConstraint($c);
		$oq->setSearcher(SpotAgenda::getAgendaSearcher());
		return SearchObject::search($oq);
     }
    
    // Override
    public function getHTMLRenderer() {
    	return new SpotHTMLRenderer($this);
    }
    
   	public static $SPOT_BUITEN = 1;
	public static $SPOT_CULTUREEL = 2;
	public static $SPOT_DISCO = 3;
	public static $SPOT_EET = 4;
	public static $SPOT_EVENEMENT = 5;
	public static $SPOT_OV_BUS = 6;
	public static $SPOT_OV_METRO = 7;
	public static $SPOT_OV_TRAM = 8;
	public static $SPOT_OV_TREIN = 9;
	public static $SPOT_PARK = 10;
	public static $SPOT_RECREATIE = 11;
	public static $SPOT_SCHOOL = 12;
	public static $SPOT_WERK = 13;
	public static $SPOT_WINKEL = 14;
	public static $SPOT_WONEN = 15;
	public static $SPOT_HOTEL = 16;
	
	public static $CATEGORIES = array(
		1	 	=> "Buiten",	
		2 		=> "Cultureel",
		3 		=> "Disco/Kroeg",
		4		=> "Eetgelegenheid",
		5 		=> "Evenement",
		6		=> "Bus (OV)",
		7		=> "Metro (OV)",
		8		=> "Tram (OV)",
		9		=> "Trein (OV)",
		10		=> "(Thema) Park",
		11		=> "Recreatie",
		12		=> "School",
		13		=> "Werk",
		14		=> "Winkel",
		15		=> "Woonomgeving",
		16		=> "Hotel/Hostel"
	);
	
	public static $CATEGORIES_LABELS = array(
		1	 	=> "Buiten",	
		2 		=> "Cultureel",
		3 		=> "Disco/Kroeg",
		4		=> "Eetgelegenheid",
		5 		=> "Evenement",
		6		=> "Bus (OV)",
		7		=> "Metro (OV)",
		8		=> "Tram (OV)",
		9		=> "Eindplaats",
		10		=> "(Thema) Park",
		11		=> "Recreatie",
		12		=> "School",
		13		=> "Werk",
		14		=> "Winkel",
		15		=> "Woonomgeving",
		16		=> "Hotel/Hostel"
	);

	public static $SHORTNAMES = array( // Voor templates, bijv: search.disco.tpl 
		1	 	=> "out",	
		2 		=> "cult",
		3 		=> "disco",
		4		=> "eat",
		5 		=> "event",
		6		=> "bus",
		7		=> "metro",
		8		=> "tram",
		9		=> "trein",
		10		=> "park",
		11		=> "recreation",
		12		=> "school",
		13		=> "work",
		14		=> "shop",
		15		=> "living",
		16		=> "hotel"
	);
	
	public static function getShortnameByCategoryNr($cat) {
		return self::$SHORTNAMES[$cat];
	}
	
	public static function getCategories($add) {
		$result = array();
		if($add != null) {
			$result = $add;
			foreach(self::$CATEGORIES as $key=>$value) {
				$result[$key] = $value;
			}
		}
		return $result;
		
	}
	
	public function getFriendlyCategoryName() {
		return self::$CATEGORIES[$this->getString("category")];
	}
    
}

class DefaultSpotSearcher extends DefaultSearcher {
	
	public function getTables(DataSource $ds) {
		$select = " FROM spot ".
			" INNER JOIN city ".
			" ON city.systemid = spot.cityid ".
			" INNER JOIN country ".
			" ON country.systemid = city.cicountryid";
		return $select;
	}
}

class SpotLoadSearcher extends LoadSearcher {
	
	public function getTables(DataSource $ds) {
		$table = $this->entity->getTable()->getTableName();
		$select = " FROM $table ".
			" INNER JOIN city ".
			" ON city.systemid = ".$table.".cityid ".
			" INNER JOIN country ".
			" ON country.systemid = city.cicountryid";
		
		return $select;
	}
	
}

class SpotMemberSearcher extends DefaultSearcher {
	function getFields(DataSource $ds) {
		return "*, users.systemid AS systemid";
	}

	function getTables(DataSource $ds) {
		return "FROM users ".
			" JOIN myspot ".
			" ON myspot.user = users.systemid".
			" LEFT JOIN photo ".
			" ON photo.systemid = users.photoid";
	}	
	
	function getFilter(DataSource $ds) {
    	$list = new QueryConstraintList();
    	$list->addKey("myspot.spotid", $ds->getString("spotid"));
    	return $list;
    }
}

class SpotHTMLRenderer extends DefaultHTMLRenderer {
	
	public function __construct($ent) {
		parent::__construct($ent);
	}
	
	//Override
	public function get($what) {
		if($what == "address") {
			return $this->getAddress();
		}
		// To show in googlempas when clicked on the spot
		if($what == "infoHTML") {
			$link = new HTML("a");
			$link->attr("ahref", "/?page=spot&action=view&id=".$this->ent->getKey());
			$link->innerHTML($this->ent->getName());
			
			return $link->toString() . "<br/>".$this->getAddress();;
		}
		return parent::get($what);
	}
	
	private function getAddress() {
		$a = $this->ent->getAddress();
		return $a->getStreet()." ".$a->getHouseno()." ".$a->getHousenoExt()."<br/>".
				$a->getZipcode()." ".$a->getCity();
	}
}


?>