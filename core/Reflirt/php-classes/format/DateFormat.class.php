<?php

class DateFormat implements Formatter {
	
	private $value;
	private $DAY 	= 2;
	private $MONTH 	= 1;
	private $YEAR 	= 0;
	
	public function setValue($value) {
		$this->value = $value;
	}
	
	public function parse() {
		if(empty($this->value)) {
			return "";
		}
		return date("Y-m-d H:i:s", strtotime($this->value));
	}
	
}
?>