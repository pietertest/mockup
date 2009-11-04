<?php
include_once(PHP_CLASS."io/Image.class.php");

class PhotoResizer {
	
	// Mogelijke schalen
	const SMALL = 4;
	const PROFILE = 1;
	const MEDIUM = 2;
	const ORIGINAL = 3;
	
	private $DEFAULT_WIDTH = 130;
	private $width = -1;
	
	public function __construct(Image $image) {
		$this->image = $image;
	}
	
	function setType($type){
		$this->type = $type;
		$width = -1;
		switch($type) {
			case self::SMALL:
				$width = 70;
				break;
			case self::PROFILE:
				$width = 130;
				break;
			case self::MEDIUM:
				$width = 500;
				break;
			case self::ORIGINAL:
				$width = -1;
				break;
			default:
				$width = $this->DEFAULT_WIDTH;
				break;
		}
		$this->setWidth($width);		
	}
	
	function setWidth($width) {
		$this->width = $width;
	}
	
	public function toBrowser() {
		header('Content-Type: ' . $this->image->getMimeType());
		$path = $this->image->getPath();
		if(!file_exists($path)) {
			$path = WEB . "images/global/dude.gif";
		}
		if ($this->type == self::ORIGINAL) {
			readfile($path);
		} else {
			$this->printPhotoByLocation($path);
		}
	}
	
	private function printPhotoByLocation($location) {
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
		$Pw = imagesx($Picture);
		$Ph = imagesy($Picture);
		$width=$Pw;
		$height=$Ph;

		$width = ($this->width == -1)? $Pw: $this->width;
		$scalefactor = $Pw / $width;	
		$height = $Ph / $scalefactor;
		
		$im = imagecreatetruecolor ($width, $height) or die ("Cannot Initialize new GD image stream");
		$this->printImage($im, $Picture, $width, $height, $Pw, $Ph);		
	}
	
	function printImage($im, $Picture, $width, $height, $Pw, $Ph){
		imagecopyresized($im,$Picture,0,0,0,0,$width,$height,$Pw,$Ph);
		Imagejpeg($im);
		imagedestroy($im);
	}
}
?>