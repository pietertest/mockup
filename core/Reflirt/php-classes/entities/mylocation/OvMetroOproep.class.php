<?php

include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/Oproep.class.php';

class OvMetroOproep extends Oproep{
	private $zoekid = null;
	private $fulltextColumns = array();
	private $label = "Metro";
	
    function OvMetroOproep() {
    	parent::__construct("reflirt_nieuw", "CAT_OV_METRO");
    	$this->setSystemidComulmnName("ZOEK_ID");
    	$this->fulltextColumns[] = "METRO_LIJNNR"; 
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
    	$keywords = "metro";
    	return $keywords;
    }
    
    function getHtml(){
    	$html .= '<div class="value">';
    	$html .= $this->getString('METRO_LIJNNR');
    	$html .= '</div>';
    	$html .= $this->getString('ZOEKDATUM');
    	return $html;
    }
}
?>