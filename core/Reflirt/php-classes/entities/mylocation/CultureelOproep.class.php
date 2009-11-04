<?php

include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/Oproep.class.php';

class CultureelOproep extends Oproep{
	private $zoekid = null;
	private $fulltextColumns = array();
	private $label = "Cultureel";
	
    function CultureelOproep() {
    	parent::__construct("reflirt_nieuw", "CAT_CULTUREEL");
    	$this->fulltextColumns[] = "LAND"; 
    	$this->fulltextColumns[] = "PLAATSNAAM"; 
    	$this->fulltextColumns[] = "CULTUREEL_SOORT"; 
    }
    
    public function getCatNr() {
    	return Oproep::$CAT_CULTUREEL;
    }
    
    function getLabel(){
    	return $this->label;
    }
    
    /**
     * Implementation of Categorie
     */
    function getFulltextColumns(){
    	return $this->fulltextColumns;
    }
    
    /**
     * Implementation of Categorie
     */
    function getExtraKeywords(){
    	return $keywords;
    }
    
    function getHtml($page){
		$html .= '<div class="value">';
    	$html .= $this->getString('CULTUREEL_SOORT');
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