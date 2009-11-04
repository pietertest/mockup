<?php

interface NotificationHandler {
	
	public function getMessage();
	
	public function getSubject();
	
	public function getFrom();
	
	public function getFromName();
	
	public function getTo();
	
}

?>