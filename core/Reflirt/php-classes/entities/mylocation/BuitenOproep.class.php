<?php

include_once PHP_CLASS.'utils/Utils/class.php';
include_once PHP_CLASS.'entities/oproep/Oproep.class.php';

class BuitenOproep extends Oproep{
	private $zoekid = null;
	private $fulltextColumns = array();
	private $label = "Buiten/Op straat";
	public $valuecolumn = "d";

    function BuitenOproep() {
    	parent::__construct("reflirt_nieuw", "CAT_BUITEN");
    	$this->fulltextColumns[] = "LAND";
    	$this->fulltextColumns[] = "PLAATSNAAM";
    }
    
    public function getCatNr() {
    	return Oproep::$CAT_BUITEN;
    }

    function getLabel(){
    	return $this->label;
    }

    function getFulltextColumns(){
    	return $this->fulltextColumns;
    }
    
    function getExtraKeywords(){
    	$keywords = "buiten";
    	return $keywords;
    }

    function getHtml($page){
    	$html .= $this->getString('LAND');
    	$html .= ", ";
    	$html .= $this->getString('PLAATSNAAM');
    	$html .= "<br/>";
    	$html .= $this->getString('ZOEKDATUM');
    	return $html;
    }
}

?>