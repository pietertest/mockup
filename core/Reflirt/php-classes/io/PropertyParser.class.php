<?php

class PropertyParser {

    public static function getValue($file, $line) {
    	$propfile = parse_ini_file($file);
    	if(isset($propfile[$line])) {
    		return $propfile[$line];
    	}
    	if(DEBUG) {
    		throw new Exception("No l81n specified in '$file' for line: ".$line);
    	}
    }
}
?>