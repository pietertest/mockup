<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../phpincl/init_smarty_config.php');
include_once PHP_CLASS.'entities/user/User';
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/oproep/DiscoOproep.class.php';

include_once(PHP_CLASS_TEST.'TestCase.class.php');
include_once(PHP_CLASS_TEST.'exceptions/ClassNotFoundExceptionTest.php');

class QueryTest extends TestCase {

	function test100Queries() {
		$systemid = 17; 
		$user = UserFactory::getUserByLogin("pieter");
		$count = 100;
		while($count-- > 0) {
			$ent = EntityFactory::loadEntity(new Zoekopdracht(), $user, $systemid);
		}
	}
	
	function test100Files() {
		$count = 100;
		$buffer = "";
		while($count-- > 0) {
			$buffer .= readfile("c:\\Copy ($count) of database.xml");
		}
		DebugUtils::debug($buffer);
	}

}

$test = new QueryTest();
$test->run();

?>
