<?php

include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/Oproep.class.php';
include_once PHP_CLASS.'entities/oproep/Oproep.class.php';

class WinkelOproep extends Oproep{
	private $zoekid = null;
	private $fulltextColumns = array();
	private $label = "Winkel";
	public $valuecolumn = "WINKEL_SOORT";
	
    function WinkelOproep() {
    	parent::__construct("reflirt_nieuw", "CAT_WINKEL");
    	$this->setSystemidComulmnName("ZOEK_ID");
    	$this->fulltextColumns[] = "LAND"; 
    	$this->fulltextColumns[] = "PLAATSNAAM"; 
    	$this->fulltextColumns[] = "WINKEL_SOORT"; 
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
    	$keywords = "winkel";
    	return $keywords;
    }
    
    function getHtml(){
    	$html .= '<div class="value">';
    	$html .= $this->getString('WINKEL_SOORT');
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