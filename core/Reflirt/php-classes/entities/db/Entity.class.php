<?php
include_once PHP_CLASS.'searchers/Searcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/db/DataModel.class.php';
include_once PHP_CLASS.'core/Map.class.php';
include_once PHP_CLASS.'core/DataSource.class.php';
include_once PHP_CLASS.'entities/db/AbstractDatabaseTableModel.class.php';
include_once PHP_CLASS.'entities/db/DatabaseTableModel.class.php';
include_once PHP_CLASS.'format/Format.class.php';
include_once PHP_CLASS.'entities/db/Table.class.php';
include_once PHP_CLASS.'utils/DateUtils.class.php';
include_once PHP_CLASS.'html/HTMLRenderer.class.php';
include_once PHP_CLASS.'html/DefaultHTMLRenderer.class.php';
include_once PHP_CLASS.'html/hasHtmlRenderer.class.php';
include_once PHP_CLASS.'entities/db/PersistentEntity.class.php';

class Entity extends AbstractDatabaseTableModel implements hasHtmlRenderer {

	public $user;
	public $otherUser;
	
	private $table = null; // Table object
	private $systemid = -1;
	private $EMPTY_SYSTEMID = -1;

	private $database = null;
	private $result = null;
	private $systemidField = null; // Kolom naam voor systemid
	private $useridField = null; // Kolom naam voor userid
	private $loadSearcher = null;
	private $htmlRenderer = null;
	
	protected $skipImportValidation = false;

	public $ownerColumn = null;
	private $wasNew = true;

	public function __construct(DataModel $model) {
		$this->datamodel = $model;
		$this->loadSearcher = new LoadSearcher($this);
	}
	
	public function setSkipImportValidation($bool) {
		$this->skipImportValidation = $bool;
	}
	
	protected function getMasks() {
		return array();
	}
	
	public function getMask($field) {
		$mask = $this->getMasks();
		if (isset($mask[$field])) {
			return $mask[$field];
		}
		return null;
	}
	
	public function getUrl() {
		//return VisitableUtils::getUrl($this);
		throw new IllegalStateException("Not yet implemented!");
	}
	
	/**
	 * Voor i18n met Smarty
	 */
	function L($message, $params=null){ 
		global $smarty;
		//smarty_gettext_strarg(array("naam"=>"Jantine"), $message, $smarty);
		return smarty_block_t($params, $message, $smarty);
	}
	
	public function getDefaultSearcher() {
		//throw new UnsupportedMethodExeption("This method has not been overridden.");
		return ObjectQuery::buildACS($this, $this->getUser());
	}

	public function setKey($systemid){
		$keyColumn = $this->getTable()->getKeyColumn();
		Utils::assertTrue("No keyColumn specified in database.xml", $keyColumn);
		$this->datamodel->put($keyColumn, $systemid);
		$this->systemid = $systemid;
	}

	public function getKey(){
		//return $this->datamodel->get($this->getTable()->getKeyColumn());
		return $this->systemid;
	}
	
	public function getHTML($what) {
		if ($this->htmlRenderer == null) {
			$this->htmlRenderer = $this->getHtmlRenderer();
		}
		return $this->htmlRenderer->get($what);
	}
	
	public function getHtmlRenderer() {
		 return new DefaultHTMLRenderer($this); 
	}
	
	public function setFormat($field, $format) {
    	$this->formats[$field] = new Format($format);
    }
    
	/**
	 * @todo wordt niet meer gebruit, weghalen
	 */
	public function getKeyColumn() {
		Utils::assertNotNull("table == null (no database and/or table info specified)",
			$this->getTable());
		return $this->getTable()->getKeyColumn();
	}

	public function removeKey() {
		$this->datamodel->remove($this->getTable()->getKeyColumn());
		$this->systemid = $this->EMPTY_SYSTEMID;
	}

