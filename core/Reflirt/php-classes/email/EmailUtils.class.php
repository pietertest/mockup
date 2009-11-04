<?php

class EmailUtils {

    public static function newMessage(User $user) {
    	$to = $user->getString("email");
    	
    	$mail    = new PHPMailer();
		$body    = $mail->getFile('contents.html');
		$body    = eregi_replace("[\]",'',$body);
		$subject = "Je hebt een nieuw bericht ontvangen van ".$user->getString("firsname");
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
    
    
    
    
    
    
    
    function EmailUtils() {
    	throw new Exception("No instantiation allowed of utilities class");
    }
}
?>