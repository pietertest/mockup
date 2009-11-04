<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../phpincl/init_smarty_config.php');
include_once(PHP_CLASS.'searchers/ACSearcher.class.php');
include_once(PHP_CLASS.'entities/user/User.class.php');
include_once(PHP_CLASS.'entities/user/User.class.php');
include_once(PHP_CLASS.'entities/user/UserFactory.class.php');
include_once(PHP_CLASS.'entities/mylocation/MyLocation.class.php');
include_once(PHP_CLASS.'utils/Utils.class.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');

/**
 */
class AutocompletionTest extends TestCase {
	
	public function testAutocompletionForSeveralCategories() {
		$tests = array(
			array("test"=>"Disco", "cat"=>3, "q"=>"pa")
		);
		
		foreach ($tests as $key=>$parameters) {
			$ds = new DataSource();
			$ds->putAll($parameters);
			$type = MyLocationFactory::newInstance($parameters["cat"]);
			$info = $type->getAutocompletionInfo($ds);
			DebugUtils::debug($info);
			$this->assertNotEmpty("Geen data voor ".$parameters["test"]);
		}		
	}
	
}

$test = new AutocompletionTest();
$test->run();
?>