<?php
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'actionresult/ActionResult.class.php';
include_once PHP_CLASS.'entities/user/UserProfile.class.php';

class AuthPage extends Page {
	
	/** @WebAction */
	public function overview() {
		
	}
	/**
	 * @WebAction
	 */
	public function login() {
		$email = $this->getString("username");
		if (empty($email)) {
			$this->fail("Er is geen combinatie van deze gebruikersnaam \"<b>$email</b>\" en wachtwoord bekend in ons systeem. Probeer het nogmaals.");
			return;	
		}
		$user = UserFactory::getUserByLogin($email);
		if($user == null) {
			$this->fail("Er is geen combinatie van deze gebruikersnaam \"<b>$email</b>\" en wachtwoord bekend in ons systeem. Probeer het nogmaals.");
			return;
		}
		if($user->getKey() == -1) {
			$this->fail("Er is geen combinatie van deze gebruikersnaam \"<b>$email</b>\" en wachtwoord bekend in ons systeem. Probeer het nogmaals.");
			return;
		}
		$password = md5($this->getString("password"));
			
		if($user->getString("password") != $password || empty($password)) {
			$this->fail("Er is geen combinatie van deze gebruikersnaam \"<b>$email</b>\" en wachtwoord bekend in ons systeem. Probeer het nogmaals.");	
			return;
		}
		$lastlogout = $user->get("lastlogout");
		$lastaction= $user->get("lastaction");
		if(DateUtils::getDateDiffSeconds($lastaction, $lastlogout) > 0) {
			$user->put("lastlogout", $lastaction);
		}
		$user->save();
		$lastlogin = $user->get("lastlogin", DateUtils::now());
		
		$_SESSION["previouslogin"] = $lastlogin;
		$_SESSION["lastlogout"] = $lastlogout;
		$user->put("lastlogin", DateUtils::now());
		$_SESSION["user"] = serialize($user);
//		$_SESSION["username"] = $user->getString("username");
//		$_SESSION["uid"] = $user->getKey();
		header("Location: /?page=account");
	}
	
	/**
	 * @WebAction
	 */
	public function forgotpassword(){}
	
	/**
	 * @WebAction
	 */
	public function sendpassword(){
		$email = $this->get("email", null);
		if(Utils::isEmpty($email)) {
			$this->fail("Er komt geen gebruiker voor in het systeem het opgegeven e-email adres \"<b>$email</b>\"");
			$this->setTemplate("forgotpassword");
			return;
		}
		$systemUser = UserFactory::getSystemUser();
		$oq = ObjectQuery::buildACS(new UserProfile(), $systemUser);
		$oq->addConstraint(Constraint::eq("email", $email));
		$user = SearchObject::select($oq);
		if ($user != null) {
			$password = Utils::generatePassword(6, 4);
			mail($email,
					"Wachtwoord Reflirt.nl", "Beste ".$user->get("username").",\n\n" .
					"Je hebt op Reflirt.nl je wachtwoord aangevraagd. Je nieuwe wachtwoord is:\n\n" .
					$password."\n\n" .
					"We hopen je gauw weer te zien op Reflirt.nl!\n\n" .
					"Reflirt.nl"
				,
				"From: \"Reflirt.nl\" <noreply@reflirt.nl>\r\n"
						);
			$user->putCol("password", md5($password));
			$user->save();
			return;
		}
		
		$this->fail("Er komt geen gebruiker voor in onze database met het email adres <b>$email</b>");
		$this->setTemplate("forgotpassword");
	}

	/**
	 * @WebAction
	 */
	public function logout() {
		if(!isset($_SESSION["user"])) {
			return;
		}
		$user = unserialize($_SESSION["user"]);
		$user->putCol("lastlogout", DateUtils::now());
		$user->save();
		unset($_SESSION["user"]);
		unset($_SESSION["previouslogin"]);
		unset($_SESSION["lastlogout"]);
		unset($_SESSION["firstlogin"]);
		//ActionResult::header("/?page=home&action=overview", "logout", true);
		header("Location: /?page=home");
	}


}

?>
