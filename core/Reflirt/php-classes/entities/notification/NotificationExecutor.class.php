<?php
include_once PHP_MAILER.'class.phpmailer.php';

class NotificationExecutor {
	
	public final static function handle($ent) {
		if (!$ent instanceof NotificationEntity ) {
			return;
		}
		if (!$ent->wasNew()) {
			return;
		}
		$handler = $ent->getNotificationHandler();
		
		$message = $handler->getMessage();		
		$subject = $handler->getSubject();		
		$to = $handler->getTo();
		$from = $handler->getFrom();
		$fromName = $handler->getFromName();

		$mail = new PHPMailer();
		$mail->AddAddress($to); 
		$mail->From		= $from; 
		$mail->FromName	= $fromName; 
		$mail->Subject	= $subject;
		$mail->MsgHTML($message);
		$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		
		if (!$mail->send()) {
			Logger::errorAndMail("Mail niet verstuurd", __FILE__, __LINE__);			
		}
	
	}
	
	public static final function getSystemAfzender() {
		return "info@reflirt.nl";		
	}
	
	public static final function getSystemAfzenderName() {
		return "Reflirt";		
	}

}

?>