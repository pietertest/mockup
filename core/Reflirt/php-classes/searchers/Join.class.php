<?php

class Join {
	
	private $table;
	private $column;
	
	public function __construct($table, $column) {
		$this->table = $table;
		$this->column = $column;
	}
	
	public function getTable() {
		return $this->table;
	}
	
	public function getColumn() {
		return $this->column;
	}

}

?>