<?php
include_once(PHP_CLASS.'Searcher.class.php');
include_once(PHP_CLASS.'SearcherInterface.class.php');

class OproepSearcher extends Searcher{

    function getFields(){
    	return " * ";    	
    }
    
    function getTables(){
    	return " FROM ZOEKOPDRACHTEN WHERE NICK = "; 
    }
    
    function getFilter(DataSource $ds) {
    	return null;
    }
}
?>