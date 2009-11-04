<?php
include_once PHP_CLASS.'io/File.class.php';
/**
 * Doet geen mutaties aan de file zelf, creeert alleen info
 */
class BaseFile implements File {
	private $dirname;
	private $basename;
	private $extension;
//	private $filename;
	private $path_parts;
	private $mimetype = "image/jpeg";

	private $file;

    function __construct($file) {
    	$file = str_replace("\\", "/", $file);
    	$this->file = $file;
    	$path_parts = pathinfo($file);
//    	$this->path_parts = $path_parts;
		$this->basename 	= $path_parts['basename'];
		if(isset($path_parts['extension'])) {
			$this->extension	= $path_parts['extension'];
		}
		if(isset($path_parts['dirname'])) {
			$this->dirname 		= $path_parts['dirname']."/";
		}
		
		//$this->filename 	= $path_parts['filename']; // since PHP 5.2.0
    }

    function isDir() {
    	return is_dir($this->file);
    }

    function getDirname() {
    	return $this->dirname;
    }

    /**
     * Filename, ex: blaat.txt
     */
    function getBasename() {
    	return $this->basename;
    }
    
    function getExtension() {
    	return $this->extension;
    }
    
    function dirUp() {
    	$tempDir = substr($this->dirname, 0, strlen($this->dirname)-1); // Haal de laatste '/' weg
    	$pos = strrpos($tempDir, "/");
    	if($pos > 0) {
    		$this->dirname = substr($tempDir, 0, $pos)."/";
    	}
    }
    
    public function getPath() {
    	return realpath($this->getDirname().$this->getBasename());
    } 
    
    public function getSize() {
    	return filesize($this->getPath());
    }

    //function getFilename() {
    	//return $this->file;
    //}
    
	public function setMimeType($mimetype) {
    	$this->mimetype = $mimetype;
    }
    
	public function getMimeType() {
    	return $this->mimetype;
    }
    
}
?>