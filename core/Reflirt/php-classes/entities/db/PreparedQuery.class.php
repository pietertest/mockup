<?php	
include_once PHP_CLASS.'entities/db/DuplicateException.class.php';
include_once PHP_CLASS.'io/Logger.class.php';
include_once PHP_CLASS.'exception/IllegalStateException.class.php';

class PreparedQuery {

	public static $query_counter = 0;

	private $db;
	private $table;
	
	private static $pdo;
	private static $dblink;
	
	private static $USE_PDO		= false;
	
	private $fields 			= "";
	
	private $constraints		= null;
	
	private $updateFields		= array();
	
	private $insertFields 		= array();
	
	private $limit;
	
	private $join;
	
	private $orderby;
	
	private $groupBy;
	
	private $countrows = false;
	
	private $count_column = "rows";
	private $rows = 0;
	
	private $type = null;
	
	private $QUERY_TYPE_SELECT = 'SELECT ';
	private $QUERY_TYPE_DELETE = 'DELETE ';
	private $QUERY_TYPE_UPDATE = 'UPDATE ';
	private $QUERY_TYPE_INSERT = 'INSERT ';
	
	// Voor als je zonder preparedstatements werkt, geen security dus!
	private $query = "";
	
	private $psParams	= array();
	private $psQuery 	= "";
	
	// Toon het aantal resultaten dat gevonden zou worden ZONDER limit
	public static $COUNT_ROWS_SQL = " SQL_CALC_FOUND_ROWS ";
	
    function __construct($db) {
   		Utils::assertNotNull("No database specified for Query", $db);
    	$this->db = $db;
   		$this->constraints = new QueryConstraintList();
    }

	function setTable($table){
    	throw new IllegalStateException("Use setSelect for specifyng query type");
    }
    
	function setSelect($table){
    	$this->type = $this->QUERY_TYPE_SELECT;
    	$this->table = $table;    	
    }

    function setDelete($table) {
    	$this->type = $this->QUERY_TYPE_DELETE;
    	$this->table = $table;
    }

    function setUpdate($table) {
    	$this->type = $this->QUERY_TYPE_UPDATE;
    	$this->table = $table;
    }
    
    function setInsert($table) {
    	$this->type = $this->QUERY_TYPE_INSERT;
    	$this->table = $table;
    }

    function setSelectFields($field){
    	Utils::assertTrue("Query must be of type SELECT (first use setTable())", 
    		$this->type == $this->QUERY_TYPE_SELECT);
		$this->fields = $field;
    }
    
 	public function addUpdateField($key, $value) {
    	Utils::assertTrue("Query type != UPDATE", $this->type ==
    				$this->QUERY_TYPE_UPDATE);
    	$this->updateFields[] = array($key, $value);
//    	if(!empty($this->updateFields)) {
//    		$this->updateFields .= ", ";
//    	}
//    	if ($value == null) {
//    		$this->updateFields .= "".$key." = NULL ";
//    	} else {
//    		$this->updateFields .= "".$key." = '".addslashes($value)."' ";
//    	}
    }
    
    public function addInsertField($key, $value) {
    	Utils::assertTrue("Query type != INSERT", $this->type ==
    				$this->QUERY_TYPE_INSERT);
    	$this->insertFields[] = array($key, $value);
    }

    function addConstraint(QueryConstraint $constraint) {
    	$this->constraints->add($constraint);
    }

    
    function addFilter($field, $value){
    	$this->constraints->add(Constraint::eq($field, $value));
    }

    function addFilterGreater($field, $value){
    	$this->constraints->add(Constraint::gt($field, $value));
    }

    function addFilterNotEqual($field, $value){
    	$this->constraints->add(Constraint::neq($field, $value));
    }
    
	function addJoin($table, $column){
    	$this->join .= " INNER JOIN ".$table." ON ".$this->table.".".$column.
			" = ".$table.".systemid ";
    }

