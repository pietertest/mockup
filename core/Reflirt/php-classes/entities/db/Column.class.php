<?php

class Column {

	private $type;
	private $length;
	private $name;
	private $nullable;
	
    function Column($name, $type, $nullable) {
    	$this->name 	= strtolower($name);
    	$this->initTypeAndLenth($type);
    	$this->nullable = strtolower($nullable);
    }
    
    private function initTypeAndLenth($type) {
    	$split = preg_split("/[\(\)]+/", $type); // Maak van "varchar(255)" -> "varchar"
    	if(count($split) > 1) {
    		$this->type = $split[0];
    		$this->length = $split[1];
    	} else {
    		$this->type = $type;
    	}    	
    }
    
    public function getType() {
	    return $this->type;
    }
    
    public function getLength() {
    	return $this->length;
    	
    }
    
    public function getName() {
    	return $this->name;
    }
    
    public function isDateTime() {
    	return $this->type == ColumnType::$DATETIME;
    }
    
    public function isNullable() {
    	return $this->nullable == "yes";
    }
}
?>