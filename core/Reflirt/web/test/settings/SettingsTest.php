<?php

class SettingsTest extends TestCase {
	private $user = null;

	function setUp() {
		$this->user = UserFactory::getUserbyLogin('PieterGenieter');
	}

	function _testDeleteSetting() {
		SettingsFactory::deleteSetting($this->user, "todon_foto");
	}

	function _testSaveSetting() {
		SettingsFactory::saveSetting($this->user, "todon_foto", 'blaat');
	}

	function _testGetSetting() {
		SettingsFactory::getSetting($this->user, "todon_foto");
	}

}

$test = new SettingsTest();
$test->run();

?>
