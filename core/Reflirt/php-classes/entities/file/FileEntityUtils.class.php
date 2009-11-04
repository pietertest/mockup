<?php

class FileEntityUtils {
	
	public static final function setFile(FileEntity $ent, $path) {
		$dir = self::getDir($ent);
		$copyTo = $dir . $ent->getKey();
		copy($path, $copyTo);
	}
	
	public static final function getFile(FileEntity $ent) {
		$dir = self::getDir($ent);		
		$path = $dir . $ent->getKey();
		return $path;
	}
	
	public static final function getDir(PersistentEntity $ent) {
		$className = get_class($ent);
		$dir = UPLOAD.$className."/";
		if(!file_exists($dir)) {
			mkdir($dir);	
		}
		return $dir;
	}
	

}

?>