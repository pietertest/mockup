 <?php
include("../config/config.php");
include_once PHP_CLASS.'internet/Url.class.php';
include_once PHP_CLASS.'utils/FileUtils.class.php';
include_once PHP_CLASS.'utils/DateUtils.class.php';
include(BASEDIR."functions/function_datetime.php");

$exception = "Duplicate entry 'pieterfibbe@gmail.com' for key 'sd'";
$matches = array();
//if(preg_match('/^\d+: Duplicate entry \'(.*)\' for key \d+$/i', $exception, $matches)) {
if(preg_match('/^Duplicate entry \'(.*)\' for key \'(.*)\'$/i', $exception, $matches)) {
	DebugUtils::debug($matches);
}

$keywords = "pieter fibbe";
$keywords = preg_replace("/(\w+)/i", "+$1", $keywords);
DebugUtils::debug($keywords);
exit();

function getInt($var, $default) {
	$val = "0";
	if($val == 0 || $val == "0") {
		return 0;	
	}
	echo (int)$val;
	if(!is_int($val)) {
	 	return $default;
	}
	 
	if(Utils::isEmpty($val)){
		return $default;
	} else {
		return $val;
	}
}

return;
echo date("Y-m-d", strtotime("19-10-1981"));
//$d = new DateTime("19-10-1981 21:24:23");
//echo $d->format();

$header = "From: blub@reflirt.nl";
mail("pieterfibbe@gmail.com", "ttest", "hoi bericht", $header	);
session_start();

echo md5(time());

unset($_SESSION["user"]);
unset($_SESSION["previouslogin"]);
unset($_SESSION["lastlogout"]);

$dateTime = new DateTime("1981-10-1", new DateTimeZone('Europe/Warsaw'));
echo $dateTime->format("Y-m-d H:i:s");
 

exit();
date("Y-m-d H:i:s", mktime());
$lastAction = date("Y-m-d H:i:s", mktime() - 60*60);
$now = date("Y-m-d H:i:s", mktime());

return;

$a =  array(
	"username" => "PieterGenieter",
	"lastname" => "Fibbe"
);

$mapping =  array(
	"username" => "naam",
	"lastname" => "achternaam"
);

function convert($a1, $a2) {
	echo $a2 . " > " . $a1;
	
}
array_map("convert", $a, $mapping);

exit(0);


$format = "Y-m-d H:i:s";
echo gettype(mktime()) ;

exit(0);
$f = "c:/documents and settings/pieter/mijn documenten/workspace/trunk/templates/account/account.overview.tpl";
function replaceJavaScript($file) {
	$xml = file_get_contents($file);
	//$a = preg_replace(array('!\{literal\}<scriptt>*.*</scriptt!m'), '', $s);
	//preg_match_all('/{literal}<script>(.*)<\/script>{\/literal}/isU', $xml, $arr, PREG_SET_ORDER);
	preg_match_all('/{literal}<script>(.*)<\/script>{\/literal}/isU', $xml, $arr, PREG_SET_ORDER);
	while(list(,$item) = each($arr)) {
		$javascript = $item[1];
		$scriptfile = generateScriptFile($javascript);
		echo $scriptfile;
	}
	
	$pattern = '/{literal}(<script>(.*)<\/script>){\/literal}/isU';
	$replacement = '$2';
	echo preg_replace($pattern, $replacement, $xml);
	//DebugUtils::debug($xml);
	$string = 'April 15, 2003';
	$pattern = '/{literal}(<script>(.*)<\/script>){\/literal}/isU';
	$replacement = '$2';
	echo preg_replace($pattern, $replacement, $xml);
	return;
    
    // parse each individual item
    while (list(, $item) = each($arr)) {
    	DebugUtils::debug($item);
    }
}
replaceJavaScript($f);

function generateScriptFile($javascript) {
	$filename = md5($javascript).".js";
	return $filename;
}

exit();
$dir = "c:/documents and settings/pieter/mijn documenten/workspace/trunk/templates/";
$files = FileUtils::readDirectory($dir, true);


foreach($files as $file) {
	if(!$file->isDir()) {
		if ($file->getExtension() == "tpl") {
			echo $file->getBasename()."<br/>";
		}
	}	
}

exit(0);
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
echo "<pre>";



read($dir);
function read($dir) {
	if ($handle = opendir($dir)) {
		    while (false !== ($file = readdir($handle))) {
		        if ($file != "." && $file != "..") {
						    	
		            	echo "<br/>";
		            if(is_dir($dir."/".$file)) {
		            	echo "[DIR]: ".$dir;
		     			read($dir."/".$file);
		     			
		            } else { 
		            	echo $file." (".$dir.")";
		        	}	
		        }
		    }
		    closedir($handle);
		}		
	}


echo "</pre>";



?>
