<?php
/* @version $Id: UserEntity.class.php,v 1.1 2007/03/04 16:46:19 pieter Exp $ */


class Task extends DatabaseEntity{
	public static $STATUS_WAITING = 0;
	public static $STATUS_EXECUTED = 1;

	function __construct() {
		parent::__construct("reflirt_nieuw", 'task');
	}
}
?>