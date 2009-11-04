<?php

class Database {
	private $tables = null;		// Table objecten
	private $database = null;	// Database naam
	
	
    function Database($database) {
    	$this->database = $database;
    }
    
    public function setTables(array $tables) {
    	$this->tables = $tables;
    }
    
    public function getTableNames() {
    	return $this->tables;
    }
    
    public function getTable($table) { // cachen..?
		return new Table($this->database, $table);
    }
}
?>