<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../phpincl/init_smarty_config.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');
include_once PHP_CLASS.'utils/StopWatch.class.php';
include_once PHP_CLASS.'html/HTML.class.php';


class HtmlTest extends TestCase {

	function testAttributesAndInnerHTMl() {
		$a = new Html("a");
		$a->attr("href", "http://www.google.com");
		$a->innerHTML("google");
		
		$expected = "<a href=\"http://www.google.com\" >google</a>";
		$this->assertEquals("link is anders", $a->toString(), $expected);
	}
}
$test = new HtmlTest();
$test->run();

?>
