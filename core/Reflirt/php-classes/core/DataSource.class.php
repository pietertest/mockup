<?php
include_once PHP_CLASS.'core/EntityException.class.php';
include_once PHP_CLASS.'core/Map.class.php';
include_once PHP_CLASS.'utils/Utils.class.php';


class DataSource implements Map {

	public $fields = array();

	function get($key){
		if(isset($this->fields[$key])) {
			return $this->fields[$key];
		}
		return null;
	}

	function remove($key) {
		unset($this->fields[$key]);
	}
	
	function getString($key, $defaultVal = null){
		$val = $this->get($key);
		if (Utils::isEmpty($val)) {
			return $defaultVal;
		}
		return $val;
	}

	function put($key, $value){
		$this->fields[$key] = $value;
	}
		
	function putAll($data){
		if ($data == null) {
			return;
		}
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
	
	function putIf($key, $value){
		if(!Utils::isEmpty($value)) {
			$this->put($key, $value);
		}
	}

	function getAll(){
		return $this->fields;
	}
	
	function getFields() {
		return $this->fields;
	}

	function getInt($key, $default) {
		$val = $this->get($key);
		if(Utils::isInt($val)) {
			return $val;
		}
		return $default;
	}
}


?>
