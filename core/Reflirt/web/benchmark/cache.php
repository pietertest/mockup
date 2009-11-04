<?php
include("../../config/init_smarty_config.php");
include_once(PHP_CLASS.'core/ClassLoader.class.php');
include_once PHP_CLASS.'core/PageLoader.class.php';
include_once PHP_CLASS.'entities/message/Message.class.php';

for($i = 0; $i < 100; $i++) {
	$m = new MessageEntity();
	$m->setKey(1000);
	$m->load();
}

/*
echo "<pre>";
print_r($m);
echo "</pre>";
*/

?>