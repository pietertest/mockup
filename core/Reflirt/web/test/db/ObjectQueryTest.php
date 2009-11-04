<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../config/config.php');
include_once(PHP_CLASS.'searchers/ACSearcher.class.php');
include_once PHP_CLASS.'entities/user/User.class.php';
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'entities/message/Message.class.php';
include_once(PHP_CLASS.'utils/Utils.class.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');

class ObjectQueryTest extends TestCase {

	function testSelect() {
		$subject = mktime();
		$message = $this->create(new Message);
		$message->put("message", "unittest");
		$message->put("subject", $subject);
		$message->save();
		
		$oq = ObjectQuery::buildACS(new Message(), $this->getUser());
		$oq->addConstraint(Constraint::eq("subject", $subject));
		$berichten = SearchObject::search($oq);
		$this->assertTrue("aantal resultaten != 1", count($berichten) == 1);
	}

	
}

$test = new ObjectQueryTest();
$test->run();

?>
