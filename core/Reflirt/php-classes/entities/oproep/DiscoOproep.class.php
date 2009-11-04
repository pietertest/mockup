<?php

include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';
include_once PHP_CLASS.'entities/spot/DiscoSpot.class.php';
include_once PHP_CLASS.'searchers/Searcher.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'entities/db/JoinedEntity.class.php';
include_once PHP_CLASS.'entities/db/FulltextSearch.class.php';

/** @FulltextColumns("disconame"); */
class DiscoOproep extends OproepEntity {
	
	private $keywords = array(
			"disco",
			"discotheek",
			"kroeg",
			"bar"
	);
	
	public function getLabel() {
		return self::$CATEGORIES[self::$CAT_DISCO];
	}
	
    public function getDefaultSearcher() {
    	DebugUtils::debug($_SERVER);
    	return new DefaultDiscoSearcher();
    }

    public function getMatchSearcher() {
    	return new DiscoMatchSearcher();
    }
    
    /**
     * Implementation of FulltextSearch
     */
	function getFulltextColumns(){
    	return array("street", "name");
    }
    
    public function validate() {
    	parent::validate();
		Utils::validateNotEmpty("Vul een discotheek in", $this->get("spotid"), "spotname");
    }
    
     /**
     * Implementation of FulltextSearch
     */
    function getExtraKeywords(){
    	return $this->keywords;
    }
    
    /** Override */
    public function getOproepLoadSearcher() {
    	return new DiscoOproepLoadSearcher($this);
    }
    
    /** Imlementation of OproeInterface */
    public function getMatches() {
    	$user = UserFactory::getSystemUser();
    	$searcher = $this->getMatchSearcher();
		$oq = ObjectQuery::build(new DiscoOproep, $user);
		$oq->addParameters($this);
		$oq->setSearcher($searcher);
		$list = SearchObject::search($oq);
		return $list;
    }
    
    // Override
    public function save() {
		//$this->saveDiscoAndCityIfNeccecary();
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
}

/**
 * LoadSearcher overschrijven
 */
class DiscoOproepLoadSearcher extends LoadSearcher {
	
	public function getTables(DataSource $ds) {
		return " FROM search_disco ".
					" INNER JOIN spot_disco ".
					" ON search_disco.sddiscoid = disco.systemid ".
					" INNER JOIN city ".
					" ON search_disco.sdcityid = city.systemid ".
					" INNER JOIN zoekopdracht ".
					" ON search_disco.searchid = zoekopdracht.systemid ".		
					" INNER JOIN users ".
					" ON zoekopdracht.user = users.systemid ".
					" LEFT JOIN photo ".
					" ON photo.systemid = users.photoid ";
	}
}

/**
 * Deze zoeker wordt gebruikt om te matcheb vanuit het zoekscherm waarbij
 * NIET de fultext methode de 'google' methode) wordt gebruikt
 */
class DiscoMatchSearcher extends Searcher {
	
	public function getFields(DataSource $ds) {
		return "*, search_disco.systemid AS systemid, " .
				"zoekopdracht.descr AS descr"; // niet de joined systemid mixen
	}
	
	public function getTables(DataSource $ds) {
		$select	= 	" FROM search_disco ";
		$discoid = $ds->getString("sddiscoid");
		$select .=	" INNER JOIN spot_disco ";
		if(!empty($discoid)) {
			$select .=	" ON search_disco.sddiscoid = disco.systemid ";
		}
		$select .=	" INNER JOIN city ";
		$select .=	" ON disco.dcityid = city.systemid ";
		$select .=	" INNER JOIN zoekopdracht ";
		$select .=	" ON search_disco.searchid = zoekopdracht.systemid ";		
		$select .=	" INNER JOIN users ";
		$select .=	" ON zoekopdracht.user = users.systemid ";
		
		// Foto
		$select .=	" LEFT JOIN photo ";
		$select .=	" ON photo.systemid = users.photoid";		
		return $select;
	}
	
	public function getFilter(DataSource $ds) {
		$list = new QueryConstraintList();
		$list->addKey("category", Oproep::$CAT_DISCO);
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
		
		$list->addKey("search_disco.sdcityid", $ds->getString("sdcityid"));
		$list->addKey("type", $ds->getString("type"));
		if(isset($_SESSION['uid'])) {
			$list->addNotKey("users.systemid", $_SESSION['uid']);
		}
		return $list;
	}
}
class DefaultDiscoSearcher extends DefaultSearcher {
	
	public function getTables(DataSource $ds) {
		return " FROM search_disco ".
					" INNER JOIN spot_disco ".
					" ON search_disco.sddiscoid = disco.systemid ".
					" INNER JOIN city ".
					" ON search_disco.sdcityid = city.systemid ".
					" INNER JOIN zoekopdracht ".
					" ON search_disco.searchid = zoekopdracht.systemid ".		
					" INNER JOIN users ".
					" ON zoekopdracht.user = users.systemid ".
					" LEFT JOIN photo ".
					" ON photo.systemid = users.photoid ";
	}
}

?>