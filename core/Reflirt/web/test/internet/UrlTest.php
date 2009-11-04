<?php
ClassLoader::load("internet.Url");

class UrlTest extends TestCase {

	function testGetFirstPart() {
		$url = new Url("http://www.reflirt.nl/?name=pieter&action=login&result=good");
		$url->addParameter("blaat", "neen");
		$firstpart = $url->getFirstPart();
		$querypart = $url->getQueryPart();

		$this->assertTrue("Firstpart klopt niet! ", $firstpart == "http://www.reflirt.nl/");
		$this->assertTrue("Querypart klopt niet! ", $querypart == "name=pieter&action=login&result=good&blaat=neen");
		$this->assertTrue("toString() klopt niet! ", ($firstpart."?".$querypart) == $url->toString());


	}
}

$test = new UrlTest();
$test->run();

?>
