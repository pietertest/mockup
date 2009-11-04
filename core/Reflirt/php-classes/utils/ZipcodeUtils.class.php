<?php

class ZipcodeUtils {
	
	public function isZipCode($zipcode) {
		return preg_match("!^[0-9]{4}[a-zA-Z]{0,2}$!", $zipcode);
	}

    function __construct() {
    	throw new IllegalStateException("No initialization of utils class allowed, stupid:)");    	
    }
}
?>