<?php

include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/Oproep.class.php';

class ParkOproep extends Oproep{
	private $zoekid = null;
	private $fulltextColumns = array();
	private $label = "(Thema)Park";
	private $valuecolumn = "DISCO_NAAM";

    function ParkOproep() {
    	parent::__construct("reflirt_nieuw", "CAT_PARK");
    	$this->setSystemidComulmnName("ZOEK_ID");
    	$this->fulltextColumns[] = "LAND"; 
    	$this->fulltextColumns[] = "PLAATSNAAM"; 
    	$this->fulltextColumns[] = "PARK_NAAM";
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
    	$keywords = "park";
    	return $keywords;
    }
    
    function getHtml(){
    	$html .= '<div class="value">';
    	$html .= $this->getString('PARK_NAAM');
    	$html .= '</div>';
    	$html .= $this->getString('LAND');
    	$html .= ", ";
    	$html .= $this->getString('PLAATSNAAM');
    	$html .= "<br/>";
    	$html .= $this->getString('ZOEKDATUM');
    	return $html;
    }
}

?>