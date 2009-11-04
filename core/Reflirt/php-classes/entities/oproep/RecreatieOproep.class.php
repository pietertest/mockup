<?php
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';

class RecreatieOproep extends OproepEntity {
	
	

	public function validate() {
    	parent::validate();
		Utils::validateNotEmpty("Vul een plaats in ", $this->get("cityid"), "cityname");
    }
    
    /**
     * @Override
     */
    public function spotIsMandatory() {
    	return false;
    }
    
    public static final function getSports() {
		throw new IllegalStateException("Not yet implemented"); 
    	return array(
			1 => "Judo",
			3 => "Tennis"
		);	
    }
    
    /**
     * Implementatie van IOproep
     */
    function getLabel(){
    	return self::$CATEGORIES[self::$CAT_RECREATIE];
    }
    
    /**
     * Implementation of Categorie
     */
    function getExtraKeywords(){
    	return array("recreatie");
    }
}

?>