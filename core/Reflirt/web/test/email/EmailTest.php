<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/../phpincl/init_smarty_config.php');
include_once(PHP_CLASS.'searchers/ACSearcher.class.php');
include_once PHP_CLASS.'entities/user/User.class.php';
include_once(PHP_CLASS.'entities/user/UserFactory.class.php');
include_once(PHP_CLASS.'entities/message/Message.class.php');
include_once(PHP_CLASS.'entities/settings/SettingsFactory.class.php');
include_once(PHP_CLASS.'utils/Utils.class.php');
include_once(PHP_CLASS_TEST.'TestCase.class.php');
include_once(PHP_CLASS_TEST.'exceptions/ClassNotFoundExceptionTest.php');
include_once(PHP_MAILER.'class.phpmailer.php');

class EmailTest extends TestCase {


	function testSendEmail() {
		$mail    = new PHPMailer();
		$body    = $mail->getFile('contents.html');
		$body    = eregi_replace("[\]",'',$body);
		$subject = "Je hebt een nieuw bericht ontvangen van Sjaan";
		$subject = eregi_replace("[\]",'',$subject);
		$mail->From     = "noreply@reflirt.nl";
		$mail->FromName = "Reflirt.nl";
		$mail->Subject = $subject;
		$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$mail->MsgHTML($body);
		$mail->AddAddress("test@oebiedoe");
		$mail->AddAttachment("foto.jpg");
		$this->assertTrue("Mail niet gestuurd. Staat de mailserver aan?", $mail->Send());
	}

	
}

$test = new EmailTest();
$test->run();

?>
