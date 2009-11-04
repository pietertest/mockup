<?php

include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/user/UserProfile.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/notification/NotificationEntity.class.php';
include_once PHP_CLASS.'entities/notification/NotificationExecutor.class.php';

class SubscribePage extends Page {

	/** @WebAction */
	public function overview() {}
	
	/** @WebAction */
	public function registered() {}
	
	/**
	 * @WebAction
	 * @JSON
	 */
	public function register() {
		$username = $this->getString("username");
		
		$user = new User();
		$user->putAll($this);
		$user->putCol("username",$username);
		$user->put("lastaction", DateUtils::now());
		
		
		$password = $this->get("password");
		if(!empty($password)) {
			Utils::validateTrue("De wachtwoorden dienen gelijk te zijn",
				$password == $this->get("password2"), "password");
			$user->put("password", md5($password));
		} else {
			$user->remove("password");
		}
		
		try {
			$user->save();
		}catch (DuplicateException $e) {
			$field = $e->getField();
			$value = $e->getValue();
			if($field == "email") {
				throw new ValidationException("Het emailadres '$value' komt al voor in het systeem. Kies een ander emailadres.", $field);
			} else if($field == "username") {
				throw new ValidationException("De gebruikernaam '$value' is al in gebruik", $field); 	
			} else {
				throw new ValidationException("Het veld '$value' is al in gebruik", $field);
			}
		}
		
		$this->sendEmail($user);
		
		$_SESSION["user"] = serialize($user);		
		$_SESSION["firstlogin"] = serialize($user);		
	}
	
	private function sendEmail(User $user) {
		$username = $user->getUsername();
		$message = "Beste $username,<br/><br/>";
		$message .= "Leuk dat je je geregistreerd hebt op Reflirt.nl! De mogelijkheid tot het vinden van die ene flirt op dat moment wordt voor jou hopelijk eeen stukje dichterbij gebracht door je oproep te plaatsen op Reflirt.nl.<br/><br/>";
		$message .= "Plaats nu gauw je oproep en vind je flirt terug!<br/><br/>";
		$message .= "<br/><br/>";
		$message .= "Team Reflirt.nl";
		
		$mail = new PHPMailer();
		$mail->AddAddress($user->get("email")); 
		$mail->From		= NotificationExecutor::getSystemAfzender(); 
		$mail->FromName	= NotificationExecutor::getSystemAfzenderName(); 
		$mail->Subject	= "Welkom op Reflirt.nl!";
		$mail->MsgHTML($message);
		$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		
		if (!$mail->send()) {
			Logger::errorAndMail("Mail niet verstuurd", __FILE__, __LINE__);			
		}
	}
}

?>
