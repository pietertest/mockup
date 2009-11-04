<?php
include_once PHP_CLASS.'entities/mylocation/MyLocationFactory.class.php';
include_once PHP_CLASS.'entities/location/City.class.php';
include_once PHP_CLASS.'entities/notification/NotificationEntity.class.php';

class OproepReaction extends DatabaseEntity implements NotificationEntity {

	function __construct() {
    	parent::__construct("reflirt_nieuw", "oproepreaction");
    }
    
    public function getFromUser() {
    	$fromuser = $this->get("fromuser");
    	return UserFactory::getUserBySystemid($fromuser);
    }
    
    public function getNotificationHandler() {
    	return new OproepReactionNotificationHandler($this);
    }
    
}

class OproepReactionNotificationHandler implements NotificationHandler {
	
	private $entity = null;
	
	public function __construct(OproepReaction $entity) {
		$this->entity = $entity;
	}
	public function getMessage() {
		$username = $this->entity->getUser()->getUsername();
		$otherUsername = $this->entity->getFromUser()->getUsername();
		$message = "Best $username,<br/><br/>";
		$message .= "$otherUsername heeft een reactie geplaatst op je oproep: <br/><br/>";
		$message .= "\"" . $this->entity->get("message") . "\"";
		$message .= "<br/><br/><br/>";
		$message .= "Reflirt.nl";
		return $message;
	}
	
	public function getSubject() {
		return "Reactie van " . $this->entity->getFromUser()->getUsername() . " op je oproep";
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
?>