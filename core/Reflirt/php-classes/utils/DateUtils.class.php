<?php

class DateUtils {

    /**
     * @param $date Timestamp
     */
    static function getDateTime($timestamp, $format = "Y-m-d H:i:s") {
    	return date($format, (int)$timestamp);
    }
    
    static final function formatDateTime($datetime, $format = "%a %d %B %Y") {
		/* Output: vrijdag 22 december 1978 */
    	$timestamp = self::DateTime2Timestamp($datetime);
		return strftime($format, $timestamp);
    }
    
	static function isEmptyDate($date) {
    	return $date == "0000-00-00 00:00:00" || $date == "1970-01-01 01:00:00";
    }
    
    static final function stringToDate($str) {
    	return date("Y-m-d H:i:s", strtotime($str));
    }
    
    static function getEmptyDate() {
    	return "0000-00-00 00:00:00";
    }

    static function now($format = "Y-m-d H:i:s") {
    	return date($format, mktime());
    }
    
    function DateTime2Timestamp($datetime){
    	return strtotime($datetime);
	}
	
	public static function getDateDiffSeconds($dateTime1, $dateTime2) {
		return DateUtils::DateTime2Timestamp($dateTime1) - DateUtils::DateTime2Timestamp($dateTime2);
	}
	
	public static function getDateDiffDays() {
		
	}
}
?>