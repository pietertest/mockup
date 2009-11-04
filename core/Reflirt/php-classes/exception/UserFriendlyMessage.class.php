<?php


class UserFriendlyMessage extends Exception{
	
	private $template;
	
	public function UserFriendlyMessage($e, $template=null) {
		$this->template = $template;
		parent::__construct($e);
	}
	
	public function getTemplate() {
		return $this->template;
	}
	

}
?>