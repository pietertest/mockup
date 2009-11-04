<?php
include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'utils/StopWatch.class.php';
//include_once(PHP_CLASS.'utils/Utils.class.php');


class TestCase{
	private $curMethod;
	private $aMethods = array();
	private $aEntities = array();
	private $user = null;

	function getUser() {
		if($this->user == null){
			$this->user = UserFactory::getUserbyLogin('UnitTestUser');
			if($this->user == null) {
				throw new RuntimeException("Unit test user == null. " .
						"Creer een user 'UnitTestUser'");
			}
		}
		return $this->user;
	}

    function run() {
		
    	$this->printHead();
    	$methods = get_class_methods($this);
    	$this->setUp();
    	$this->aMethods = $methods;
    	foreach($methods as $method){
    		if(Utils::startsWith($method, 'test')){
				$s1 = new StopWatch("");
				$s1->start();
				$this->currMethod = $method;
				$this->aMethods[$method] = true;
				$this->printStart($method);
				$meth = array(&$this, $method);
    			call_user_func($meth);
				$this->printEnd($s1->end());
    		}
    	}
    	$this->tearDown();
    }

    function create(Entity $o) {
    	$o->setUser($this->getUser());
    	$this->fill($o);
    	$this->aEntities[] = $o;
    	return $o;
    }

    function fill(Entity $o) {
    	// fill columns here...
    }

    function setUp(){}

    function tearDown(){
    	foreach($this->aEntities as $ent) {
			$ent->delete();
    	}
    }

    function handleException($exception) {
    	print $exception;
    }

    function assertNotEmpty($obj) {
    	if(empty($obj)) {
    		throw new Exception($obj . " == empty");
    	}
    }

    private function printHead() {
    	echo '<h1>'.get_class($this).'</h1>';
    }
    private function printStart($method) {
    	echo '<b>'.$method.'()</b>';
    	echo '<div style="border: 1px solid #DCE6F5; background-color: #EDF2FA">';
    }

    private function printEnd($duration) {
    	$this->printMethodStatus($duration);
    	echo '</div>';
    	echo '<br/>';
    	echo '<br/>';
    }

    private function printMethodStatus($duration) {
    	if($this->aMethods[$this->currMethod]) {
    		$this->printStatusOk($duration);
    	} else {
    		$this->printStatusFailed($duration);
    	}
    }

    private function printStatusOk($duration) {
    	echo '<div style="background-color: green; color: white;">Succes ('.$duration.')</div>';
    }

    private function printStatusFailed($duration) {
    	echo '<div style="background-color: red;">Failed ('.$duration.')</div>';
    }

    function out($s){
    	echo $s;
    }

    function assertNotNull($mesg, $obj){
    	if($obj == null) {
    		$this->aMethods[$this->currMethod] = false;
    		$this->out($mesg);
    	}
    }

    function assertNull($mesg, $obj){
    	if($obj != null || empty($obj)) {
    		$this->aMethods[$this->currMethod] = false;
    		$this->out($mesg);
    	}
    }

    function assertTrue($mesg, $condition){
    	if(!$condition) {
    		$this->aMethods[$this->currMethod] = false;
    		$this->out($mesg);
    	}
    }
    
    function assertEquals($mesg, $value, $expected){
    	if ($value != $expected) {
    		$this->aMethods[$this->currMethod] = false;
    		$this->out($mesg);
    		$this->out( " (expected: ".$expected."), but was: ".$value);
    	}
    }

}
?>