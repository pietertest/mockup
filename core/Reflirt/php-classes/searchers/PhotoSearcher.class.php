<?php
include_once(PHP_CLASS."Searcher.class.php");

class PhotoSearcher extends Searcher{

    function getFields(){
    	return " SELECT * ";
    }
    
    function getTables(){
    	return " FROM fotos2 ";
    }
    
    function getFilter(DataSource $ds) {
    	$zoekid = $this->getInt("ZOEK_ID");
    	Utils::assertTrue("zoekid == -1", $zoekid > -1);
       	return " WHERE NICK ='" . $this->getString("NICK") . "' " .
    			"AND ZOEK_ID = ".$zoekid;
    }
}
?>