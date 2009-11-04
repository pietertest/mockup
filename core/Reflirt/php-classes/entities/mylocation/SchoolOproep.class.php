<?php

include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/Oproep.class.php';

class SchoolOproep extends Oproep{
	private $zoekid = null;
	private $fulltextColumns = array();
	private $label = "School";
	private $valuecolumn = "DISCO_NAAM";
	
    function SchoolOproep() {
    	parent::__construct("reflirt_nieuw", "CAT_SCHOOL");
    	$this->setSystemidComulmnName("ZOEK_ID");
    	$this->fulltextColumns[] = "LAND"; 
    	$this->fulltextColumns[] = "PLAATSNAAM"; 
    	$this->fulltextColumns[] = "SCHOOL_NAAM"; 
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
    	$keywords = "school";
    	return $keywords;
    }
    
    function getHtml(){
    	$html .= '<div class="value">';
    	$html .= $this->getString('SCHOOL_NAAM');
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