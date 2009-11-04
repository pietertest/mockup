<?php

include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';

class ErrorPage extends Page {
	
	/** @WebAction*/
	public function overview(){}
	
	/** @WebAction*/
	public function error() {}
	
}

?>
