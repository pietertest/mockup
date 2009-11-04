<?php
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';

class HotelOproep extends OproepEntity {

	public function validate() {
    	parent::validate();
		Utils::validateNotEmpty("Vul een hotel in", $this->get("spotid"), "spotname");
    }
    
    /**
     * Implementatie van IOproep
     */
    function getLabel(){
    	return self::$CATEGORIES[self::$CAT_HOTEL];
    }
    
    /**
     * Implementation of Categorie
     */
    function getExtraKeywords(){
    	return array("hotel", "hostel");
    }
    
}

?>