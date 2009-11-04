<?php

class PageErrorHandler {

    
    public static $LOG = 1; 			// De gebruiker krijgt enkel een melding: er is een fout ontstaan 
    public static $SHOW_TO_USER = 2;	// Toon de volledige fout (handig voor in DEBUG mode)
    
    public $policy = 2;
    
    private $page;  
    
    function PageErrorHandler(Page $page) {
    	$this->page = $page;
    }
    
    function handleException($e) {
    	if($this->policy == self::$SHOW_TO_USER) {
    		DebugUtils::debug($e);
    	} else if($this->policy == self::$LOG_ONLY) {
    		throw new Exception("Logging error not implemented yet!");
    	}
    }
    
    function setErrorPolicy($policy) {
    	$this->policy = $policy;
    }
}
?>