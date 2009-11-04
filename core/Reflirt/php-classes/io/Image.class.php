<?php
require_once PHP_CLASS. 'io/File.class.php';

interface Image extends File {
	
	function getWidth();
	function getHeight();
	function getMimeType();

}

?>