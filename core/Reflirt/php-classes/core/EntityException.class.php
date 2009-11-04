<?php

class EntityException extends Exception{
	private $Ex;
	
    function __construct($message, $alert=0) {
		parent::__construct($message);
		$this->Ex = $message;
    }
    
    function toString() {
    	return $this->Ex; 
    }
    
}
?>