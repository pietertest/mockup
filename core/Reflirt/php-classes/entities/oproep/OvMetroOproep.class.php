<?php
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';

class OvMetroOproep extends OproepEntity {
	
	public function validate() {
    	parent::validate();
		Utils::validateNotEmpty("Vul een plaats in", $this->get("cityid"), "cityname");
    }
    
    /**
     * Implementatie van IOproep
     */
    function getLabel(){
    	return self::$CATEGORIES[self::$CAT_EVENEMENT];
    }
    
    /**
     * Implementation of Categorie
     */
    function getExtraKeywords(){
    	return array("metro");
    }
    
	/**
	 * @Override
	 */
    public function spotIsMandatory() {
		return false;
	}
}
?>