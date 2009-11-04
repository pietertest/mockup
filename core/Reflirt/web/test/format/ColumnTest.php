<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../phpincl/init_smarty_config.php');
include_once(PHP_CLASS.'searchers/ACSearcher.class.php');
include_once PHP_CLASS.'entities/user/User.class.php';
include_once(PHP_CLASS.'entities/user/UserFactory.class.php');
include_once(PHP_CLASS.'entities/message/MessageEntity.class.php');
include_once(PHP_CLASS.'entities/settings/SettingsFactory.class.php');
include_once(PHP_CLASS.'utils/Utils.class.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');
include_once(PHP_CLASS_TEST.'exceptions/ClassNotFoundExceptionTest.php');

class FormatTest extends TestCase {


	function testDateFormat() {
		$m = $this->getUser();
		$m->put("date_in", "19-10-1981");
		$m->save();
		$this->assertTrue("Wrong dateformat", 
			$m->getString("date_in") == "1981-10-19 00:00:00");
		//$m->getString();
		
	}

	
}

$test = new FormatTest();
$test->run();

?>
