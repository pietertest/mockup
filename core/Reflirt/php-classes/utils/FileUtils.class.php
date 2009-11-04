<?php
include_once PHP_CLASS.'io/File.class.php';
include_once PHP_CLASS.'utils/Utils.class.php';

class FileUtils {
	private $aFiles = array();
	private $currDir;
	
	static function readDirectory($dir, $recursive = false) {
		Utils::assertTrue("Geen directory: ".$dir, is_dir($dir));
		
		$fileUtils = new FileUtils();
		$dir2 = realpath($dir);
		$fileUtils->readDirectory2($dir, true);
		return $fileUtils->getFiles();
	}
	
	 function getFiles() {
		return $this->aFiles;
	}
	
	public static final function copy($source, $destDir, $destFile) {
		if(!file_exists($destDir)) {
			mkdir($destDir);
		}
		return copy($source, $destDir . "/" . $destFile);
	}
	
	private function readDirectory2($dir, $recursive = false, $doPrint = false) {
		if($dir == '.svn' || $dir == "." || $dir == ".." ) { // svn directories niet uitlezen
			return;
		}
		//echo "[Dir]  ".$dir.":";
		if ($handle = opendir($dir)) {
		    while (false !== ($file = readdir($handle))) {
		        if ($file != "." && $file != "..") {
		    	
		            if(is_dir($dir."/".$file)) {
		            	//echo "[DIR]: ".$dir;
		     			$this->currDir = realpath($file);
		     			$f = new File(realpath($file));
		     			$this->aFiles[] = $f;
		     			$this->readDirectory2($dir."/".$file, $recursive);
		            } else { 
//		            	echo $file;
		            	$pathInfo = pathinfo($dir."/".$file);
		            	$f = new File(realpath($dir).'/'.$file);
		     			$this->aFiles[] = $f;
		        	}	
		        }
		    }
		    closedir($handle);
		}		
	}
}
?>
