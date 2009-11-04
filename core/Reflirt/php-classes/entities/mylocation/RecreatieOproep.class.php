<?php

include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/Oproep.class.php';

class RecreatieOproep extends Oproep{
	private $zoekid = null;
	private $fulltextColumns = array();
	private $label = "Recreatie/Sport";
	private $valuecolumn = "DISCO_NAAM";
	
    function RecreatieOproep() {
    	parent::__construct("reflirt_nieuw", "CAT_RECREATIE");
    	$this->setSystemidComulmnName("ZOEK_ID");
    	$this->fulltextColumns[] = "LAND"; 
    	$this->fulltextColumns[] = "PLAATSNAAM"; 
    	$this->fulltextColumns[] = "RECREATIE_SOORT"; 
    }
    
    function getLabel(){
    	return $this->label;
    }
    
    function getCatValue(){
    	return $this->getString($this->valuecolumn);
    }
    
    function getFulltextColumns(){
    	return $this->fulltextColumns;
    }
    /**
     * Implementation of Categorie
     */
    function getExtraKeywords(){
    	$keywords = "recreatie sport";
    	return $keywords;
    }
    
    function getHtml(){
    	$html .= '<div class="value">';
    	$html .= $this->getString('RECREATIE_SOORT');
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