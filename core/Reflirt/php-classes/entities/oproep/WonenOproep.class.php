<?php
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';

class WonenOproep extends OproepEntity {
	

	public function validate() {
    	parent::validate();
		Utils::validateNotEmpty("Vul de omgeving in (bijv. je woonplaats)", $this->get("cityid"), "cityname");
    }
    
    
	public function spotIsMandatory() {
		return false;		
	}
    
    /**
     * Implementatie van IOproep
     */
    function getLabel(){
    	return self::$CATEGORIES[self::$CAT_WONEN];
    }
    
    /**
     * Implementation of Categorie
     */
    function getExtraKeywords(){
    	return array("buurt");
    }
    
}

?>