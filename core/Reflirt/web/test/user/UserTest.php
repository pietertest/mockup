<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../config/config.php');
include_once(PHP_CLASS.'searchers/ACSearcher.class.php');
include_once(PHP_CLASS.'entities/user/User.class.php');
include_once(PHP_CLASS.'entities/user/UserFactory.class.php');
include_once(PHP_CLASS.'entities/settings/SettingsFactory.class.php');
include_once(PHP_CLASS.'utils/Utils.class.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');

class UserTest extends TestCase {

	

	function testTest() {
		$user = new User();
			$user->putCol("username", "sdfsddfd");
			$user->putCol("email", "sdfsddfd");
			$user->putCol("firstname", "sdfsddfd");
			$tussenvoegsel = "sdfsddfd";
			$achternaam = "sdfsddfd";
			$user->save();
	}
	
	function _testDeleteUser() {
		$user = new User();
		$user->put("nick", "tes3t");
		$user->delete();
	}
	

	function testInsertUser() {
		//$nick = mktime();
		$nick = 'tes3t' . rand();
		$user = new User();
		$user->put("username", $nick);
		$user->put("password", "passwd");
		$user->put("email", "em" . rand());
		$user->save();

		return;
		$user2 = new User();
		$user2->setKey(1);
		$user2->load();
		$this->assertNotNull("Key == null",$user2->getKey());

		$user3 = new User();
		$user3->put("nick", "pieter");
		$user3->load();
	}

	function _testLoadUserByLogin() {
		$user = UserFactory::getUserByLogin("--Anitaatje--");
		$this->assertNotNull("User == null", $user);
	}
}

$test = new UserTest();
$test->run();

?>
