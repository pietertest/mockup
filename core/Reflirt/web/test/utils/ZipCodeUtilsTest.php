<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../phpincl/init_smarty_config.php');
include_once(PHP_CLASS.'utils/ZipCodeUtils.class.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');

class ZipCodeUtilsTest extends TestCase {

	public function testIsZipCode() {
		$this->assertTrue("Dit is een geldige postcode", 
			ZipCodeUtils::isZipCode("1056VS"));
		$this->assertTrue("Dit is geen geldige postcode", 
			!ZipCodeUtils::isZipCode("10526VS"));
		$this->assertTrue("Dit is geen geldige postcode", 
			!ZipCodeUtils::isZipCode("1056VVS"));
		$this->assertTrue("Dit is geen geldige postcode", 
			!ZipCodeUtils::isZipCode("1056S"));
	}
}

$test = new ZipCodeUtilsTest();
$test->run();

?>
