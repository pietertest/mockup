<?php
/* @version $Id: UserEntity.class.php,v 1.1 2007/03/04 16:46:19 pieter Exp $ */

include_once(PHP_CLASS.'entities/db/PreparedQuery.class.php');
include_once(PHP_CLASS.'entities/db/DatabaseEntity.class.php');
include_once(PHP_CLASS.'utils/Utils.class.php');

class SettingsEntity extends DatabaseEntity{

	function SettingsEntity() {
		parent::__construct('user', 'settings');
    }

}
?>