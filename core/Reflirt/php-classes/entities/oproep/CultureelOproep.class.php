<?php

include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';

class CultureelOproep extends OproepEntity {
	
    public function getCatNr() {
    	return Oproep::$CAT_CULTUREEL;
    }
    
    public function validate() {
    	parent::validate();
    	if(!$this->skipImportValidation) {
			Utils::validateNotEmpty("Vul een culturele instelling in", $this->get("spotid"));
		}
    }
    
	public function spotIsMandatory() {
		if($this->skipImportValidation) {
			return false;		
		}
		return true;
	}
    
    
    /**
     * Implementatie van IOproep
     */
    function getLabel(){
    	return self::$CATEGORIES[self::$CAT_CULTUREEL];
    }
    
    /**
     * Implementation of Categorie
     */
    function getExtraKeywords(){
    	return array();
    }
    
}

?>