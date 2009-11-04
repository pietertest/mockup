<?php
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/user/UserProfile.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'entities/location/City.class.php';
include_once PHP_CLASS.'entities/location/Country.class.php';
include_once PHP_CLASS.'entities/oproep/OproepUtils.class.php';
include_once PHP_CLASS.'entities/photo/Photo.class.php';
include_once PHP_CLASS.'image/UploadedImage.class.php';
include_once PHP_CLASS.'entities/note/Note.class.php';
include_once PHP_CLASS.'entities/mylocation/MyLocation.class.php';
include_once PHP_CLASS.'exception/ValidationException.class.php';

/**
 * @Login
 */
class MyprofilePage extends Page {
	
	private $FEEDBACK_SUCCESSFULLY_SAVED = 1;
	private static $IMAGE_MAX_SIZE = 10000; // 100 KB
	
	public function initFeedbacks() {
		$this->addFeedback(new Feedback(
			$this->FEEDBACK_SUCCESSFULLY_SAVED, Feedback::$TYPE_SUCCESS, "successfullysaved"));
	}
	
	/**
	 * @WebAction
	 */
	public function overview() {
		$systemUser = UserFactory::getSystemUser();
		$oq = ObjectQuery::build(new UserProfile(), $systemUser);
		$oq->setSearcher(new DefaultUserSearcher());
		$oq->addConstraint(Constraint::eq("users.systemid", $this->getUser()->getKey()));
		$profile = SearchObject::select($oq);
		
		$this->putAll($profile);
		
		//$cityid = $this->user->getString("cityid");
		
		$photoid = $profile->getInt("photoid", -1);
		if ($photoid) {
			$photo = EntityFactory::loadEntity(new Photo, $this->getUser(), $photoid);
			if ($photo) {
				$this->put("profilePictureUrl", $photo->getUrl());
			}
		}
		$this->put("country", "1");
		$this->loadData();
		
		
//		if(empty($cityid)) {
//			return;
//		}
//		$oCity = EntityFactory::loadEntity(new City(), UserFactory::getSystemUser(),$cityid);
//		$this->put("cityfield", $oCity->getString("cityname")." (".$oCity->getString("country").")");
	}
	
	function loadData() {
		$this->put("options_sex", Utils::getArrayForSex());
		$this->put("countries", Country::createCountryPulldownArray());
		
		$this->getSpot("companyid", "company");
		$this->getSpot("schoolid", "school");
	}
	
	private function getSpot($foreignkey, $fieldname) {
		$systemUser = UserFactory::getSystemUser();
		$systemid = $this->user->getString($foreignkey);
		if (Utils::isEmpty($systemid)) {
			return;
		}
		$rs = EntityFactory::loadEntity(new Spot, $systemUser, $systemid);
		$this->put($fieldname, $rs->get("name"));
	} 
	
	/**
	 * @WebAction
	 * @Login
	 * @JSON
	 */
	public function save() {
		$this->setTemplate("overview"); // Doe het hier zodat als er een fout optreed hij wel dit template pakt
		
//		$user = new UserProfile();
		$user = new User();
		$user->setKey($this->getUser()->getKey());
		$user->setUser($this->getUser());
		$user->putAll($this);
		
		$password = $this->get("password");
		if(!empty($password)) {
			Utils::validateTrue("De wachtwoorden dienen gelijk te zijn",
				$password == $this->get("password2"), "password");
			$user->put("password", md5($password));
		} else {
			$user->remove("password");
		}
		
		$day = $this->get("Date_Day", "");
		$month = $this->get("Date_Month", "");
		$year = $this->get("Date_Year", "");
		
		if(empty($day) || empty($month) || empty($year)) {
			$user->put("birthdate", null);
		} else {
			$date = $this->get("Date_Day") . "-" . $this->get("Date_Month") . "-" . $this->get("Date_Year");
			$user->put("birthdate", $date);
		}
		try {
			$user->save();
		}catch (DuplicateException $e) {
			$field = $e->getField();
			$value = $e->getValue();
			if($field == "email") {
				throw new ValidationException("Het emailadres '$value' komt al voor in het systeem. Kies een ander emailadres.", "email");
			} else if($field == "username") {
				throw new ValidationException("De gebruikernaam '$value' is al in gebruik", "email"); 	
			} else {
				throw new ValidationException("Het veld '$value' is al in gebruik", "email");
			}
		}
		
		// De user in de sessie updaten
		//$_SESSION['user'] = serialize($user);
		
		//$this->overview();
		
		//$this->forward("overview",null,$this->FEEDBACK_SUCCESSFULLY_SAVED);
		//header("Location: /?page=myprofile&action=overview");
	}
	
	/**
	 * @WebAction
	 * @Login
	 * @JSON
	 */
	public function uploadphoto() {
		if (!isset($_FILES["profilepicture"])) {
			throw new UserFriendlyMessageException("Oeps, er is iets misgegaan bij het uploaden van je profielphoto. Probeer het nogmaals.");
		}
		$picture = $_FILES["profilepicture"];
		
		$this->deletePicture();
				
		$ui = new UploadedImage($picture);
		if(!$ui->accepted()) {
			throw new UserFriendlyMessageException("Dit type is niet toegestaan: " . $ui->getExtension());
		}
		
		$photo = new Photo();
		$photo->setUser($this->getUser());
		$photo->setImage($ui);
		$photo->save();
		
		$this->getUser()->put("photoid", $photo->getKey());
		$this->getUser()->save();
		
		$json = array();
		
		include_once SERVLETS_DIR.'media/MediaServlet.class.php';
		$json["photoUrl"] = MediaServlet::getUrl($photo);
		return $json;
	}
	
	/**
	 * @WebAction
	 * @Login
	 * @JSON
	 */
	public function deletephoto() {
		$this->getUser()->put("photoid", NULL);
		$this->getUser()->save();
		$json["photoUrl"] = $this->getUser()->getProfile()->getPhotoUrl();
		return $json;	
	}
	

	private function deletePicture() {
		$oq = ObjectQuery::buildACS(new Photo(), $this->getUser());
		$photo = SearchObject::select($oq);
		if($photo) {
			// Bestaande photo verwijderen
			@unlink($photo->getPath());
			$photo->delete();
		}
	}

}

?>
