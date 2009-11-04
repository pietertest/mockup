<?php
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'entities/photo/Photo.class.php';

class UserProfile extends DatabaseEntity {

    function __construct() {
    	parent::__construct("reflirt_nieuw", "users");
    }
    
	public function getPhotoUrl() {
    	$photoid = $this->getInt("photoid", -1);
    	if($photoid != -1) {
    		include_once PHP_CLASS.'entities/photo/Photo.class.php';
    		$photo = $this->loadEntityByForeignKey(new Photo(), "photoid");
    		if ($photo != null) {
    			$options = new MediaOptions();
    			$options->setSize(PhotoResizer::PROFILE);
    			return MediaServlet::getUrl($photo, $options);
    		}
    	}
    	
    	$sex = $this->getInt("sex", 0);
    	if ($sex == 0) {
    		return "/images/global/girl.gif";
    	}
    	return "/images/global/dude.gif";
    }
    
//    public static function _getPhotoUrl(User $user) {
//    	$photoid = $user->getString("photoid");
//    	Utils::assertNotNull("User == null!", $user);
//		if(!empty($photoid)){
//			$photo = EntityFactory::loadEntity(new Photo(), $user, $photoid);
//			return $photo->getString("filename");
//		}
//		return null;
//    }
}

?>