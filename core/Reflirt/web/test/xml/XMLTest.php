<?php

class XMLTest extends TestCase {
	
	function testXML() {
		$doc = new DOMDocument();
		$doc->load("xml/file.xml");
		$tables = $doc->getElementsByTagName("table");
	}

}

$test = new XMLTest();
$test->run();

?>
