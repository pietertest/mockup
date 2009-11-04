<?php
include_once(PHP_CLASS_TEST.'TestCase.class.php');
include_once PHP_CLASS.'utils/StopWatch.class.php';
include_once PHP_CLASS.'cache/Cache.class.php';


class CacheTest extends TestCase {

	function _setUp() { // uitgezet door _
		
		$s1 = new StopWatch("Creating Message objects");
		$s1->start();
		for($i = 0;$i < 50; $i++) {
			$m = new MessageEntity();
			$m->setUser($this->getUser());
			$m->put("subject", "benchmark test");
			$m->save();
		}
		DebugUtils::debug($s1->end());
	}
	
	function testSpeedsWithManyResults() {
		$query = "SELECT * FROM bericht";
		$db = new PreparedQuery("reflirt_nieuw");
		$db->setQuery($query);

		$s1 = new StopWatch("Whithout cache");
		$s1->start();
		for ($i = 0; $i < 10; $i++) {
			$rs = $db->execute();
		}
		DebugUtils::debug("Selected ".count($rs)." rows");
		DebugUtils::debug($s1->end());
		
		$s2 = new StopWatch("With cache");
		$s2->start();
		for ($i = 0; $i < 10; $i++) {
			if(!$rs=Cache::get($query)) {
				$rs = $db->execute();
				DebugUtils::debug("storing");
				Cache::store($query, $rs);
			}
		}
		DebugUtils::debug($s2->end());
	}
	
	function testSpeedsFewResults() {
		$query = "SELECT * FROM bericht WHERE systemid=21";
		$db = new PreparedQuery("reflirt_nieuw");
		$db->setQuery($query);
		
		$s1 = new StopWatch("Without cache");
		$s1->start();
		for ($i = 0; $i < 10; $i++) {
			$rs = $db->execute();
		}
		DebugUtils::debug($s1->end());
		
		$s2 = new StopWatch("With cache");
		$s2->start();
		for ($i = 0; $i < 10; $i++) {
			if(!$rs=Cache::get($query)) {
				$rs = $db->execute();
				Cache::store($query, $rs);
			}
		}
		DebugUtils::debug($s2->end());
	}
	
}
$test = new CacheTest();
$test->run();

?>
