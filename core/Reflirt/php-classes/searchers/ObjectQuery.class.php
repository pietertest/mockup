<?php
include_once PHP_CLASS.'searchers/ACSearcher.class.php';

class ObjectQuery{

/** TODO: implementeren dat ie alleen op de juiste owner zoekt */
	private $user = null;
	private $clazz =  null;
	private $searcher = null; // Searcher object
	private $limit = null;
	private $constraints = array();
	private $parameters;
	private $groupBy = null;
	private $orderBy = null;
	private $countRows = false;
	private $noOfResults;
	private $joins = array();
	
	private static $pq = null;

	function __construct(User $user = null) {
		$this->user = $user;
		$this->parameters = new DataSource();
	}
	
	function setGroupBy($groupBy) {
		$this->groupBy = $groupBy;
	}
	
	public function setCountRows($count) {
		$this->countRows = $count;
	}
	
	public function getCountRows() {
		return $this->noOfResults;
	}
	
	static function build(Entity $clazz, User $user = null, $limit = null) {
		$os = new ObjectQuery($user);
		$os->setTarget($clazz);
		if($limit) {
			$os->setLimit($limit);
		}
		return $os;
	}
	
	public function getUser() {
		return $this->user;
	}
	
	public function addJoin(Join $join) {
		$this->joins []= $join; 
	}

	static function buildACS(Entity $clazz, User $user, $limit = null) {
			$searcher = new ACSearcher($clazz->getTable());
			$os = ObjectQuery::build($clazz, $user, $limit);
			$os->setSearcher($searcher);
			return $os;
	}
	
	static function buildDS(Entity $clazz, User $user, $limit = null) {
			$searcher = $clazz->getDefaultSearcher();
			$os = ObjectQuery::build($clazz, $user, $limit);
			$os->setSearcher($searcher);
			return $os;
	}

	function setLimit($limit, $end = null) {
		$this->limit = intval($limit);
		if($end != null ) {
			$this->limit .= "," . intval($end);
		}
	}

	function addConstraint(QueryConstraint $constraint) {
		$this->constraints[] = $constraint;
	}
	
	function addIf(QueryConstraint $constraint, $value) {
		if($this->ifCondition($value)) {
			$this->addConstraint($constraint);
		}
	}

	function setTarget($clazz) {
		$this->clazz = $clazz;
	}
	
	function getTarget() {
		return $this->clazz;
	}

	/**
	 * Parameters meegeven. Deze worden niet in de ACSearcher gebruikt, wel in
	 * de andere.
	 */
	function addParameter($key, $value) {
		$this->parameters->put($key, $value);
	}
	
	function setOrderBy($orderby) {
		$this->orderBy = $orderby;
	}
	
	function addIfParameter($key, $value) {
		if($this->ifCondition($value)) {
			$this->parameters->put($key, $value);
		}
	}
	
	function addParameters($params) {
		$this->parameters->putAll($params);
	}

	function setSearcher(SearcherInterface $searcher) {
		Utils::assertTrue("Searcher already set!", $this->searcher == null);
		$this->searcher = $searcher;
	}

	/**
	 * Geef een list met entiteiten terug
	 */
	function execute() {
		Utils::assertNotNull("No searcher specified", $this->searcher);
		$dbName = $this->clazz->getTable()->getDatabaseName();
		self::$pq = new PreparedQuery($dbName);

		$tables = $this->searcher->getTables($this->parameters);
		
		
		//$select = $this->searcher->getFields($this->parameters);
		
		
		foreach ($this->joins as $join) {
			$tables .= " LEFT JOIN ".$join->getTable()." ON ".$join->getColumn().
			" = ".$join->getTable().".systemid ";
		}
		self::$pq->setSelect($tables);
		$fields = $this->searcher->getFields($this->parameters);
		self::$pq->setSelectFields($fields);

		$list = new QueryConstraintList();
		$c = $this->searcher->getFilter($this->parameters);
		Utils::assertNotNull("no constraints == null! (getFilter == null)", $c);
		if($c != null) {
			$list->add($c);
		}
		foreach ($this->constraints as $constraint) {
			$list->add($constraint);
		}

		$ownerColumn = $this->clazz->getTable()->getOwnerColumn();
		if($ownerColumn == null) {
			Utils::assertTrue("No authorisation to load data on a table without usercolumn with this user:".$this->getUser()->getKey(), $this->user instanceof SystemUser);
			if(defined(DEBUG_QUERY)) {
				DebugUtils::debug("[query zonder usercolumn]");
			}
		} else {
			Utils::assertNotEmpty("Geen user geladen", $this->user);
			Utils::assertNotEmpty("Geen user geladen", $this->user->getKey());
//			if(!UserFactory::isSystemUser($this->user)) {
			if(!$this->user instanceof SystemUser) {
				$list->addKey($this->clazz->getTable()->getTableName().".".$ownerColumn, $this->user->getKey());
			}
		}
		
		self::$pq->addConstraint($list);		 
		// Query via PreparedQuery opbouwen
		// Zie PreparedQuery::$COUNT_ROWS_SQL
		if($this->countRows) {
			//$query .= PreparedQuery::$COUNT_ROWS_SQL." ";
			self::$pq->setSelectFields(PreparedQuery::$COUNT_ROWS_SQL . " " . $fields);
			self::$pq->setCountRows(true);
		}

		$groupby = ""; 
		$searcherGroupBy = " ".$this->searcher->getGroupBy($this->parameters);;
		if($this->ifCondition($searcherGroupBy)) {
			$groupby = " GROUP BY ".$searcherGroupBy;
		}
		if($this->ifCondition($this->groupBy)) {
			if(empty($groupby)) {
				$groupby .= " GROUP BY ".$this->groupBy;
			} else {
				$groupby = $this->groupBy;
			}
		}
		if(!empty($groupby)) {
			self::$pq->setGroupBy($searcherGroupBy);
		}
//		$query .= $groupby;
		
		$orderby = " ".$this->searcher->getOrderBy($this->parameters);;
		if($this->ifCondition($orderby)) {
			self::$pq->setOrderBy($orderby);
//			$orderby = " ORDER BY ".$orderby;
		}
		if($this->ifCondition($this->orderBy)) {
			self::$pq->setOrderBy($this->orderBy);
//			$orderby = " ORDER BY ".$this->orderBy;
		}

		if($this->ifCondition($this->limit)) {
			self::$pq->setLimit($this->limit);
			//$query .= " LIMIT ".$this->limit;
		}
		$rs = self::$pq->execute(__FILE__, __LINE__);
		
		if($this->countRows) {
			$this->noOfResults = self::$pq->getNoFoundRows();
		}
		
		return $rs;
	}

	 private function ifCondition($s) {
    	if(Utils::isEmpty(trim($s))) {
    		return false;
    	}
    	return true;
    }
    
    public function getLastQuery() {
    	return self::$pq->getDebugQuery();
    }
}
?>