    public function putCol($col, $value) {
    	Utils::assertTrue("No column '".$col."' in table: ".$this->getTable()->getTableName().".".
    		$this->getTable()->getDatabaseName(), $this->getTable()->hasColumn($col));
    	$this->put($col, $value);
    }
    
    function getFormat($key) {
    	if(!empty($this->formats[$key])) {
    		return $this->formats[$key];
    	}
    	return null;
    } 
    
    function put($key, $value) {
    	$format = $this->getFormat($key);
		if($format != null) {
			$format->setValue($value);
			$value = $format->parse();
		}
    	$this->datamodel->put($key, $value);
    }
    
    public function getUser() {
    	if ($this->user instanceof SystemUser) {
			$ownerColumn = $this->getTable()->getOwnerColumn();
			$systemid = $this->get($ownerColumn);
			$user = UserFactory::getUserBySystemid($systemid);
			$this->setUser($user);
		}
		return $this->user;
    }
    
	public function setUser($user) {
    	$this->user = $user;
    }
    
	public function getOtherUser() {
		$otherUserColumn = $this->getTable()->getOtherUserColumn();
		$systemid = $this->get($otherUserColumn);
		$user = UserFactory::getUserBySystemid($systemid);
		$this->setOtherUser($user);
	}
	
	public function setOtherUser($user) {
		$this->otherUser = $user;
    	$otherUserColumn = $this->datamodel->getTable()->getOtherUserColumn();
    	if ($user == null) {
			$this->datamodel->put($otherUserColumn, null);
    	} else {
			$this->datamodel->put($otherUserColumn, $user->getKey());
    	}
	}
    
    /**
     * For example to get spots from a user in User.class you would type: 
     * 	return $this->getObjectByForeignKe(new MySpots, "user")   
     */
    function getObjectsByForeignKey(Entity $type, $foreignColumn, $extraConstraints = null, $limitStart = null, $limitEnd = null) {
    	if($this->isNew()) {
    		throw new IllegalStateException("Can't get foreign objects of a new instance'");
    	}
    	$oq = ObjectQuery::buildACS(new $type(), $this->getUser());
    	$oq->addConstraint(Constraint::eq($foreignColumn, $this->getKey()));
    	if ($extraConstraints != null) {
    		$oq->addConstraint($extraConstraints);
    	}
    	if($limitStart != null) {
	    	$oq->setLimit($limitStart);
	    	if($limitEnd != null) {
	    		$oq->setLimit($limitStart, $limitEnd);
	    	}
    	}
		return SearchObject::search($oq);
    }
    
    function loadEntityByForeignKey(Entity $type, $column) {
    	$systemid = $this->getInt($column, -1);
    	if ($systemid == -1) {
			return null;    		
    	}
    	$systemUser = UserFactory::getSystemUser();
    	return EntityFactory::loadEntity(new $type(), $systemUser, $systemid);
    	
    }

    /**
	 * Check of er met de huidige data in de entity een unieke index
	 * te vinden is. Hiervoor worden de indexes opgehaald en gekeken of
	 * er tenminste een van de indexen voldoende data bevat om een
	 * unieke index te vinden.
     */
    private function canLoad() {
    	
    	if($this->getTable()->hasOwnerColumn() && $this->getUser() == null) {
    		throw new RuntimeException("user == null");
    	}
    	$list = $this->getTable()->getIndexes();
    	$bCanLoad = false;
    	foreach($list as $index) {
    		$indexCols = $index->getIndexColumnNames();

    		$bAllColsFilled = false;
    		foreach($indexCols as $col) {
    			if($this->datamodel->get($col) != null){
    				$bAllColsFilled = true;
    			} else {
    				$bAllColsFilled = false;
    				break;
    			}
    		}
    		if($bAllColsFilled) {
    			$bCanLoad = true;
    			break;
    		}
    	}
    	return $bCanLoad;
    }

    function getTableName() {
    	return $this->getTable()->getTableName();
    }

