<?php
echo "<pre>";

$docRoot = $_SERVER['DOCUMENT_ROOT'];
$path = $docRoot.'/profile/data';
$dataFile = getProfileDataFile($path);

system("php pprofp.php -u data/$dataFile output.txt");
echo "</pre>";



function getProfileDataFile($path) {
	echo "Searching for testdata in d dir: \"".$path."\".";
	if ($handle = opendir($path)) {
	    $files = 0;
	    $profileData = null;
	    while (false !== ($file = readdir($handle))) {
			if(is_dir($file)) {
				continue;
			}
			$files++;
			$profileData = $file;
		}
	}
	if($files > 1) {
		exit("\nMeerdere bestanden gevonden\n");
	} else if ($files < 1) {
		exit("\nGeen profiledata gevonden\n");
	}
	closedir($handle);
	return $profileData;
}

?>
