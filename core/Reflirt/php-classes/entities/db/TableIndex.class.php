<?php

class TableIndex {
	private $indexName;
	private $indexType;
	private $columns = array();

    function TableIndex($indexName, $indexType) {
    	$this->indexName = $indexName;
    	$this->indexType = $indexType;
    }
    
    function addColumn($column) {
    	$this->columns[] = $column;
    }
    
    function getIndexColumnNames() {
    	return $this->columns;
    }
    
    function getName() {
    	return $this->indexName;
    }
    
    function getType() {
    	return $this->indexType;
    }
    
}
?>