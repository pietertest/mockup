<?php
include_once PHP_CLASS.'utils/ZipcodeUtils.class.php';
/**
 * Deze klasse is handig om een type (bijvoorbeeld zoeken op Postcode of Stad) 
 * te halen en de daadwerkelijk zoekquery. Tevens kan eenvoudig een suggestie 
 * gegevens worden voor een type opdracht. Als je bijvoorbeeld amsterdam intypt
 * dan geeft dit ding als alternatieve type zoekopdracht "Stad" terug.
 * 
 */
class SearchAction {

    private $keywords;
    public static $TYPE_NONE = "";
    public static $TYPE_CITY = "city";
    public static $TYPE_ZIPCODE = "zipcode";
    
    private $keyword;
    private $isCity = null;
    private $additionalParams = null;
    private $type = null;
    
    public function SearchAction(DataSource $ds) {
    	$this->keywords = $ds->getString("q");
    	$this->type = $ds->getString("type");
    	if(empty($this->type)) {
    		$cityid = $ds->getString("cityid");
    		$zipcode = $ds->getString("zipcde");
    		if(!empty($cityid)) {
    			$this->type = self::$TYPE_CITY;
    		} else if(!empty($zipcode)) {
    			$this->type = self::$TYPE_ZIPCODE;
    		}
    	}
    	
    }
    
    public function getType() {
    	return $this->type;
    }
    
    public function getQueryString() {
    	return $this->keywords;
    }
    
    public function getAdditionalParams() {
    	return $this->additionalParams;
    }
    
    public function getAlternativeTypeSuggestion() {
    	if($this->isCity() && $this->type != SearchAction::$TYPE_CITY) {
    		$this->additionalParams = "cityname=".ucfirst($this->keywords)."&cityid=".$this->cityId;
    		return self::$TYPE_CITY;
    	} else if ($this->isZipCode() && $this->type != SearchAction::$TYPE_ZIPCODE) {
    		$this->additionalParams = "zipcode=".$this->keywords;
    		return self::$TYPE_ZIPCODE;
	   	}
	   	return null;
    }
    
    public function isZipCode() {
    	return ZipCodeUtils::isZipCode($this->keywords);
    }
    
    public function isCity() {
    	if ($this->isCity == null) {
    		$systemUser = UserFactory::getSystemUser();
	    	$oq = ObjectQuery::buildACS(new City(), $systemUser, 1);
	    	$oq->addConstraint(Constraint::eq("cicityname", $this->keywords));
	    	$cities = SearchObject::search($oq);
	    	if (count($cities) > 0) {
	    		$this->cityId = $cities[0]->getKey();
	    		$this->isCity = true;
	    	} else {
	    		$this->isCity = false;
	    	} 
    	}
    	return $this->isCity;
    }
    
    
}
?>