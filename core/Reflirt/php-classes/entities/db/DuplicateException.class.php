<?php
include_once PHP_CLASS.'core/EntityException.class.php';

class DuplicateException extends EntityException{
	
	private $field;
	private $value;
	
	public function __construct($message, $field, $value) {
		parent::__construct($message);
		$this->field = $field;
		$this->value = $value;
	}
	
	public function getField() {
		return $this->field;
	}
	
	public function getValue() {
		return $this->value;
	}
	
}
?>