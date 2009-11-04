<?php
include_once PHP_CLASS.'searchers/Searcher.class.php';

abstract class DefaultSearcher extends Searcher{
	
	function getFields(DataSource $ds) {
		return "*";
	}

//    function getTables(DataSource $ds); // implemnt in extented class

    function getFilter(DataSource $ds) {
    	return new QueryConstraintList();
    }

}
?>