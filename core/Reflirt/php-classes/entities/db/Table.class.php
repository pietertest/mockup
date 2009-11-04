<?php
include_once PHP_CLASS.'cache/Cache.class.php';
include_once PHP_CLASS.'entities/db/PreparedQuery.class.php';
include_once PHP_CLASS.'entities/db/TableIndex.class.php';
include_once PHP_CLASS.'entities/db/Column.class.php';

class Table{
	private $table;
	private $database;
	private $columns = array();
	private $indexes = array();
	private $keyColumn = -1;

	private $ownerColumn = null;
	private $otherUserColumn = null;
	private $lastUpdateColumn = null;
	private $insertDateColumn = null;
	private $foreignkeys = array();

	const OWNER_COLUMN = "ownerColumn";
	const OTHER_USER_COLUMN = "otherUserColumn";
	const LASTUPDATE_COLUMN = "lastupdateColumn";
	const INSERT_DATE_COLUMN = "insertDateColumn";

    function Table($database, $table) {
    	$this->database = $database;
    	$this->table = $table;
    	$this->loadIndexData();
    	$this->loadColumnData();
    	$this->loadTableInfoFromXML();
    	$this->checkColumns();
    }

    /**
     * Nu alle velden bekend zijn, kijk dan of de informatie uit database.xml
     * klopt.
     */
    function checkColumns() {
    	if($this->ownerColumn != null) {
	    	Utils::assertTrue("Invalid column in database.xml: ".$this->table. ".".$this->ownerColumn,
	    		$this->hasColumn($this->ownerColumn));
    	}
    	if($this->lastUpdateColumn != null) {
	    	Utils::assertTrue("Invalid column in database.xml: ".$this->table. ".".$this->lastUpdateColumn,
	    		$this->hasColumn($this->lastUpdateColumn));
    	}
    	if($this->insertDateColumn != null) {
	    	Utils::assertTrue("Invalid column in database.xml: ".$this->table. ".".$this->insertDateColumn,
	    		$this->hasColumn($this->insertDateColumn));
    	}
    }
    
    public function getForeignKeys() {
    	return $this->foreignkeys;
    }

    function loadTableInfoFromXML(){
    	$xml= new XMLReader();
    	$xml->open(DATABASE_XML);
 		$xml->read(); // Skip root node
 		
 		$current_database = "";
 		$current_table = "";
 		$found = false;
 		while($xml->read()) {
 			switch($xml->name) {
 				case 'database':
 					$current_database = $xml->getAttribute('name');
 					$xml->read();
 					break;

 				case 'table':
 					if($found) {
 						$xml->close();
 						return true;
 					}
 					$current_table = $xml->getAttribute('name');
	 				if($current_database == $this->database &&
		 				$current_table == $this->table) {
		 					$this->ownerColumn = $xml->getAttribute(self::OWNER_COLUMN);
		 					$this->otherUserColumn = $xml->getAttribute(self::OTHER_USER_COLUMN);
		 					$this->lastUpdateColumn = $xml->getAttribute(self::LASTUPDATE_COLUMN);
		 					$this->insertDateColumn = $xml->getAttribute(self::INSERT_DATE_COLUMN);
		 					$found = true;
//		 					$xml->close();
//	 						return true;
	 				}
 					$xml->read();
 					//echo "#".$current_table;
 					break;
 				
 				case 'foreignkey':
 					
 					$key = $xml->getAttribute('column');
 					$table = $xml->getAttribute('table');
// 					echo "##FOREIGNKEYS:".$table.".".$key;
 					$this->foreignkeys[$key] = $table;
 					$xml->read();
 					break; 
 			}
 		}

 		$xml->close();
		throw new RuntimeException("Tabel '$this->database.$this->table' niet in database.xml");
 		return true;
    }

    /**
     * @todo Filecahce gebruiken hier?
     *
     */
    function loadIndexData() {
    	$db = new PreparedQuery($this->database);
		$query = "SHOW keys FROM ".$this->database.".".$this->table;
		$db->setQuery($query);
		$rs = $db->execute();

		Utils::assertTrue("No indexes defined (so no primary?!) on table: "
			.$this->getDatabaseName().".".$this->getTableName(), count($rs) > 0);
		$index_type = '';
		$list = array();
		foreach($rs as $key=>$index) {
			$index_name = $index['Key_name'];
			if ($index['Index_type'] == 'FULLTEXT') {
	            $index_type = 'FULLTEXT';
	        } else if ($index_name == 'PRIMARY') {
	            $index_type = 'PRIMARY';
	            $this->keyColumn = $index['Column_name'];
	        } else if ($index['Non_unique'] == '0') {
	            $index_type = 'UNIQUE';
	        } else {
	            $index_type = 'INDEX';
	        }
	        if(isset($list[$index_name])) {
	        	$temp = (object)$list[$index_name]; // TableIndex
	        	$temp->addColumn($index['Column_name']);
	        } else {
	        	$temp = new TableIndex($index_name, $index_type);
	        	$temp->addColumn($index['Column_name']);
	        	$list[$index_name] = $temp;
	        }
		}
		$this->indexes = $list;
    }

    function loadColumnData() {
		$query = "DESCRIBE ".$this->database.".".$this->table;
		$db = new PreparedQuery($this->database);
		$db->setQuery($query);
		$rs = $db->execute();
		$index_type = '';
		$list = array();
		foreach($rs as $index=>$column) {
			$this->columns[$column['Field']] = new Column($column['Field'], $column['Type'], $column['Null']);
		}
    }

    function getIndexes() {
		return $this->indexes;
    }

	function getTableName() {
    	return $this->table;
    }

	function getDatabaseName() {
    	return $this->database;
    }

    function getColumns() {
    	return $this->columns;
    }
    
    function getColumn($column) {
    	if(!empty($this->columns[$column])) {
    		return $this->columns[$column];
    	}
    	return null;
    }

    function getKeyColumn() {
    	return $this->keyColumn; // Primary key
    }

    function hasColumn($columnName) {
    	return isset($this->columns[$columnName]);
    }

    function hasOwnerColumn() {
    	return isset($this->ownerColumn);
    }
    
    function getOwnerColumn() {
//    	if(!$this->hasOwnerColumn()) {
//    		throw new Exception(
//    		'No owner column specified in database.xml on table: '.
//    		$this->database.".".$this->table);
//    	}
    	return $this->ownerColumn;
    }
    
	function hasOtherUserColumn() {
    	return isset($this->otherUserColumn);
    }
    
    function getOtherUserColumn() {
    	return $this->otherUserColumn;
    }

	function getLastUpdateColumn() {
    	return $this->lastUpdateColumn;
    }

	function getInsertDateColumn() {
    	return $this->insertDateColumn;
    }
}
?>