<?php
include_once "Framework/entities/message/Message.class.php";

class HomeController extends Page {
	
	/**
	 * @WebAction
	 */
	public function overview() {
		$message = new Message();
		$message->put("naam", "pieter");
		DebugUtils::debug($message);
	}

}

?>