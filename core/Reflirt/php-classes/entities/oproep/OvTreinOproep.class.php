<?php
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';

class OvTreinOproep extends OproepEntity {
	
	public function validate() {
    	parent::validate();
		Utils::validateNotEmpty("Vul een eindplaats in", $this->get("cityid"), "cityname");
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
    	return array("trein", "sneltrein", "intercity");
    }
    
	/**
	 * @Override
	 */
    public function spotIsMandatory() {
		return false;		
	}
}

?>