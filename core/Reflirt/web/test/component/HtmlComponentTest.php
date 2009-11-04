<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../config/config.php');
include_once(PHP_CLASS.'html/component/Document.class.php');
include_once(PHP_CLASS.'html/component/Head.class.php');
include_once(PHP_CLASS.'html/component/Body.class.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');

class HtmlComponentTest extends TestCase {
	
	function testSaveAndDelete() {
		$doc = new Document();
		
		$head = new Head();
		$head->addJavascriptInclude("/js/script1.js");
		$head->addJavascriptInclude("/js/script2.js");
		
		$head->addCssInclude("/css/style1.css");
		
		$head->addMetaData("description", "Geilheid!");
		
		
		
		$doc->add($head);
		$body = new Body();
		$doc->add($body);
		
		$head = $body->getDocument();
		
		$head = $doc->getHead();
		$head->setTitle("blaat");
		
		$head->addMetaData("description", "Op zoek naar elkaar! In de buurt, werk, uitgaan, interesses en meer");
		$head->addMetaData("keywords", "emeet, e-meet, terug, vinden, buurt, werk, flirt, uitgaan, bioscoop");
		$head->addMetaData("classification", "emeet, e-meet, terug, vinden, buurt, werk, flirt, uitgaan, bioscoop");
		$head->addMetaData("copyright", "Emeet.nl");
		
		$head->addCssInclude("/css/reset.css");
		$head->addCssInclude("/css/components.css");
		$head->addCssInclude("/css/style.css");
		$head->addCssInclude("/css/jquery.autocomplete.css");
		$head->addCssInclude("/css/jquery.tabs.css");
		$head->addCssInclude("/css/jquery.autocomplete.css");
		$head->addCssInclude("/css/jquery.datepicker.css");
		$head->addCssInclude("/css/jquery.highlight.css");
		
		$head->addJavascriptInclude("/javascript/all.js");
		$head->addJavascriptInclude("/javascript/components/Autocomplete.js");
		$head->addJavascriptInclude("/javascript/components/jquery.tabcomplete.js");
		//$head->addJavascriptInclude("/javascript/main.js.php");
		$head->addJavascriptInclude("/javascript/startup.js");
		$head->addJavascriptInclude("/javascript/onload.js");
		$head->addJavascriptInclude("/javascript/core.js");
		
		$body = $doc->getBody();
		$body->setOnload("init()");
		$body->setOnUnload("bye()");
		
		
		
		
		$doc->writeHtml();
	}

}

$test = new HtmlComponentTest();
$test->run();

?>
