<?php

include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/Oproep.class.php';

class OvBusOproep extends Oproep{
	private $zoekid = null;
	private $fulltextColumns = array();
	private $label = "Bus";

    function OvBusOproep() {
    	parent::__construct("reflirt_nieuw", "CAT_OV_BUS");
    	$this->setSystemidComulmnName("ZOEK_ID");
    	$this->fulltextColumns[] = "BUS_LIJNNR"; 
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
    	$keywords = "bus";
    	return $keywords;
    }
    
	function getHtml(){
    	$html .= '<div class="value">';
    	$html .= $this->getString('BUS_LIJNNR');
    	$html .= '</div>';
    	$html .= $this->getString('ZOEKDATUM');
    	return $html;
    }
}
?>