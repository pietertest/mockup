<?php

include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/Oproep.class.php';

class WonenOproep extends Oproep {
	
    function __construct() {
    	parent::__construct("reflirt_nieuw", "");
    }
    
    function getMatches() {
    	
    }
    
    /** Implementation of OproepInterface */
    public function getTitle() {
    	return "Woonomgeving ".$this->getString("cicityname");
    }
    
    public function getMatchSearcher() {
    	
    }
    
    public function getFulltextColumns() {}
    public function getExtraKeywords() {}
    public function getOproepLoadSearcher() {}
    public function getDefaultSearcher() {}
    
}

?>