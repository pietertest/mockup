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
include_once PHP_CLASS.'searchutils/SearchAction.class.php';
include_once PHP_CLASS.'searchutils/SearchSuggestion.class.php';
include_once PHP_CLASS.'io/Logger.class.php';

class SpotSearchPage extends Page {
	
	/**
	 * Via: Zoeken, tab 'spots'.
	 * Template: search.ajax.spot.tpl
	 * @WebAction
	 */
	public function search() {
		$q = $this->getString("q");
		$cat = $this->getString("spotcategory");
		if (empty($cat)) {
			$cat = 0;	
		}
		// Pulldown text
		if ($cat != 0) {
			$this->put("spotcategory_text", Spot::$CATEGORIES[$cat]);
		} else {
			$this->put("spotcategory_text", 0);
		}
		$spots = null;
		
		$cityId = $this->getString("cityid");
		$zipcode = $this->getString("zipcode");
		// Als je op de zoekpagina terecht komt heb je nog niks ingevuld
		if(empty($q) && empty($cityId) && empty($zipcode)) {
			$spots = SpotUtils::getLatest($cat);
		} else {
			
			// Kijk waarop de gebruiker filtert: stad, postcode of niks
			
			$constraintList = new QueryConstraintList();
			if(!empty($q)) {
				$constraintList->add(Constraint::like("name", $q));
			}
			if (!empty($cityId)) {
				$constraintList->add(Constraint::eq("cityid", $cityId));
			}
			if (!empty($zipcode)) {
				$constraintList->add(Constraint::eq("zipcode", $zipcode));
			}
			$spots = $this->getSpotsByConstraint($constraintList, $cat);
			// Kijk of datgene waar de gebruiker op zoekt een mogelijk postcode 
			// of stad is. Zo ja, geef dan een googleachtig melding 
			// "Wil je zoeken op de postcode '1056VS'?  
			$searchAction = new SearchAction($this);
			$searchSuggestion = $this->getAlternativeSearchTypeSuggestion($searchAction);
			if ($searchSuggestion != null) {
				$this->put("searchsuggestion", $searchSuggestion->getMessage());
				$this->put("suggestedparams", $searchSuggestion->getAdditionalParams());
				$this->put("suggestedtype", $searchSuggestion->getType());
			}
			// Toon de gebruiker waarop gezocht is
			$this->put("tonen", $this->getTonen($searchAction));
		}
		//$this->put("spots", $spots);
		$json = array();
		$json["items"] = $this->toArray($spots);
		$json["template"] = file_get_contents(SMARTY_TEMPLATE_DIR."spotsearch/templates/spotresult.tpl");
		$json["spotinfohtml"] = file_get_contents(SMARTY_TEMPLATE_DIR."spotsearch/templates/spotinfohtml.tpl");
		$this->put("spots", json_encode($json));
		$this->put("aantalspots", count($spots));
		$this->put("categories", Spot::getCategories(array("Alle categorien")));
	}
	
	private function toArray($spots) {
		$aSpots = array();
		foreach($spots as $key=>$spot) {
			$aSpot = array();
			$aSpot["spotted"] = $spot->getString("aantal");
			$aSpot["categoryname"] = $spot->getFriendlyCategoryName();
			$aSpot["category"] = $spot->getString("category");
			$aSpot["name"] = $spot->getName();
			$aSpot["id"]= $spot->getKey();
			$aSpot["lat"]= $spot->getString("lat");
			$aSpot["lng"]= $spot->getString("lng");
			$aSpot["cityname"]= $spot->getString("cicityname");
			$aSpots[] = $aSpot;
			//DebugUtils::debug($aSpot);
		}		
		//DebugUtils::debug($aSpots);
		return $aSpots;
	}
	
	private function getSpotsByConstraint(QueryConstraint $c, $cat) {
		$user = UserFactory::getSystemUser();
		$oq = ObjectQuery::build(new Spot(), $user);
		$oq->addConstraint($c);
		if (!Utils::isEmpty($cat)) {
			$oq->addConstraint(Constraint::eq("category", $cat));
		}
		$oq->setSearcher(new SpotsSearcher());
		return SearchObject::search($oq);
	}
	
	private function getTonen(SearchAction $searchAction) {
		$type = $searchAction->getType();
		$q = $searchAction->getQueryString();
		switch($type) {
			case SearchAction::$TYPE_NONE:
				return "Tonen: spots met de naam \"".$q."\"";
			case SearchAction::$TYPE_CITY:
				return "Tonen: spots in \"".$this->getString("cityname")."\"";
			case SearchAction::$TYPE_ZIPCODE:
				return "Tonen: spots in de postcode \"".$this->getString("zipcode")."\"";
			default: 
				Logger::warn(
					"Er wordt gezocht op een alternatief type dat helemaal niet bestaat!",
					 __FILE__, __LINE__);
				return "Tonen: spots met de naam \"".$q."\""; 
		}
	}
	
	private function getAlternativeSearchTypeSuggestion($searchAction) {
		
		$q = $searchAction->getQueryString();
		if ($searchAction->getType() != SearchAction::$TYPE_NONE) {
//			return new SearchSuggestion(
//								"Toon weer alle spots met de naam \"".$q."\"?");
			return null;
		}
		
		$alternativeType = $searchAction->getAlternativeTypeSuggestion();
		if ($alternativeType == null) {
			return null;
		}		
		if ($alternativeType == SearchAction::$TYPE_CITY) {
			return new SearchSuggestion(
								"Wil je zoeken naar spots in \"".ucfirst($q)."\"?",
								$searchAction->getAdditionalParams(),
								$alternativeType);
		} else if ($alternativeType	== SearchAction::$TYPE_ZIPCODE) {
			return new SearchSuggestion(
						"Wil je zoeken naar spots in de postcode \"".$q."\"?",
						$searchAction->getAdditionalParams(),
						$alternativeType);
		}
		Logger::warn("Unknow searchtype: ".$alternativeType, __FILE__, __LINE__);
		return null;
	} 
	
	/**
	 * Lege "constructor"verplicht"
	 * @WebAction
	 */
	public function overview() {}
	
}

class SpotsSearcher extends Searcher {
	function getFields(DataSource $ds) {
		return "COUNT(*) AS aantal, spot.*, city.cicityname";
	}

    function getTables(DataSource $ds) {
    	return "FROM `myspot`" .
    			" JOIN spot " .
    			" ON myspot.spotid = spot.systemid " .
				" JOIN city " .
				" ON city.systemid = spot.cityid";				
    }

    function getFilter(DataSource $ds) {
    	return new QueryConstraintList();
    }
    
    function getGroupBy(DataSource $ds) {
    	 return "spot.systemid";
    }
    
    function getOrderBy(DataSource $ds) {
    	return "aantal DESC";
    }
}
?>
