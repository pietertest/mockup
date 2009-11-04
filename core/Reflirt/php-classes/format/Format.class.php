<?php
include_once PHP_CLASS.'format/Formatter.class.php';

class Format {

    private $formatter = null;
    public static $DATE = 1;
    
    function __construct($format) {
    	$this->formatter = $format;
    }
    
    public function getFormatter() {
    	$formatter = null;
    	switch($this->formatter) {
			case self::$DATE: 
				$formatter = new DateFormat();
				break;
			default: 
				throw new RuntimeException("No valid formatter: ".$formatter);
    	}
    	return $formatter; 
    }
}

?>