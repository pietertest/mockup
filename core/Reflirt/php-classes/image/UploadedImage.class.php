<?php
include_once(PHP_CLASS."io/Image.class.php");

class UploadedImage extends BaseImage {
	
    private $location	= null;
    private $type		= null;
    private $size		= null;
    private $name		= null;
    
    private $dir		= null;
    
    function __construct($file) {
    	$this->location = $file['tmp_name'];
    	$this->type = $file['type'];
    	$this->size = $file['size'];
    	$this->name = preg_replace("/(\s+)/i", "_", $file['name']); // Zet alle spaties om in een '_'$file['name'];
    	
    	$info = $this->getInfo($file['tmp_name']);
    	$this->width = $info['width'];
    	$this->height = $info['height'];
    	Utils::assertTrue("Invalid image type: ".$this->getExtension(), $this->accepted());
    }
    
    private function getInfo($location) {
		$buffer="";
		$handle = fopen ($location, "r");
		if($handle){
			while (!feof ($handle)) {
			    $buffer .= fgets($handle, 4096);
			}
		}
		else{
			return;
		}
		fclose ($handle);
		
		$Picture = imagecreatefromstring($buffer);
		$info = array(
			"width"	 => imagesx($Picture),
			"height" => imagesy($Picture)
		);
		return $info;
	}
    
    public function accepted() {
    	return in_array($this->type, parent::$ACCEPTED);
	}
	
	public function getPath() {
		return realpath($this->location);
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getSize() {
		return $this->size;		
	}
	
	public function getMimeType() {
		return $this->type;
	}
}
?>