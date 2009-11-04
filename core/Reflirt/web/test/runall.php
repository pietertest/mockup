<?php
include_once('../../config/init_smarty_config.php');
include_once(PHP_CLASS.'core/ClassLoader.class.php');
include_once PHP_CLASS.'entities/message/Message.class.php';
include_once PHP_CLASS.'utils/FileUtils.class.php';
include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'utils/ErrorHandler.class.php';


$errors = array();
//error_reporting(0); // Geen errors tonen, doen we later
set_error_handler('handleError');
//set_exception_handler('handleException');
$files = FileUtils::readDirectory('.');

define('ROOT', $_SERVER['DOCUMENT_ROOT']);

foreach($files as $file) {
	if(!$file->isDir()) {
		if(Utils::endsWith($file->getFilename(), 'Test.php')) {
			include_once($file->getFilename());
		}
	}
}

//DebugUtils::debug($errors);

function handleError($errno, $errmsg, $filename, $linenum, $vars) {
	$handler = new ErrorHandler($errno, $errmsg, $filename, $linenum, $vars);
	echo "<pre>";
	echo $handler->toString();
	echo "</pre>";
	$errors[] = $handler->ErrorHandler($errno, $errmsg, $filename, $linenum, $vars);
}

function handleException($exception) {
	$errors[] = $exception;
 	//DebugUtils::printException($exception);
	echo "Uncaught exception: " , $exception->getMessage(), "\n";
}


?>