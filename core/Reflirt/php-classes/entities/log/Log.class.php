<?php
include_once PHP_CLASS.'entities/db/DatabaseENtity.class.php';

class Log extends DatabaseEntity {
	
	public function __construct() {
		parent::__construct("log");
	}

}

?>