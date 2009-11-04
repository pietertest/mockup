<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../phpincl/init_smarty_config.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/message/Message.class.php';


class QueryConstraintTest extends TestCase {

	function testConstraintList() {
		$systemUser = UserFactory::getSystemUser();
		$oq = ObjectQuery::buildACS(new Message(), $systemUser);
		
		$constraintList = new QueryConstraintList();
		$constraintList->add(Constraint::eq("category", 1));
		$constraintList->add(Constraint::eq("cityid", 2));
		$constraintList->add(Constraint::eq("poepie", 3));
		
		$this->assertEquals("Opgebouwde constraint komt niet overeen", 
			"category = '1' AND cityid = '2' AND poepie = '3'",
			$constraintList->toString());
		
	}
}

$test = new QueryConstraintTest();
$test->run();

?>