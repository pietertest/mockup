<?php
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';

class WinkelOproep extends OproepEntity {

	public function validate() {
    	parent::validate();
    	if(!$this->skipImportValidation) {
			Utils::validateNotEmpty("Vul een waarde in voor Winkel", $this->get("spotid"), "spotname");
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
    	return self::$CATEGORIES[self::$CAT_WINKEL];
    }
    
    /**
     * Implementation of Categorie
     */
    function getExtraKeywords(){
    	return array("winkel");
    }
}

?>