    function setMatch($keywords){
    	$this->filter .= "WHERE MATCH(f.nick, lokatie, commentaar) against('".$keywords."' IN BOOLEAN MODE)";
//   		throw new IllegalStateException("Not yet implemented");
    }

    function addMatchField($field, $value /* keywords*/ ){
    	$this->constraints->add(Constraint::match($field, $value));
//    	throw new IllegalStateException("Not yet implemented");
//   		$field = "MATCH(f.nick, lokatie, commentaar) against('".DBUtils::dbEscape($keywords)."'" .
//   				" IN BOOLEAN MODE) as ".$columnName." ";
//   		$this->addField($field);
    }

    function setLimit($limit_start, $limit_end = null){
    	$this->limit = $limit_start;
    	if(!empty($limit_end)) {
    		$this->limit .= ", ".$limit_end." ";
    	}
    }

    function setOrderBy($orderby){
    	$this->orderby = $orderby;
    }

    function setGroupBy($groupby){
    	$this->groupBy = $groupby;
    }
    
    function setCountRows($count){
    	$this->countrows = $count;
    }

    function setCountRowsColumn($column){
    	$this->count_column = $column;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    private function getTablePart() {
//    	$query_part = "";
//    	$table = $this->table;
//    	switch($this->type) {
//    		case $this->QUERY_TYPE_SELECT:
//
//    			// Splitten voor als je een alias voor een table gebruikt
//    			$table = split(' ', $table); 
//
//    			if(count($table) == 2){
//		    		$query_part = "FROM ".$table[0]." ". $table[1];
//		    	} else if(count($table == 1)){
//		    		$query_part = "FROM ".$table[0]." ";
//		    	} else{
//		    		throw new IllegalStateException(
//		    			"Can't read table info (1) (too many spaces?):".$table);
//		    	}
//		    	break;
//		    	
//		    case $this->QUERY_TYPE_DELETE:		    			
//    			$query_part = ' FROM '.$table." ";
//    			break;
//    			
//    		case $this->QUERY_TYPE_UPDATE:
//    			$query_part = ' UPDATE '.$table." SET ";
//    			break;
//    		case $this->QUERY_TYPE_INSERT:
//    			$query_part = ' INSERT INTO '.$table." ";
//    			break;
//    		default:
//    			die("Can't read table info (2) (too many spaces?):".$table);
//    	}
//    	return $query_part;	
    }

    private function getPreparedQuery(){
    	$query = "";
    	$query .= $this->type;
    	switch($this->type) {
    		
    		case $this->QUERY_TYPE_SELECT:
    			return $this->prepareSelect();
    		
    		case $this->QUERY_TYPE_INSERT:
    			return $this->prepareInsert();
    			
    		case $this->QUERY_TYPE_UPDATE:
    			return $this->prepareUpdate();
    		
    		case $this->QUERY_TYPE_DELETE:
    			return $this->prepareDelete();
    			
    		default: 
    			throw new IllegalStateException("Query type not implemented: " . $this->type);
    	}
    }
    
 	private function prepareSelect() {
    	Utils::assertTrue("Query is niet van het type SELECT", 
    		$this->type == $this->QUERY_TYPE_SELECT);
    		
    	$this->psQuery = "SELECT " . $this->fields . " " . $this->table;
    	    	
    	$this->prepareFilter();
    	
    	if($this->groupBy) {
    		$this->prepareGroupBy();
    	}
    	if($this->orderby) {
	    	$this->prepareOrderBy();
    	}
    	if($this->limit) {
	    	$this->prepareLimit();
    	}
    }
    
    private function prepareInsert() {
    	
    	$fields = "";
    	$values = "";
    	
    	$first = true;
    	
    	foreach($this->insertFields as $tuple) {
    		$key = $tuple[0];
    		$value = $tuple[1];
    		if(!$first) {
    			$values .= ", ";	
    			$fields .= ", ";	
    		}
    		$fields .= $key . " ";
    		$values .= "? ";
    		$this->addParameter($value);
    		$first = false;
    	}

    	$this->psQuery = "INSERT INTO " . $this->table . "(" . $fields . 
    		") VALUES(" . $values. ")";
    }
    
	private function prepareUpdate() {
    	Utils::assertTrue("Query is niet van het type UPDATE", 
    		$this->type == $this->QUERY_TYPE_UPDATE);
    		
    	$updateFields = array();
    	
    	Utils::assertTrue("Specify at least one field to update", 
    		count($this->updateFields) > 0);
    	
    	foreach($this->updateFields as $tuple) {
    		$field = $tuple[0];
    		$value = $tuple[1];
    		$updateFields[] = $field . " = ?";
    		$this->addParameter($value);
    	}
    	
    	// Maakt iets als "field_1 = ?, field_2 = ?"
    	$sFields = join(", ", $updateFields);
    	 
    	$this->psQuery = "UPDATE " . $this->table . " SET " . $sFields;
    	    	
    	$this->prepareFilter();
    }
    
	private function prepareDelete() {
    	Utils::assertTrue("Query is niet van het type DELETE", 
    		$this->type == $this->QUERY_TYPE_DELETE);
    		
    	$this->psQuery = "DELETE FROM " . $this->table;
    	    	
    	$this->prepareFilter();
    }
    
    private function prepareGroupBy() {
    	$this->psQuery .= " GROUP BY " . $this->groupBy;
    }

    private function prepareOrderBy() {
    	$this->psQuery .= " ORDER BY " . $this->orderby;
    }
    
    private function prepareLimit() {
    	$this->psQuery .= " LIMIT " . $this->limit;
    }

  
    
    /**
     * Voegt een parameter toe aan de PDO params, uiteindelijk komt dat terecht
     * bij $pdo->addParam($field, $param);
     *
     * @param Object Waarde van het veld wat vervangen is door een '?'
     */
    private function addParameter($param) {
    	//DebugUtils::debug("Parameter toevoegen: " . $param); 
    	$this->psParams[] = $param;
    }
    
    private function prepareFilter() {
    	$wherePart = $this->constraints->toString();
    	$params = $this->constraints->getValues();
    	
    	if(!empty($wherePart)) {
	    	$this->psQuery .= " WHERE " . $wherePart;
    	}
    	foreach ($params as $param) {
    		$this->addParameter($param);
    	}
    }
    
    function setQuery($query){
    	$this->query = $query;
    }

    function checkConfig() {
    	if(DB_USER == '') {
    		throw new Exception('config.php niet geconfigureerd');
    	}
    }
    
    function connect() {
    	if(self::$USE_PDO) {
    		if(!self::$pdo) {
		    	self::$pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".$this->db, DB_USER, DB_PASSWORD, 
		    		array(PDO::ATTR_PERSISTENT => true));
				self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    	}
	    	return true;	
    	} else {
	    	if(self::$dblink == null) {
	    		$this->checkConfig();
	    		self::$dblink = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
	    	}
        	return mysql_select_db($this->db) or die("Kan geen verbinding maken met database: '".$this->db."' > ".mysql_error());
    	}
        
    }
    
