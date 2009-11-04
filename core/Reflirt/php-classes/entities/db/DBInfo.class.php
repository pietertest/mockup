<?php
include_once(PHP_CLASS."entities/db/Database.class.php");

/**
 * Deze klasse wordt niet meer gebruikt als het goed is. In DatabaseTableModel
 * wordt direct de tabel uit de cache gehaald.
 */
class DBInfo {
	private static $DATABASES = array();
	
	public static function getTable($database, $table) {
		$db = self::getDatabase($database);
		$table = $db->getTable($table); // halt hem uit de cache
		return $table;
	}
	
	public static function getDatabase($database) {
		if (!isset(self::$DATABASES[$database])) {
			self::$DATABASES[$database] = new Database($database);
		}
		return self::$DATABASES[$database];
	}
}
?>
