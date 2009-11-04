<?php

class UserFriendlyMessageException extends Exception{
	
	private $alert = 0; // Geeft aan of de exceptie in een alert moet
	
	public function UserFriendlyMessageException($e, $alert=0) {
		$this->alert = $alert;
		parent::__construct($e);
	}
	
	public function doAlert() {
		return $this->alert;
	}

}
?>