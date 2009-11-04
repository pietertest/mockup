<?php

class Translator {
	
	private static $currentPage;
	private static $country = "NL";
	private static $language = "nl";
    
    
    /**
     * Verandert de taal als de juiste parameters mee worden gegeven
     */
    public static function locale($ds) {
	    self::$currentPage = $ds->getString("currentPage");
		$lang = $ds->getString("_lang_country");
		$country = $ds->getString("_lang_language");
		if(!empty($lang)) {
			self::$country = $lang;
			self::$language = $country;
    	}		
    }
    
    public function getCountry() {
    	return self::$country;
    }
    public function getLanguage() {
    	return self::$language;
    }
    public function getCurrentPage() {
    	return self::$currentPage;
    }
}
?>
