<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../config/config.php');
include_once(PHP_CLASS.'searchers/ACSearcher.class.php');
include_once PHP_CLASS.'entities/user/User.class.php';
include_once(PHP_CLASS.'entities/user/UserFactory.class.php');
include_once(PHP_CLASS.'utils/Utils.class.php');
include_once(PHP_CLASS.'batches/MailBatch.class.php');
include_once(PHP_CLASS.'entities/notification/NotificationExecutor.class.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');
include_once(PHP_CLASS_TEST.'exceptions/ClassNotFoundExceptionTest.php');
include_once(PHP_MAILER.'class.phpmailer.php');

class MailBatchTest extends TestCase {

	function testMailBatch() {
		$ds = new DataSource();
		$ds->put("aantalLeden", 10000);
		
		$templateRenderer = new NieuweReflirtTemplateRenderer();
		$templateRenderer->setData($ds);
		
		$oq = ObjectQuery::buildACS(new User, UserFactory::getSystemUser());
		$oq->addConstraint(Constraint::like("email", "pieterfibbe%"));
		$users = SearchObject::search($oq);
		
		$mailing = new MailBatch();
		$mailing->setUsers($users);
		$mailing->setTemplateRenderer($templateRenderer);
		$mailing->execute();
	}
	
}

class NieuweReflirtTemplateRenderer extends UserMailingList {
	
	public function getMessage() {
		$smarty = new Smarty();
		$smarty->compile_dir = SMARTY_COMPILE_DIR;
		$smarty->template_dir = PHP_CLASS_TEST . "email";
		$smarty->assign("username", $this->user->getUsername());
		return $smarty->fetch("mail_batch_contents.html");
		//return "Beste " . $this->user->getUsername() . ",<br/><br/> Reflirt is weer nieuw!";
	}
	
	public function getSubject() {
		return "Nieuwe versie van Reflirt.nl " . $this->user->getUsername() . "!";
	}
	
	public function getFrom() {
		return NotificationExecutor::getSystemAfzender();	
	}
	
	public function getFromName() {
		return NotificationExecutor::getSystemAfzenderName();
	}
	
	public function getTo() {
		return $this->user->getEmailAddress();	
	}
	
	public function getToName() {
		return $this->user->get("firstname") . " " . $this->user->get("lastname");	
	}
	
}

$test = new MailBatchTest();
$test->run();

?>
