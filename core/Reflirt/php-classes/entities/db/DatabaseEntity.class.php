<?php
include_once PHP_CLASS.'entities/db/Entity.class.php';
include_once PHP_CLASS.'entities/db/DBUtils.class.php';

abstract class DatabaseEntity extends Entity implements PersistentEntity {

	function __construct($database, $table = null) {
		if ($table == null) {
			$table = $database;
			$database = DEFAULT_DATABASE;
		}
		parent::__construct(new DatabaseTableModel($database, $table));
	}
	
	// @Override
	public function getFormat($key) {
		$format = parent::getFormat($key);
		if($format == null) {
			$format = $this->getDatabaseFormat($key);
		}
		return $format;
	}
	private function getDatabaseFormat($col) {
		$col = $this->getTable()->getColumn($col);
		if($col != null) {
			$format = DBUtils::getFormatter($col);
			return $format;
		}
		return null;
	}
}
?>