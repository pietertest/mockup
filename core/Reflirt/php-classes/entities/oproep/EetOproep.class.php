<?php
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';

class EetOproep extends OproepEntity {
    
	public function validate() {
    	parent::validate();
    	if(!$this->skipImportValidation) {
			Utils::validateNotEmpty("Vul een eetgelegenheid in", $this->get("spotid"), "spotname");
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
    	return self::$CATEGORIES[self::$CAT_EETEGE];
    }
    
    /**
     * Implementation of Categorie
     */
    function getExtraKeywords(){
    	return array("eetgelegenheid");
    }
    
    
}

?>