<?php
include_once(PHP_CLASS."core/DataSource.class.php");

class DataModel extends DataSource {
	
	public function setKey($key) {
		$this->put("systemid", $key);
	}
	public function removeKey() {
		$this->remove("systemid");
	}
	
	public function delete() {
		// dlete code hier
	}
	public function save() {
		// save code hier
	}
	public function load() {
		// load code hier
	}
    
}
?>