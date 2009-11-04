<?php
include_once PHP_CLASS.'core/Map.class.php';

abstract class AbstractDatabaseTableModel implements Map {

    protected $datamodel = null;
    
	function putAll($data) {
		if($data instanceof Map) {
			$this->putAll($data->getAll());
		} else if(is_array($data)){
			foreach ($data as $key => $value) {
				$this->put($key, $value);
			}
		} else {
			throw new RuntimeException("Invalid datatype to put on datasource!");
		}
	}
	
	function putIf($key, $value) {
		$this->datamodel->putIf($key, $value);
	} 

	function get($key) {
		return $this->datamodel->get($key);
	}
	
	function getAll() {
		return $this->datamodel->getAll();
	}
	
	function getInt($key, $default) {
		return $this->datamodel->getInt($key, $default);
	}
    
	function getString($key, $defaultVal = null) {
		$val = $this->datamodel->getString($key);
		if (Utils::isEmpty($val)) {
			$val = $defaultVal;
		}
		return $val;
	}
	
	function remove($key) {
		$this->datamodel->remove($key);
	}
    
}
?>