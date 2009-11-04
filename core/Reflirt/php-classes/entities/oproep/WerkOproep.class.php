<?php
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';

class WerkOproep extends OproepEntity {
	public function validate() {
    	parent::validate();
		Utils::validateNotEmpty("Vul een waarde in voor Werk", $this->get("spotid"), "spotname");
    }
    
    /**
     * Implementatie van IOproep
     */
    function getLabel(){
    	return self::$CATEGORIES[self::$CAT_WERK];
    }
    
    /**
     * Implementation of Categorie
     */
    function getExtraKeywords(){
    	return array("werk");
    }
    
}

?>