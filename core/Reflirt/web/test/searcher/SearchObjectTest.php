<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../config/config.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/message/Message.class.php';


class SearchObjectTest extends TestCase {

	function testACSearch() {
		$subject = microtime();

		$message = $this->create(new Message());
		$message->put("subject", $subject);
		$message->put("message", "unititest");
		$message->save();

		$oq = ObjectQuery::buildACS(new Message(), $this->getUser());
		$oq->addConstraint(Constraint::eq("subject", $subject));
		$berichten = SearchObject::search($oq);
		Utils::assertTrue("aantal resultaten != 1", count($berichten) == 1);
	}
}

$test = new SearchObjectTest();
$test->run();

?>
