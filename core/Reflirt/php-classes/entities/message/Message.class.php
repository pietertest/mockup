<?php
include_once PHP_CLASS.'entities/db/DatabaseEntity.class.php';
include_once PHP_CLASS.'searchers/DefaultSearcher.class.php';
include_once PHP_CLASS.'entities/notification/NotificationEntity.class.php';

class Message extends DatabaseEntity implements NotificationEntity {

	function __construct() {
		parent::__construct("reflirt_nieuw", "message");
    }
    
    public function getNotificationHandler() {
    	return new MessageNotificationHandler($this);
    }
    
    public function getMessage() {
    	return $this->get("message");
    }
    
    public function getSubject() {
    	return $subject = $this->getString("subject", "(geen)");
    }
    
    public function getOtherUser() {
    	$systemid = $this->getInt("sender", -1);
    	return UserFactory::getUserBySystemid($systemid);
    }
    
    /**@Override*/
    public function getLoadSearcher() {
    	return new MessageLoadSearcher($this);
    }
}

class MessageNotificationHandler implements NotificationHandler {
	
	private $entity = null;
	
	public function __construct(Message $entity) {
		$this->entity = $entity;
	}
	public function getMessage() {
		$username = $this->entity->getUser()->getUsername();
		$otherUsername = $this->entity->getOtherUser()->getUsername();
		$message = "Beste $username,<br/><br/>";
		$message .= "$otherUsername heeft je een bericht gestuurd: <br/><br/>";
		$message .= "\"" . $this->entity->get("message") . "\"";
		$message .= "<br/><br/><br/>";
		$message .= "Reflirt.nl";
		return $message;
	}
	
	public function getSubject() {
		return "Nieuw bericht van " . $this->entity->getOtherUser()->getUsername();
	}
	
	public function getTo() {
		return $this->entity->getUser()->get("email");
	}
	
	public function getFrom() {
		return NotificationExecutor::getSystemAfzender();
	}
	
	public function getFromName() {
		return NotificationExecutor::getSystemAfzenderName();
	}
	
	public function getUser() {
		return $this->entity->getUser();
	}
	
}

class MessageLoadSearcher extends LoadSearcher {
	
	function getTables(DataSource $ds) {
		return " FROM message INNER JOIN users ".
			" ON message.sender = users.systemid ";
	}
	
}
?>