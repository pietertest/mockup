<?php
include_once(PHP_CLASS.'entities/db/DatabaseEntity.class.php');
include_once(PHP_CLASS.'searchers/DefaultSearcher.class.php');
include_once(PHP_CLASS.'entities/file/FileEntityUtils.class.php');
include_once(PHP_CLASS.'entities/file/FileEntity.class.php');
include_once(PHP_CLASS.'utils/FileUtils.class.php');
include_once(PHP_CLASS.'entities/media/MediaEntity.class.php');
include_once(PHP_CLASS.'io/BaseImage.class.php');
include_once(SERVLETS_DIR.'media/MediaServlet.class.php');

class Photo extends DatabaseEntity implements MediaEntity {
		
    public static $ORIG = -1;
	// Groottes van de pictures voor verchillende profiellokaties
	public static $SIZE_OVERVIEW = 90;
	public static $SIZE_PROFILE = 150;
	public static $SIZE_SMALL = 50;
	
    function __construct() {
    	parent::__construct("reflirt_nieuw", "photo");
    }
    
    public function setImage(Image $image) {
    	$this->image = $image;
    }
    
    public function getPath() {
    	$dir = FileEntityUtils::getDir($this);
    	$filename = $this->get("filename");
//    	DebugUtils::debug("filename: ". $dir . $filename);
//    	DebugUtils::debug("realpath: " . realpath($dir . $filename));
    	
    	return ($dir . $filename); 
    }
    
    public function save() {
		$source = $this->image->getPath();
		
		
		$destDir = FileEntityUtils::getDir($this);
		$destFile = md5($this->getUser()->getKey() . time()) . $this->image->getName();
		
		if (!FileUtils::copy($source, $destDir, $destFile)) {
			Logger::errorAndMail("Er is iets fout gegegaan bij het bewaren van de photo", __FILE__, __LINE__);
			throw new UserFriendlyMessageException("Er is iets fout gegegaan bij het bewaren van de photo");
		}
    	$this->put("filename", $destFile);
    	$this->put("orig_filename", $this->image->getName());
    	$this->put("mimetype", $this->image->getMimeType());
    	$this->put("size", $this->image->getSize());
    	$this->put("width", $this->image->getWidth());
    	$this->put("height", $this->image->getHeight());
    	parent::save();
    }
    
    public function getFile() {
    	$file = new BaseImage($this->getPath());
    	$file->setMimeType($this->get("mimetype"));
    	return $file;
    }
    
    public function getUrl() {
    	return MediaServlet::getUrl($this);
    }
    
    public function getDefaultSearcher() {
    	return new DefaultPhotoSearcher();
    }
    
    /**
     * Maakt van een photo 3 verschillende formaten in 3 verschillende mappen.
     */
    public function makeProfilePictures() {
    	$sizes = array(self::$SIZE_OVERVIEW, self::$SIZE_PROFILE);
    	foreach($sizes as $size) {
    		$dest = PHOTOS.$size."/".$this->image->getBasename();
    		$this->resizeTo($dest, $size, $size);
    	}
    }
}

class DefaultPhotoSearcher extends DefaultSearcher {
	public function getFields(DataSource $ds) {
		return "*, photo.systemid AS systemid, photoalbum.systemid AS albumid ";
	}
	
	public function getTables(DataSource $ds) {
		return "FROM photo".
			" LEFT JOIN photoalbum " .
			" ON photo.albumid = photoalbum.systemid " .
			" AND photo.user = photoalbum.user ";
	}
}
?>