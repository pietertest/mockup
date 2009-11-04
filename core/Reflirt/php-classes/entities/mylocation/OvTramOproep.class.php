<?php

include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/Oproep.class.php';

class OvTramOproep extends Oproep{
	private $zoekid = null;
	private $fulltextColumns = array();
	private $label = "Tram";

    function OvTramOproep() {
    	parent::__construct("reflirt_nieuw", "CAT_OV_TRAM");
    	$this->setSystemidComulmnName("ZOEK_ID");
    	$this->fulltextColumns[] = "TRAM_LIJNNR"; 
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
    	$keywords = "tram";
    	return $keywords;
    }
    
  	function getHtml(){
    	$html .= '<div class="value">';
    	$html .= $this->getString('TRAM_LIJNNR');
    	$html .= '</div>';
    	$html .= $this->getString('ZOEKDATUM');
    	return $html;
    }
}

?>