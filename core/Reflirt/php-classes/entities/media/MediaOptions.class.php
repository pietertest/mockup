<?php
include_once PHP_CLASS.'utils/PhotoResizer.class.php';

class MediaOptions {
	
	private $size = PhotoResizer::MEDIUM;
	
	public function setSize($size) {
		$this->size = $size;
	}
	
	public function getSize() {
		return $this->size;
	}

}

?>