<?php
include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';

class ParkOproep extends OproepEntity {
    
    public function validate() {
    	parent::validate();
		Utils::validateNotEmpty("Vul een waarde in bij Park", $this->get("spotid"), "spotname");
    }
    
    /**
     * Implementatie van IOproep
     */
    function getLabel(){
    	return self::$CATEGORIES[self::$CAT_PARK];
    }
    
    /**
     * Implementation of Categorie
     */
    function getExtraKeywords(){
    	return array("park", "pretpark");
    }
}

?>