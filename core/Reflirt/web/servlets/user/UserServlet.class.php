<?php
include_once(SERVLETS_DIR.'Servlet.class.php');
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'entities/spot/MySpot.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';

class UserServlet extends Servlet{
	
	public function overview() {
		$file = $this->getMediaFile();
		
		$resize = $this->getInt("resize", -1);
		if ($resize == -1) {
			$resize = PhotoResizer::ORIGINAL;		
		} 
		//echo $file->getPath();
		$g = new PhotoResizer($file);
		$g->setType($resize);
		$g->toBrowser();
}	
	
}
?>