    function prepare(){
    	$this->connect();
    	
    	if(!empty($this->query)) {
    		$this->psQuery = $this->query; 
    	} else {
    		$this->getPreparedQuery();
    	}
    }

    private function setNoFoundRows($rows){
    	$this->rows = $rows;
    }
    
    /**
     * Als setCountRows(true) dan kun je met deze methode het aantal resultaten
     * ophalen dat er ZONDER een limit zou zijn. Handig voor pagination.
     */
    public function getNoFoundRows() {
    	return $this->rows;
    }

	public function execute($file = null, $line = null, $fetch_array = true){
		if(!$fetch_array) {
			throw new IllegalStateException("fetch_array moet niet meer gebruikt worden");
		}
		self::$query_counter++;
		$this->prepare();
			
		if(self::$USE_PDO) {
			if(DEBUG_QUERY && !IS_PRODUCTION) {
				DebugUtils::debug("[query] " . $this->getDebugQuery(), 0);
			}
			
			$statement = self::$pdo->prepare($this->psQuery);
	
			//DebugUtils::debug($this->psParams);
			
			for ($i = 0; $i < count($this->psParams); $i++) {
				// Kan hier geen foreach loop gebruiken want de value MOET via 
				// $this->psParams[] toegewezen worden op een of ander emanier
				$statement->bindParam($i + 1, $this->psParams[$i]);
			}
			$statement->execute(); //or $this->drop(mysql_error(), $this->psQuery, $file, $line);;
			if($this->type == $this->QUERY_TYPE_INSERT) {
				$systemid = self::$pdo->lastInsertId();
				return $systemid;
			}
				
			if($this->countrows) {
				$pq = new PreparedQuery(DEFAULT_DATABASE);
				$pq->setQuery("SELECT FOUND_ROWS() AS rows;");
				$cr = $pq->execute();
				$this->setNoFoundRows($cr[0]['rows']);
			}
			/* fetchen naar array, niet bij delete en update queries */
			if($fetch_array 
					&& $this->type != $this->QUERY_TYPE_DELETE
					&& $this->type != $this->QUERY_TYPE_UPDATE){
				return $statement->fetchAll();
			}
			else{
				//return $statement->fetchAll();
				return null;
			}
		} else {
			
			$query = $this->getDebugQuery();
			if(DEBUG_QUERY && !IS_PRODUCTION) {
				DebugUtils::debug("[query] " . $query, 0);
			}
			
			$rs = mysql_query($query) or $this->drop(mysql_error(), $query, $file, $line);
			if($this->type == $this->QUERY_TYPE_INSERT) {
				$systemid = mysql_insert_id();
				return $systemid;
			}
				
			if($this->countrows) {
				$pq = new PreparedQuery(DEFAULT_DATABASE);
				$pq->setQuery("SELECT FOUND_ROWS() AS rows;");
				$cr = $pq->execute();
				$this->setNoFoundRows($cr[0]['rows']);
			}
			/* fetchen naar array, niet bij delete en update queries */
			if($fetch_array 
					&& $this->type != $this->QUERY_TYPE_DELETE
					&& $this->type != $this->QUERY_TYPE_UPDATE){
				$lines = array();
				while($line = mysql_fetch_array($rs, MYSQL_ASSOC)) {
					$lines[] = $line; 
				}
				return $lines;
			}
			else{
				//return $statement->fetchAll();
				return $rs;
			}
		}
		
		return null;
	}
	
