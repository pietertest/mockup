<?php

$doProfile = isset($_GET['profile']);

if($doProfile) {
	profile();
}

function profile() {
	$docRoot = $_SERVER['DOCUMENT_ROOT'];
	deleteFiles($docRoot.'/profile/data');
	apd_set_pprof_trace($docRoot.'/profile/data');
}

function deleteFiles($path) {
	if ($handle = opendir($path)) {
	    
	    while (false !== ($file = readdir($handle))) {
			if(is_dir($file)) {
				continue;
			}
			echo $file;
			unlink($path.'/'.$file);
		}
	closedir($handle);
	}
}

?>
