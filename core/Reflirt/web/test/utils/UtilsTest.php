<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../config/config.php');
include_once(PHP_CLASS.'utils/Utils.class.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');

class UtilsTest extends TestCase {

	public function testIsInt() {
		
		$ds = new DataSource();
		$ds->put("sex", 0);
		
		$this->assertTrue("Is geen int 1", Utils::isInt("0"));
		$this->assertTrue("Is geen int 2", Utils::isInt(0));
		$this->assertTrue("Is geen int 3", Utils::isInt("1"));
		$this->assertTrue("Is geen int 4", Utils::isInt(1));
		$this->assertTrue("Is geen int 5", !Utils::isInt("d0"));
		
	}
}

$test = new UtilsTest();
$test->run();

?>
