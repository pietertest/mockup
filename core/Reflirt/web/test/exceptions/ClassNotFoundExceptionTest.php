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
class ClassNotFoundExceptionTest extends Exception {
	private $fqClassName;
	
	function __construct($fqClassName, $foreignClassLoader = false) {
		$this->fqClassName = $fqClassName;
        $caller  = debug_backtrace();
        $file   = ((false == $foreignClassLoader) ? ($caller[1]['file']) : ($caller[2]['file']));
        $line   = ((false == $foreignClassLoader) ? ($caller[1]['line']) : ($caller[2]['line']));
        $message = 'The class ' . $this->fqClassName . ' loaded in ' . $file . ' on line ' . $line . ' was not found.';
        parent::__construct($message);
	}
	
	function testLoadMessage() {
		$user = UserFactory::getUserByLogin("--Anitaatje--");
		$message = new MessageEntity();
		$message->setKey(92933980);
		$message->load();
		
		$this->assertTrue("Timestamp is anders!", $message->get('TIMESTAMP') == "2007-05-24 00:24:31");
	}
}


?>
