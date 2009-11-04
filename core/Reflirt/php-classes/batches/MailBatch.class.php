<?php
include_once PHP_CLASS.'entities/notification/NotificationHandler.class.php';
class MailBatch  {
	
	private $users; 
	private $templateRenderer; 
	
	public function setUsers($users) {
		$this->users = $users;	
	}
	
	public function setTemplateRenderer($templateRenderer) {
		$this->templateRenderer = $templateRenderer;
	}
	
	public function execute() {
		
		foreach($this->users as $user) {
			$mail = new PHPMailer();
			
			$this->templateRenderer->setUser($user);
			
			$mail->From		= $this->templateRenderer->getFrom(); 
			$mail->FromName	= $this->templateRenderer->getFromName();
			$mail->Subject	= $this->templateRenderer->getSubject();
			
			$mail->AddAddress($this->templateRenderer->getTo(), 
									$this->templateRenderer->getToName()); 
			$mail->MsgHTML($this->templateRenderer->getMessage());
			
			$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
			
			if (!$mail->send()) {
				Logger::warn("Mail niet verstuurd", __FILE__, __LINE__);			
			}
		}
	}
	
}

abstract class UserMailingList implements NotificationHandler {
	
	private $data;
	protected $user;
	
	public function setData(DataSource $data) {
		$this->data = $data;
	}
	
	public function setUser(User $user) {
		$this->user = $user;
	}
}


?>