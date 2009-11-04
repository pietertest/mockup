<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../phpincl/init_smarty_config.php');
include_once(PHP_CLASS.'searchers/ACSearcher.class.php');
include_once PHP_CLASS.'entities/user/User.class.php';
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once(PHP_CLASS.'entities/message/MessageEntity.class.php');
include_once(PHP_CLASS.'entities/settings/SettingsFactory.class.php');
include_once(PHP_CLASS.'utils/Utils.class.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');
include_once(PHP_CLASS_TEST.'exceptions/ClassNotFoundExceptionTest.php');

class ColumnTest extends TestCase {

	function testUpdateColumn() {
		$m = $this->getUser();
		$date1 = $m->getString($m->getTable()->getLastUpdateColumn());
		$m->putCol("firstname", "Pieter2");
		$m->save();
		$date2 = $m->getString($m->getTable()->getLastUpdateColumn());
		$this->assertTrue("lastupdate niet geupdate", $date2 > $date1);
	}

	function testColumnType() {
		$f = array();
		$this->assertTrue("Column != Datetime", $this->getUser()->getTable()->getColumn("date_in")->isDateTime());
	}
	
	function testIsNullable() {
		$f = array();
		$this->assertTrue("Column == Nullable", !$this->getUser()->getTable()->getColumn("date_in")->isNullable());
		$this->assertTrue("Column != Nullable", $this->getUser()->getTable()->getColumn("zipcode")->isNullable());
	}
	
	/**
	 * Kijken of het inserten van een datum werkt bij het aanmaken van een
	 * nieuwe entity.
	 */
	//function testInsertNewEntity() {
//		$m = new MessageEntity();
//		$m->put("username", "test");
//		$m->put("firstname", "test");
//		$m->put("lastname", "test");
//		$m->put("email", "test");
//		$m->put("password", "test");
//		$m->save();
		//$m = $this->create(new MessageEntity());
		//Utils::assertTrue("Datum == 0000-00-00 00:00:00",
		//$m->getString($m->getTable()->getInsertDateColumn()) != "0000-00-00 00:00:00");
//	}
}

$test = new ColumnTest();
$test->run();

?>
