<?php
include_once PHP_CLASS.'searchers/Searcher.class.php';
include_once PHP_CLASS.'searchers/QueryConstraintList.class.php';

class ACSearcher extends Searcher{

	public $table = null; // Table object

	function ACSearcher($table) {
    	$this->table = $table;
    }

    function getFields(DataSource $ds) {
    	return "*";
    }

    function getTables(DataSource $ds) {
    	return "FROM ".
    		$this->table->getDatabaseName().".".
    		$this->table->getTableName()." ";
    }

    function getFilter(DataSource $ds) {
    	return new QueryConstraintList();
//		$list->addKey("subject", "Test onderwerp");
    }
}
?>