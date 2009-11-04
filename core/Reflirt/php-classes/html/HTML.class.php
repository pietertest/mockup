<?php

class HTML{

    private $elem;
    private $innerHTML;
    private $attributes = array();
    
    function __construct($elem) {
    	$this->elem = $elem;	
    }
    
    public function attr($key, $value) {
    	$this->attributes[$key] = $value;
    }
    
    public function innerHTML($html) {
    	$this->innerHTML = $html;    	
    }
    
    public function toString() {
    	$html = "<".$this->elem." ";
    	foreach($this->attributes as $key=>$value) {
    		$html .= $key."=\"".$value."\" ";
    	}
    	$html .= ">";
    	$html .= $this->innerHTML;
    	$html .= "</".$this->elem.">";
    	
    	return $html;
    }
}
?>