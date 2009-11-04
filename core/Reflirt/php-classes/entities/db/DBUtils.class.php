<?php
include_once(PHP_CLASS."format/DateFormat.class.php");
include_once(PHP_CLASS."entities/db/ColumnType.class.php");
class DBUtils {

    public static function getFormatter(Column $col) {
		; // maak van "varchar(255)" -> "varchar"
		switch($col->getType()) {
			case ColumnType::$DATETIME: 
				return new DateFormat();
			default:
				return null;
		}
    }
    
    private function DBUtils() {} // Niet instantieren
    
    public static function dbEscape($value) {
    	//throw new IllegalStateException("Deze methode (DBUtils::dbEscape()) niet meer gebruiken");
    	return mysql_escape_string($value);
    }
}
?>