<?php
$term = $_REQUEST['q'];
$images = array_slice(scandir("userpictures"), 2);
foreach($images as $value) {
	if( strpos(strtolower($value), $term) === 0 ) {
		echo $value . "\n";
	}
}
?>
