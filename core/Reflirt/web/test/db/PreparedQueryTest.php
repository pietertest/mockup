<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../config/config.php');
include_once(PHP_CLASS.'searchers/ACSearcher.class.php');
include_once PHP_CLASS.'entities/user/User.class.php';
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once(PHP_CLASS.'utils/Utils.class.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');

class PreparedQueryTest extends TestCase {

	function testSelect() {
		$pq = new PreparedQuery(DEFAULT_DATABASE);
		$pq->setSelect("FROM users");
		$pq->setSelectFields("username, email");
		$pq->addFilter("username", "pieter");
		$pq->setLimit(10);
		DebugUtils::debug($pq->execute());
	}
	
	function testQueryConstraint() {
		$list = new QueryConstraintList();
		$list->addIfKey("naam", "pieter");
		$list->add(Constraint::gt("birthdate", "19-10-1981"));
		$list->add(Constraint::in("sex", array("1",2)));
		$list->add(Constraint::between("age", 50, 60));
		$list->add(Constraint::match("achternaam", "fibbe"));
		
		// String waarde met '?'
		
		// Values
		//	DebugUtils::debug($list->getValues());
		$values = $list->getValues();
		
		$expectedValues = array(
			"pieter",
			"19-10-1981",
			"1",
			"2",
			"50",
			"60",
			"fibbe"
		);
		
		$this->assertEquals("Volgorde is niet gelijk van de parameters", $expectedValues, $values);
		
		
		DebugUtils::debug($list->getValues());
		DebugUtils::debug($list->toString());
		echo strlen($list->toString());
		$expected = "naam = ? AND birthdate > ? AND sex IN ( ? ,  ? ) AND age BETWEEN ? AND ? AND  MATCH(achternaam) AGAINST(? IN BOOLEAN MODE)";
		$this->assertEquals("Niet gelijk", $list->toString(), $expected);
	}
	
	function testPreparedStatement() {
		
		$pdo = new PDO("mysql:host=localhost;dbname=reflirt_nieuw", "reflirt_user", "reflirt_pass123");
		$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	   	$stmt = $pdo->prepare("select * from users limit 1");
	   	$this->assertNotNull("Geen geldig statement", $stmt);
	   	$username = "--Anitaatje--";
	   	$email = "b'sdf\"lub";
	   	//$stmt->bindParam(1, $email);
	   	$stmt->bindParam(1, $username);
	   	DebugUtils::debug($stmt);
	   	
	   	$result = $stmt->execute();
	   	echo "No of records updated: " . $result;
	   	//$stmt->fetchAll();
	}
	
	function testPrepareQuery() {
		$pq = new PreparedQuery(DEFAULT_DATABASE);
		$pq->setUpdate("users");
		$pq->addUpdateField("email", "harry@gmail.com");
		$pq->addFilter("username", "pieter");
		$pq->execute();
	}
	
	function testSetQuery() {
		$pq = new PreparedQuery(DEFAULT_DATABASE);
		$pq->setQuery("SELECT * FROM users LIMIT 10");
		$pq->execute();
	}

	
}

$test = new PreparedQueryTest();
$test->run();

?>