    public function getDatabaseName() {
    	return $this->getTable()->getDatabaseName();
    }
    
    public function getTable() {
    	return $this->datamodel->getTable();
    }

	private function checkKeyColumns() {
		Utils::assertTrue("Niet genoeg data om een " .
				"unieke index te vinden bij het laden van entiteit '".
				$this->getTable()->getDatabaseName().".".$this->getTable()->getTableName()."'", $this->canLoad());
	}

	private function loadBySystemid() {
		$db = new PreparedQuery($this->getTable()->getDatabaseName());
		$db->setSelect("FROM " . $this->getTable()->getTableName());
		$db->setSelectFields("*");
		$foreignkeys = $this->getTable()->getForeignKeys();		
		if(count($foreignkeys) > 0) {
			foreach($foreignkeys as $column=>$table) {
				$db->addJoin($table, $column);
			}
		}
		$db->addFilter($this->getTableName().".".$this->getTable()->getKeyColumn(), $this->getKey());
		$ownerColumn = $this->getTable()->getOwnerColumn();
		if($ownerColumn != null) {
			$db->addFilter($ownerColumn, $this->getUser()->getKey());
		}
		$rs = $db->execute();
		if($rs[0] == null) {
			return null;
		}
		$this->datamodel->putAll($rs[0]);
	}

	/**
	 * load - Een Entity laden.
	 * Bij een load moet nog een user opgegevens worden. Of niet? Namelijk bij niet ingelogde gebruikers
	 * moet het niet nodig zijn, afhankelijk dus van wat er geladen wordt.
	 */
	public function load(){
		$oq = ObjectQuery::build($this, $this->getUser());
		$oq->addParameters($this->datamodel->getData());
		$oq->setSearcher($this->getLoadSearcher());
		$obj = SearchObject::select($oq);
		if($obj == null) {
			throw new RuntimeException("Not able to load entity with systemid:".$this->getKey().
			 "(".$this->getTable()->getDatabaseName().".".$this->getTable()->getTableName().") with userid: " .
			 $this->getUser()->getKey());
		}
		$this->datamodel->putAll($obj);
		if($this->datamodel->get($this->getTable()->getKeyColumn()) != null) {
			$this->systemid = $this->datamodel->get($this->getTable()->getKeyColumn());
		}
		//Utils::assertNotEmpty($rs[0], " Dit ding bestaat niet en kan niet geladen worden ");
	}
//	public function load(){
//		if($this->getKey() != -1) {
//			$this->loadBySystemid();
//			return;
//		}
//		$this->checkKeyColumns();
//		$user = $this->getUser();
//		$db = new PreparedQuery($this->getTable()->getDatabaseName());
//		$db->addField("*");
//		$db->setTable($this->getTable()->getTableName());
//		$foreignkeys = $this->getTable()->getForeignKeys();		
//		if(count($foreignkeys) > 0) {
//			//DebugUtils::debug($foreignkeys);
//			foreach($foreignkeys as $column=>$table) {
//				$db->addJoin($table, $column);
//			}
//		}
//		$db->addAllFields($this->datamodel->getAll());
//		$rs = $db->execute();
//		if($rs[0] == null) {
//			$this->systemid = $this->EMPTY_SYSTEMID;
//			//throw new EntityException("No record found!");
//			return null;
//		}
//		$this->datamodel->putAll($rs[0]);
//		if($this->datamodel->get($this->getTable()->getKeyColumn()) != null) {
//			$this->systemid = $this->datamodel->get($this->getTable()->getKeyColumn());
//		}
//		//Utils::assertNotEmpty($rs[0], " Dit ding bestaat niet en kan niet geladen worden ");
//	}

	/**
	 * Een entiteit laden aan de hand van gegevens. Dus niet alleen
	 * op Index-waardes zoeken.
	 *
	 */
	public function find(){
		$this->load();
		return $this->isNew();
	}

