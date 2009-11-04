<?php

/**
 * Zie spotsearch.php voor gebruik 
 */

class SearchSuggestion {
	
	private $message;
	private $additionalParams;
	private $type;

    public function __construct($message, $additionalParams="", $type="") {
    	$this->message = $message;
    	$this->additionalParams = $additionalParams;
    	$this->type = $type;
    }
    
    public function getType() {
    	return $this->type;
    }
    
    public function getAdditionalParams() {
    	return $this->additionalParams;
    }
    
    public function getMessage() {
    	return $this->message;
    }
}
?>