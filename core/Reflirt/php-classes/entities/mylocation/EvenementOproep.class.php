<?php

include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/Oproep.class.php';

class EvenementOproep extends Oproep{
	private $zoekid = null;
	private $fulltextColumns = array();
	private $label = "Evenement";
	
    function EvenementOproep() {
    	parent::__construct("reflirt_nieuw", "CAT_EVENEMENT");
    	$this->setSystemidComulmnName("ZOEK_ID");
    	$this->fulltextColumns[] = "LAND"; 
    	$this->fulltextColumns[] = "PLAATSNAAM"; 
    }
    
    function getLabel(){
    	return $this->label;
    }
    
    function getFulltextColumns(){
    	return $this->fulltextColumns;
    }
    /**
     * Implementation of Categorie
     */
    function getExtraKeywords(){
    	$keywords = "evenement";
    	return $keywords;
    }
    
    function getHtml(){
    	$html .= $this->getString('PLAATSNAAM');
    	$html .= "<br/>";
    	$html .= $this->getString('ZOEKDATUM');
    	return $html;
    }
}

?>