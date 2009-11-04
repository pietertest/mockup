<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../config/config.php');
include_once(PHP_CLASS.'searchers/ACSearcher.class.php');
include_once(PHP_CLASS.'entities/user/User.class.php');
include_once(PHP_CLASS.'entities/user/UserFactory.class.php');
include_once(PHP_CLASS.'entities/message/Message.class.php');
include_once(PHP_CLASS.'entities/settings/SettingsFactory.class.php');
include_once(PHP_CLASS.'utils/Utils.class.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');

/**
 * Testen of message versturen goed gaat etc
 */
class MessageTest extends TestCase {


	function testLoadMessage() {
		$user = UserFactory::getUserByLogin("--Anitaatje--");
		$message = new Message();
		$message->setKey(92933980);
		$message->load();

		$this->assertTrue("Timestamp is anders!", $message->get('TIMESTAMP') == "2007-05-24 00:24:31");
	}
}

$test = new MessageTest();
$test->run();

?>