	public function getDebugQuery() {
		$split = explode("?", $this->psQuery);
		if(count($split) - 1 != count($this->psParams)) {
			throw new IllegalStateException("Wrong parameter count"); 
		}
		$query = "";
		foreach($this->psParams as $key=>$value) {
			if($value == null && $value != "0") {
				$query .= $split[$key] . "NULL";
			} else {
				$query .= $split[$key] . "'" . mysql_escape_string($value) . "'";
			}
		}
		
		$query .= $split[count($split) - 1];
		return $query;
	}

	function drop($exception, $query, $file, $line){
		if(strstr($exception, "Duplicate entry")) {
			if(preg_match('/^Duplicate entry \'(.*)\' for key (.*)$/i', $exception, $matches)) {
				$field = $matches[2];
				$value = $matches[1];
            	throw new DuplicateException($exception, $field, $value);
			}
			
		}
		Logger::error("Er is een database fout opgetreden:<br/><br/> :".@query.
			" ".$exception, $file, $line);		
		if(_DEBUG) {
			DebugUtils::debug("Er is een database fout opgetreden:<br/><br/> ".$exception);
			DebugUtils::debug("Query:<br/>".$query);
			DebugUtils::printException(new Exception($exception));
		}
//		global $smarty;
//		$smarty->assign('page', 'error');
//		$smarty->display('main.tpl');
//		exit();
//		throw new RuntimeException($exception);
	}
}


?>