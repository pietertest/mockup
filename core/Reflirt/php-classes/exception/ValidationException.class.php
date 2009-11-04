<?php

class ValidationException extends Exception {
	
	private $field;
	
	public function __construct($e, $field=null) {
		$this->field = $field;
		parent::__construct($e);
	}
	
	public function getField() {
		return $this->field;
	}
	

}

?>