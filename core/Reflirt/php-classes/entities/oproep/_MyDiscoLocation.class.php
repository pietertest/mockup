<?php

include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/mylocation/MyLocation.class.php';
include_once PHP_CLASS.'entities/entries/Disco.class.php';
include_once PHP_CLASS.'searchers/Searcher.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'entities/db/JoinedEntity.class.php';
include_once PHP_CLASS.'entities/db/FulltextSearch.class.php';
include_once PHP_CLASS.'entities/mylocation/AbstractMyLocation.class.php';
//include_once PHP_CLASS.'entities/zoekopdracht/Zoekopdracht.class.php';

/** @FulltextColumns("disconame"); */
class MyDiscoLocation extends AbstractMyLocation {
	
	private $keywords = array(
			"disco",
			"discotheek",
			"kroeg",
			"bar"
	);
	
    function __construct() {
		parent::__construct("reflirt_nieuw", "mylocation_disco", MyLocation::$CAT_DISCO);
    }
    
    /** Implementation of OproepInterface */
    public function getDefaultSearcher() {
    	return new DefaultMyDiscoLocationSearcher();
    }

    public function getMatchSearcher() {
    	return new MyDiscoLocationMatcher();
    }
    
    public function getMostPopulairSearcher() {
    	return new DiscoMostPopulairSearcher();
    }
    
    public function getMyLocationLoadSearcher() {
    	return new MyDiscoLocationLoadSearcher($this);
    }
    
    public static function getSpotColumn() {
    	return "sddiscoid";
    }
    
    public function getTitle() {
    	return $this->getString("ddisconame"); //.",".$this->getString("cicityname");
    }
    
    public function getAddition() {
    	return $this->getString("cicityname"); //.",".$this->getString("cicityname");
    }
    
    public function getMatches() {
    	$user = UserFactory::getSystemUser();
    	$searcher = $this->getMatchSearcher();
		$oq = ObjectQuery::build(new MyDiscoLocation, $user);
		$oq->addParameters($this);
		$oq->setSearcher($searcher);
		$list = SearchObject::search($oq);
		return $list;
    }
    
    /** Implementation of FulltextSearch  */
	function getFulltextColumns(){
    	$columns = array();
    	$columns[] = "cocountryname";
    	$columns[] = "cicityname";
    	$columns[] = "ddisconame";
    	return $columns;
    }
    
    function getExtraKeywords(){
    	return $this->keywords;
    }
    
    // Override
    public function save() {
		$this->saveDiscoAndCityIfNeccecary();
    	parent::save();
    }
    
    private function saveDiscoAndCityIfNeccecary() {
    	// Ervanuit gegaan dat er geen nieuwe stad toegevoegd kan worden is er 
    	// dus altijd een cityid!
    	$discoId = $this->getString("sddiscoid");
    	$disconame = $this->getString("ddisconame");
    	$tempCityId = $this->getString("sdcityid");
		if(empty($tempCityId)) {
//			DebugUtils::debug("leeeeeeeeeeeef:".$tempCityId);
			$countryId 	= $this->getString('cicountryid');
			$cityName 	= $this->getString('cicityname');
			
			$city = new City();
			$city->put('cicountryid', $countryId);
			$city->put('cicityname', $cityName);
			$city->save();
			
			$this->put("sdcityid", $city->getKey());
		}
    	if(empty($discoId)) {
	    	if(!empty($disconame)) {
	    		$cityId = $this->getString("sdcityid");
		    	$ent = new Disco();
		    	$ent->put("ddisconame", $disconame);
		    	$ent->put("sdcityid", $cityId);	    	
		    	$ent->save();
		    	
		    	$this->put("sddiscoid", $ent->getKey());
	    	} else {
	    		// TODO: Moet alleen kunnen bij het plaatsen vanee Preflirt ding
	    		$this->remove("sddiscoid"); // Zodat de waarde NULL in de db komt
	    	}
    	}
    }
    
	public function getAutocompletionData(DataSource $ds) {
		throw new IllegalStateException("Not implemented!");
	}
}

