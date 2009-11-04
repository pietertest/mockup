<?php
include_once(PHP_CLASS."entities/db/DBInfo.class.php");

class DatabaseTableModel extends DataModel /** was DataSource */{
	
	private $database = null;
	private $table = null;
	private $cachedTable = null;
	
	public function __construct($database, $table) {
		$this->database = $database;
		$this->table = $table;
	}
	
	public function getTable() {
		// TODO: cache staat nog uit!
		if(!($this->cachedTable = Cache::get("Table.".$this->database.".".$this->table))) {
			$this->cachedTable = DBInfo::getTable($this->database, $this->table);
			Cache::store("Table.".$this->database.".".$this->table, $this->cachedTable);
		}
		return $this->cachedTable;
	}
	
	public function getData() {
		return $this->getFields();
	}
	
//	public function getTable() {
//		if($this->cachedTable == null) {
//			DebugUtils::debug("getting table");
//			$this->cachedTable = DBInfo::getTable($this->database, $this->table);
//		}
//		return $this->cachedTable;
//	}
	
}
?>
