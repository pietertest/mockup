<?php
include_once(PHP_CLASS."io/BaseFile.class.php");
include_once(PHP_CLASS."io/Image.class.php");

class BaseImage extends BaseFile implements Image {
	
	private $image;
	   
    protected $width		= null;
    protected $height		= null;
	
	protected static $ACCEPTED = array(
    	"image/jpeg",
    	"image/pjpeg",
    	"image/gif",
    	"jpg",
		"gif"
    );
	
    function __construct($url) {
//    	DebugUtils::debug("url: " . $url);
    	parent::__construct($url);
    	Utils::assertTrue("Invalid image type: ".$this->getMimeType(), $this->accepted());
    	$this->readFileIntoImage();
    }
    
    private function readFileIntoImage () {
    	$buffer="";
		$handle = fopen ($this->getPath(), "r");
		if($handle){
			while (!feof ($handle)) {
			    $buffer .= fgets($handle, 4096);
			}
		}
		else{
			return;
		}
		fclose ($handle);
    	$this->image = imagecreatefromstring($buffer);
    	
    	$this->width = imagesx($this->image);
    	$this->height = imagesy($this->image); 
    }
    
    public function accepted() {
    	return in_array(strtolower($this->getExtension()), self::$ACCEPTED);
	}
    
    public function getWidth() {
    	return $this->width;
    }
    
    public function getHeight() {
    	return $this->height;
    }
    
    public function getImage() {
    	return $this->image;
    }
}
?>