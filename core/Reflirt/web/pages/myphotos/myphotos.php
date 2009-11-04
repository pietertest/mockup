<?php
ClassLoader::load("entities.photo.Photo");
ClassLoader::load("entities.photo.PhotoAlbum");
ClassLoader::load("entities.user.UserProfile");
ClassLoader::load("entities.user.UserFactory");
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'image/UploadedImage.class.php';
include_once PHP_CLASS.'entities/media/MediaUtils.class.php';

class MyPhotosPage extends Page {
	
	private $FEEDBACK_ALBUM_CREATED = 1;
	private $FEEDBACK_PHOTO_UPLOADED = 2;
	private $FEEDBACK_PHOTO_DELETED = 3;
	private $FEEDBACK_ERROR_MAX_SIZE = 4;
	
	private static $IMAGE_MAX_SIZE = 10000000; // 10 MB
	
	public function init() {
		$this->setHeader("Mijn foto's");
	}
	
	function initFeedbacks() {
		$this->addFeedback(new Feedback(
			$this->FEEDBACK_ALBUM_CREATED, Feedback::$TYPE_SUCCESS, "albumcreated"));
		$this->addFeedback(new Feedback(
			$this->FEEDBACK_PHOTO_DELETED, Feedback::$TYPE_SUCCESS, "photodeleted"));
		$this->addFeedback(new Feedback(
			$this->FEEDBACK_PHOTO_UPLOADED, Feedback::$TYPE_SUCCESS, "photouploaded"));
		$this->addFeedback(new Feedback(
			$this->FEEDBACK_ERROR_MAX_SIZE, Feedback::$TYPE_FAIL, "error"));
			
	}
	
	/** @WebAction*/
	public function overview() {
		$profilePictureId = $this->getUser()->getString("photoid");
		$this->putIf("profilePictureId", $profilePictureId);
		
		$oq = ObjectQuery::buildACS(new PhotoAlbum(), $this->getUser());
		$albumsList = SearchObject::search($oq);
		$albums = array();
		foreach($albumsList as $key=>$album) {
			$oq = ObjectQuery::buildDS(new Photo(), $this->getUser());
			$oq->setOrderBy("photo.insertdate DESC");
			$oq->addConstraint(Constraint::eq("albumid", $album->getKey()));
			$photos = SearchObject::search($oq);
			$albumInfo = array();
			$albumInfo["album"] = $album;
			$albumInfo["photos"] = $photos;
			$albums[] = $albumInfo;
		}
		
		$this->put("albums", $albums);
	}
	
	/** @WebAction */
	public function createalbum() {
		$albumname = $this->getString("album");
		$album = new PhotoAlbum();
		$album->setUser($this->getUser());
		$album->put("albumname", $albumname);
		$album->save();
		//$this->forward("overview", null, $this->FEEDBACK_ALBUM_CREATED);
		header("Location: /?page=myphotos&action=overview");
	}
	
	/**
	 * @WebAction
	 * @Login
	 * @JSON
	 *
	 */
	public function upload() {
		if (!isset($_FILES["picture"])) {
			throw new UserFriendlyMessageException("Oeps, er is iets misgegaan bij het uploaden van je profielphoto. Probeer het nogmaals.");
		}
		$picture = $_FILES["picture"];
		
		$ui = new UploadedImage($picture);
		if(!$ui->accepted()) {
			throw new UserFriendlyMessageException("Dit type is niet toegestaan: " . $ui->getExtension());
		}
		
		$albumid = $this->getInt("album", 0);
		
		$photo = new Photo();
		$photo->setUser($this->getUser());
		$photo->put("albumid", $albumid);
		$photo->setImage($ui);
		$photo->save();
		
		$json = array();
		
		include_once SERVLETS_DIR.'media/MediaServlet.class.php';
		$json["photoUrl"] = MediaServlet::getUrl($photo);
		$json["album"] = $albumid;
		
//		$template = file_get_contents(PAGES."myphotos/templates/myphotos.template.photo.tpl");
//		$json["template"] = $template;
//		
		return $json;
		
	}
	
	private function savePhotoToDisc($file) {
		$image = new UploadedImage($file);
		
		if(!$image->accepted()) { // Check extentie (alleen jpeg geaccepteerd)
			throw new UserFriendlyMessageException("Alleen plaatjes van het type JPEG " .
					"kunnen worden geupload.");
		}
		if($file['size'] > self::$IMAGE_MAX_SIZE) {
			throw new UserFriendlyMessageException("De photo mag niet groter zijn dan " .
					(self::$IMAGE_MAX_SIZE / 1000 ) ." KB.");
		}
		$prefix = $this->getUser()->getString("username");
		$image->save(PHOTOS, $prefix.'_'.time().$image->getName());
		return $image;
	}
	
	/** @WebAction */
	public function setProfilePicture() {
		$photoId = $this->getString("photoid");
		if(empty($photoId)) {
			return;
		}
		$photo = EntityFactory::loadEntity(new Photo(), $this->getUser(), $photoId);
		Utils::assertNotNull("Geen foto met id ".$photoId, $photo);
		//DebugUtils::debug("updatein g phot for user: ".$this->getUser()->getString("username"));
		
//		$user = EntityFactory::loadEntity(new UserProfile(), $this->getUser(), $this->getUser()->getKey());
//		$user->put("photoid", $photoId);
//		$user->save();
		$this->getUser()->put("photoid", $photoId);
		$this->getUser()->save();
		
		header("Location: /?page=myphotos&action=overview");
	}
	
	/**
	 * @WebAction
	 * @Login
	 * @JSON
	 */
	public function deletephoto() {
		$systemid = $this->get("id");
		$ent = EntityFactory::loadEntity(new Photo, $this->getUser(), $systemid);
		Utils::assertTrue("Photo == null", $ent);
		MediaUtils::delete($ent);
	}
	
	//TODO: foutafhandeling
	private function deletePhotoFromDisc($url) {
		if(@!unlink($url)) {
			// TODO: loggen
			Logger::error("Foto wordt niet verwijderd", __FILE__, __LINE__);
		}
	}
	
	
	/**
	 * @WebAction
	 * @Login
	 * @JSON
	 */
	public function deleteAlbum() {
		$albumId= $this->getInt("id", -1);
		Utils::assertTrue("Invalid photoalbum", $albumId != -1);		
		$oq = ObjectQuery::buildACS(new Photo(), $this->getUser());
		$oq->addConstraint(Constraint::eq("albumid", $albumId));
		$list = SearchObject::search($oq);
		foreach($list as $photo) {
			if ($this->getUser()->getString("photoid") == $photo->getKey()) {
				$this->getUser()->put("photoid", NULL);
				$this->getUser()->save();
			}
			$photo->delete();
			$this->deletePhotoFromDisc(PHOTOS.$photo->getString("filename"));
		}
		EntityFactory::deleteEntity(new PhotoAlbum(), $this->getUser(), $albumId);
	}
}

?>
