<?php
include_once(SERVLETS_DIR.'Servlet.class.php');
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'entities/spot/MySpot.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'utils/PhotoResizer.class.php';
include_once PHP_CLASS.'entities/media/MediaOptions.class.php';

class MediaServlet extends Servlet{
	
	public static $MAPPING = array(
		"Photo" => "entities/photo/Photo.class.php"
	);
	
	public static final function getUrl(MediaEntity $ent, MediaOptions $options = null) {
		$url = new Url("/servlets/");
		$url->addParameter("servlet", "media");
		$size = PhotoResizer::PROFILE;
		if ($options) {
			$size = $options->getSize();
		}else {
			$size = PhotoResizer::PROFILE;
		}
		$url->addParameter("resize", $size);
		$url->addParameter("entity", get_class($ent));
		$url->addParameter("id", $ent->getKey());
		return $url->toString();
	}
	
	/**
	 * @WebAction
	 */
	public function overview() {
		$file = $this->getMediaFile();
		
		$resize = $this->getInt("resize", -1);
		if ($resize == -1) {
			$resize = PhotoResizer::ORIGINAL;		
		} 
		//DebugUtils::debug($file);
		$g = new PhotoResizer($file);
		$g->setType($resize);
		$g->toBrowser();
	}
	
	private function getMediaFile() {
		$entityName = $this->get("entity");
		$systemid = $this->getInt("id", -1);
		
		Utils::assertNotNull("Invalid entity", $entityName);
		Utils::assertTrue("Invalid id", $systemid != -1);
		
		$include_file = self::$MAPPING[$entityName];
		include_once PHP_CLASS.$include_file;
		
		$ent = EntityFactory::loadEntity(new $entityName, UserFactory::getSystemUser(), $systemid);
		
		Utils::assertNotNull("entity == null", $ent);
		
		return $ent->getFile();
	}
	
}

?>