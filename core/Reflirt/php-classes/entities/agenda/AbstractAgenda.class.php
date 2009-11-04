<?php

abstract class AbstractAgenda extends DatabaseEntity {

    function __construct($database, $table) {
    	parent::__construct($database, $table);
    }
    
    public function getSummeray() {
    	return "Op deze datum jah!";
    }
    
}
?>