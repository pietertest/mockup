<?php
include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';

class BuitenOproep extends OproepEntity {
	
	public function validate() {
    	parent::validate();
		Utils::validateNotEmpty("Vul een plaats in ", $this->get("cityid"), "cityname");
    }

    public function getCatNr() {
    	return Oproep::$CAT_BUITEN;
    }
    
	public function spotIsMandatory() {
		return false;		
	}

    function getLabel(){
    	//private $label = "Buiten/Op straat";
    	return self::$CATEGORIES[self::$CAT_EVENEMENT];
    }

    function getFulltextColumns(){
    	return array();
    }
    
    function getExtraKeywords(){
    	return array("buiten");
    }

}

?>