	private function clear(){
		$data = $this->datamodel->getAll();
		foreach ($data as $key) {
			$this->datamodel->remove($key);
		}
	}
	
	public function getFields() {
		return $this->datamodel->getAll();
	}

	public function isNew(){
		$key = $this->datamodel->get($this->getTable()->getKeyColumn());
		return empty($key);
	}
	
	public function wasNew() {
		return $this->wasNew;
	}

	/**
	 * Overriden wanneer nodig
	 */
	public function validate(){}

	/**
	 * @WebAction
	 */
	public function save() {
		$this->validate();
		$systemid = $this->getKey();
		if($systemid != -1) {
			$this->update();
			$this->wasNew = false;
		} else {
			$this->insert();
			$this->wasNew = true;
		}
		if ($this instanceof NotificationEntity ) {
			include_once PHP_CLASS.'entities/notification/NotificationExecutor.class.php';
			NotificationExecutor::handle($this);
		}
		$this->loadBySystemid();
		
	}
	
	public function setLoadSearcher(Searcher $searcher) {
		$this->loadSearcher = $searcher;
	}
	
	public function getLoadSearcher() {
		return $this->loadSearcher;
	}

	public function delete() {
		$this->checkKeyColumns();
		$user = $this->getUser();
		$pq = new PreparedQuery($this->getTable()->getDatabaseName());
		$table = $this->getTable();
		$pq->setDelete($table->getTableName());
		$pq->addFilter($table->getKeyColumn(), $this->getKey());
		if ($table->hasOwnerColumn()) {
			$pq->addFilter($table->getOwnerColumn(), $user->getKey());
		}
		Utils::assertTrue("Cannot delete entity when systemid == -1", $this->getKey() > $this->EMPTY_SYSTEMID);
//		if ($this->getKey()) {
//			$db->addFilter("systemid", $this->getKey());
//		} else {
//			$db->addAllFields($this->getFields());
//		}
		$pq->execute();
	}

	/**
	 * Hebben we hier wat aan? In het volgende geval misschien: je maakt een
	 * nieuwe entity aan, put wat waardes en dan maakt het je niet uit of
	 * je een nieuwe aanmaakt of misschien een bestaande overschrijft.
	 * Weghalen?
	 */
	public function replace(){
		if(count($this->datamodel->getAll()) < 1){
			return false;
		}
		if($this->find()){
			$this->insert();
			$this->load();
		}
		else {
			$this->update();
		}
	}
	
	private function insert(){
		if($this->getTable()->hasOwnerColumn()) {
			Utils::assertNotNull("user == null", $this->user);
		}
		
		$pq = new PreparedQuery($this->getTable()->getDatabaseName());
		$pq->setInsert($this->getTable()->getTableName());
		$data = $this->datamodel->getAll();
		foreach($data as $key=>$value){
			if(!$this->getTable()->hasColumn($key)){
				continue;
			}
			$col = $this->getTable()->getColumn($key);
			if($key == $this->getTable()->getKeyColumn() ||
				$key == $this->getTable()->getInsertDateColumn()){
				continue;
			}
			$colType = $col->getType();
			$fields = $this->getFields();
			$isSet = isset($fields[$key]);
			$value= $this->datamodel->get($key);
			
			if ($col->isNullable() && Utils::isEmpty($value)) {
//				echo " Leeg, skipping..";
				$pq->addInsertField($key, null);
			} elseif ($colType == ColumnType::$TINYINT && is_bool($value)) {
				$pq->addInsertField($key, $value ? 1 : 0 );
			} else {
				$pq->addInsertField($key, $value);
			}
		}
		$ownerColumn = $this->getTable()->getOwnerColumn();
		if(!Utils::isEmpty($ownerColumn)) {
			$pq->addInsertField($ownerColumn, $this->user->getKey());
		}
		$insertdateColumn = $this->getTable()->getInsertDateColumn();
		if(!Utils::isEmpty($insertdateColumn)) {
			$pq->addInsertField($insertdateColumn, DateUtils::now());
		}
		$lastupdateColumn = $this->getTable()->getLastUpdateColumn();
		if(!Utils::isEmpty($lastupdateColumn)) {
			$pq->addInsertField($lastupdateColumn, DateUtils::now());
		}
		$systemid = $pq->execute();
		$this->setKey($systemid);
	}