/**
 * LoadSearcher overschrijven
 */
class MyDiscoLocationLoadSearcher extends LoadSearcher {
	
	public function getTables(DataSource $ds) {
		return " FROM mylocation_disco ".
					" INNER JOIN disco ".
					" ON mylocation_disco.sddiscoid = disco.systemid ".
					" INNER JOIN city ".
					" ON mylocation_disco.sdcityid = city.systemid ".
					" INNER JOIN mylocation ".
					" ON mylocation_disco.searchid = mylocation.systemid ".		
					" INNER JOIN users ".
					" ON mylocation.user = users.systemid ".
					" LEFT JOIN photo ".
					" ON photo.systemid = users.photoid ";
	}
}

class DefaultMyDiscoLocationSearcher extends DefaultSearcher {
	
	public function getTables(DataSource $ds) {
			return " FROM mylocation_disco ".
					" INNER JOIN disco ".
					" ON mylocation_disco.sddiscoid = disco.systemid ".
					" INNER JOIN city ".
					" ON mylocation_disco.sdcityid = city.systemid ".
					" INNER JOIN mylocation ".
					" ON mylocation_disco.searchid = mylocation.systemid ".		
					" INNER JOIN users ".
					" ON mylocation.user = users.systemid ".
					" LEFT JOIN photo ".
					" ON photo.systemid = users.photoid ";
	}
}

/**
 * Deze zoeker wordt gebruikt om te matcheb vanuit het zoekscherm waarbij
 * NIET de fultext methode de 'google' methode) wordt gebruikt
 */
class MyDiscoLocationMatcher extends Searcher {
	
	public function getFields(DataSource $ds) {
		return "*, mylocation_disco.systemid AS systemid "; // niet de joined systemid mixen
	}
	
	public function getTables(DataSource $ds) {
		$select	= 	" FROM mylocation_disco ";
		$discoid = $ds->getString("sddiscoid");
		$select .=	" INNER JOIN disco ";
		if(!empty($discoid)) {
			$select .=	" ON mylocation_disco.sddiscoid = disco.systemid ";
		}
		$select .=	" INNER JOIN city ";
		$select .=	" ON disco.dcityid = city.systemid ";
		$select .=	" INNER JOIN mylocation ";
		$select .=	" ON mylocation_disco.searchid = mylocation.systemid ";		
		$select .=	" INNER JOIN users ";
		$select .=	" ON mylocation.user = users.systemid ";
		
		// Foto
		$select .=	" LEFT JOIN photo ";
		$select .=	" ON photo.systemid = users.photoid";		
		return $select;
	}
	
	public function getFilter(DataSource $ds) {
		$list = new QueryConstraintList();
		$list->addKey("category", MyLocation::$CAT_DISCO);
		$list->addIfKey("sddiscoid", $ds->getString("sddiscoid"));
		$sex = $ds->getString("dsex");
		if(is_array($sex)) {
			if(count($sex) == 1) {
				$sex = $sex[0];
			} else {
				$sex = "";
			}
		}
		$list->addIfKey("users.sex", $sex);
		
		$list->addKey("mylocation_disco.sdcityid", $ds->getString("sdcityid"));
		if(isset($_SESSION['uid'])) {
			$list->addNotKey("users.systemid", $_SESSION['uid']);
		}
		return $list;
	}
}
class DiscoMostPopulairSearcher extends Searcher {
	
	public function getFields(DataSource $ds) {
		return "*, count(*) AS aantal ";
	}
	
	public function getTables(DataSource $ds) {
		return " FROM mylocation_disco " .
				" INNER JOIN disco ".
				" ON mylocation_disco.sddiscoid = disco.systemid ".
				" INNER JOIN city ".
				" ON mylocation_disco.sdcityid = city.systemid ";					
	}
	
	public function getFilter(DataSource $ds) {
		return new QueryConstraintList();
	}
	
	public function getOrderby(DataSource $ds) {
		return "sddiscoid";
	}
	
	public function getGroupBy(DataSource $ds) {
		return "sddiscoid"; 
	}
}

?>