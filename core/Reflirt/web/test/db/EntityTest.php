<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../config/config.php');
include_once(PHP_CLASS.'searchers/ACSearcher.class.php');
include_once(PHP_CLASS.'entities/user/User.class.php');
include_once(PHP_CLASS.'entities/user/UserFactory.class.php');
include_once(PHP_CLASS.'entities/message/Message.class.php');
include_once(PHP_CLASS.'entities/db/EntityFactory.class.php');
include_once(PHP_CLASS.'entities/settings/SettingsFactory.class.php');
include_once(PHP_CLASS.'utils/Utils.class.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');

class EntityTest extends TestCase {
	
	function testSaveAndDelete() {
		$message= $this->create(new Message);
		$message->put("message", "berigggieee");
		$message->save();
		
		$key = $message->getKey();
		
		$message->delete();
		
		$newMessage = EntityFactory::loadEntity(new Message, UserFactory::getSystemUser(), 
			$key);
			
		$this->assertTrue("Entity bestaat nog", $newMessage == null);
		
	}


	function _testUpdateByKey() {
		$m = new MessageEntity();
		$m->putCol("ONDERWERP", "bllaat onderwerp");
		$m->putCol("ONTVANGER_NICK", "pieter");
		$m->putCol("VERZENDER_NICK", "pieter");
		$m->setKey(431);
		$m->replace();
		$this->assertTrue("systemid != 431", $m->getKey() == 431);
	}

	function _testRemoveKey() {
		$m = new MessageEntity();
		$m->put("ONDERWERP", "bllaat onderwerp");
		$m->put("ONTVANGER_NICK", "pieter");
		$m->put("VERZENDER_NICK", "pieter");
		$m->setKey(431);
		$m->removeKey();
		$m->save();
		$this->assertTrue("systemid != 431", $m->getKey() != 431);
	}

	function _testReplace() {
		$user = UserFactory::getUserbyLogin('pieter');

		$en = new SettingsEntity('settings');
		$en->setUser($user);
		$en->put('property', 'tesdt');
		$en->put('value', 'testjee');
		$en->replace();
	}

}

$test = new EntityTest();
$test->run();

?>