	private function putIfEmpty($col, $value) {
		$type = $col->getType();
		switch ($type) {
			case ColumnType::$INT: 
			case ColumnType::$DECIMAL:
				if (empty($value)) {
					return false; 
				}
				break;
			default:
				return true;
		}
    }

	private function update(){
		Utils::assertNotEmpty("systemidField == null", $this->getTable()->getKeyColumn());
		Utils::assertNotEmpty( "systemid == null", $this->getKey());
		$pq = new PreparedQuery($this->getTable()->getDatabaseName());
		$pq->setUpdate($this->getTable()->getTableName());
		$pq->addFilter($this->getTable()->getKeyColumn(), $this->getKey());
		$data = $this->datamodel->getAll();
		foreach($data as $key=>$value){
			if(!$this->getTable()->hasColumn($key)){
				continue;
			}
			$col = $this->getTable()->getColumn($key);
			if($key == $this->getTable()->getKeyColumn() ||
				$key == $this->getTable()->getInsertDateColumn()){
				continue;
			}
			$colType = $col->getType();
			$fields = $this->getFields();
			$isSet = isset($fields[$key]);
			$value= $this->datamodel->get($key);
			//if ($isSet && ($value == "" ||  $value == null)) {
			if ($isSet && (empty($value) &&  $value != 0)) {
				if ($colType ==  ColumnType::$INT || $colType ==  ColumnType::$DECIMAL || $colType ==  ColumnType::$DATETIME) {
					if ($col->isNullable()) {
						$value = null;
					}
				}
			}
			
			
			if ($col->isNullable() && (empty($value) &&  $value != 0)) {
//				echo " Leeg, skipping..";
				$pq->addUpdateField($key, null);
			}  elseif ($colType == ColumnType::$TINYINT) {
				if (is_bool($value)) {
					$pq->addUpdateField($key, $value ? 1 : 0 );
				} else {
					$pq->addUpdateField($key, $this->get($key));
				}
			} else {
				$pq->addUpdateField($key, $this->get($key));
				
			}
		}
		$ownerColumn = $this->getTable()->getOwnerColumn();
		if(!Utils::isEmpty($ownerColumn)) {
			$pq->addFilter($ownerColumn, $this->user->getKey());
		}
		$lastupdateColumn = $this->getTable()->getLastUpdateColumn();
		if(!Utils::isEmpty($lastupdateColumn)) {
			$pq->addUpdateField($lastupdateColumn, DateUtils::now());
		}
		$pq->execute();
	}

}

class LoadSearcher extends Searcher {
	protected $entity = null;
	
	public function __construct(Entity $ent) {
		$this->entity = $ent;
	}
	
	public function getFields(DataSource $ds) {
		$table = $this->entity->getTable()->getTableName();
		return "*, ".$table.".systemid AS systemid"; // ivm met joines
	}
	
	public function getTables(DataSource $ds) {
		$table = $this->entity->getTable()->getTableName();
		$select = " FROM $table ";
//		DebugUtils::debug("jaaaaaaaaa");
		return $select;
	}
	
	public function getFilter(DataSource $ds) {
		$table = $this->entity->getTable()->getTableName();
		$list = new QueryConstraintList();
		Utils::assertTrue("cannot load entity with systemid = -1", $this->entity->getKey() != -1);
		$list->addKey($table.".systemid", $this->entity->getKey());
		return $list;
	}
}
?>