<?php
/* @version $Id: UserEntity.class.php,v 1.1 2007/03/04 16:46:19 pieter Exp $ */
include_once PHP_CLASS.'entities/db/DatabaseEntity.class.php';

class Province extends DatabaseEntity{

	function __construct() {
		parent::__construct("reflirt_nieuw", "province");
    }
}
?>