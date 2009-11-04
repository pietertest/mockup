<?php

include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/Oproep.class.php';

class WerkOproep extends Oproep{
	private $zoekid = null;
	private $fulltextColumns = array();
	private $label = "Werk";
	
    function WerkOproep() {
    	parent::__construct("reflirt_nieuw", "CAT_WERK");
    	$this->setSystemidComulmnName("ZOEK_ID");
    	$this->fulltextColumns[] = "LAND"; 
    	$this->fulltextColumns[] = "PLAATSNAAM"; 
    	$this->fulltextColumns[] = "WERK_NAAM"; 
    }
    
    function getLabel(){
    	return $this->label;
    }
    
    function getFulltextColumns(){
    	return $this->fulltextColumns;
    }
    
    /**
     * Implementation of Oproep
     */
    function getExtraKeywords(){
    	$keywords = "werk";
    	return $keywords;
    }
    
    function getHtml(){
    	$html .= '<div class="value">';
    	$html .= $this->getString('WERK_NAAM');
    	$html .= '</div>';
    	$html .= $this->getString('LAND');
    	$html .= ', ';
    	$html .= $this->getString('PLAATSNAAM');
    	$html .= "<br/>";
    	$html .= $this->getString('ZOEKDATUM');
    	return $html;
    }
}

?>