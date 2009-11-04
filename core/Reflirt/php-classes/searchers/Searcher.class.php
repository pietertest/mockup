<?php
include_once PHP_CLASS.'searchers/SearcherInterface.class.php';
include_once PHP_CLASS.'core/DataSource.class.php';


abstract class Searcher implements SearcherInterface{
	public $user = null;
	private $limit	= null;

	function addParameter($key, $value) {
    	$this->put($key, $value);
    }

	function setLimit($limit) {
    	$this->limit = $limit;
    }

    function getQuery(){
    	$query = $this->getFields();
    	$query .= $this->getTables();
    	$query .= $this->getConstraints();
    	return $query;
    }
    
    function getOrderBy(DataSource $ds) {
    	return "";
    }
    
    function getGroupBy(DataSource $ds) {
    	return "";
    }
}
?>