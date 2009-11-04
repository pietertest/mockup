<?php
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';

class SchoolOproep extends OproepEntity {
	
	public function validate() {
    	parent::validate();
		Utils::validateNotEmpty("Vul een waarde in bij School", $this->get("spotid"), "spotname");
    }
    
    /**
     * Implementatie van IOproep
     */
    function getLabel(){
    	return self::$CATEGORIES[self::$CAT_SCHOOL];
    }
    
    /**
     * Implementation of Categorie
     */
    function getExtraKeywords(){
    	return array("school");
    }
}

?>