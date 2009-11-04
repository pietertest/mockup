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
include_once PHP_CLASS.'entities/message/Message.class.php';
include_once PHP_CLASS.'entities/mylocation/MyLocation.class.php';
include_once PHP_CLASS.'entities/spot/SpotUtils.class.php';
include_once PHP_CLASS.'entities/spot/MyNeighborhood.class.php';
include_once PHP_CLASS.'entities/user/UserUtils.class.php';
include_once PHP_CLASS.'entities/user/UserFavorite.class.php';

/**
 * @Login
 */
class UserprofilePage extends Page {
	
	private $profileUser;
	
	public function __construct(DataSource $ds) {
		parent::__construct($ds);
		$this->initProfileUser();
	}
	
	private static $IMAGE_MAX_SIZE = 10000; // 100 KB

	/**
	 * @WebAction
	 */
	public function overview() {}

	/**
	 * @WebAction
	 * @Ajax
	 */
	public function summary() {
		$userid= $this->getString("id");
		$user = UserFactory::getUserBySystemid($userid);
		
		$this->put("profileUser", $user);
		
		// Berichten
//		$oq = ObjectQuery::build(new Note(), $user);
//		$oq->setSearcher(Note::getDefaultSearcher());
//		$notes = SearchObject::search($oq);
//		$this->put("notes", $notes);
		
		// Spots
//		$oq = ObjectQuery::build(new MySpot(), $user);
//		$oq->setSearcher(new DefaultMySpotSearcher());
//		$this->put("spots", SearchObject::search($oq));
		
		// Fotos
		$oq = ObjectQuery::buildACS(new Photo(), $user);
		$this->put("photos", SearchObject::search($oq));
		
		// Buren
//		$zipcode = $user->getString("zipcode", null);
//		$zipcodematches = MyNeighborhood::getPeople($zipcode, $this->get("cityid"), 20);
//		$zipcodematchesJSON = MyNeighborhood::getInMijnPostcodeJSON($zipcodematches);
//		$this->put("zipcodematches", $zipcodematches);
//		$this->put("zipcodematchesJSON", $zipcodematchesJSON);
		
		
		// Overeenkomstige spots
//		if (isset($_SESSION["uid"])) {
//			$samespots = UserUtils::getSimilarSpots($this->getUser(), $this->profileUser);
//			$this->put("samespots", $samespots);
//		}
		
	}
	
	/** 
	 * @WebACtion
	 * @Login
	 */
	public function view() {
		$systemid = $this->get("id");
		$profileUser = UserFactory::getUserBySystemid($systemid);
		
		$oq = ObjectQuery::buildACS(new UserFavorite(), $this->getUser());
		$oq->addConstraint(Constraint::eq("otheruser", $profileUser->getKey()));
		$favorite = SearchObject::select($oq);
		
		$this->put("favorite", $favorite);
		
		$this->put("profileUser", $profileUser);
		
		
		$options = new MediaOptions();
		$options->setSize(PhotoResizer::PROFILE);
			$photo = $profileUser->getPhoto();
		if ($photo) {
			$url = MediaServlet::getUrl($photo, $options);
		} else {
			$sex = $profileUser->getInt("sex", 0);
    		if ($sex == 0) {
    			$url = "/images/global/girl.gif";
    		} else {
    			$url = "/images/global/dude.gif";
    		}	
		}
		$this->put("photoUrl", $url);
				
	}
	
	private function getCountMatches(MyLocationInterface $mylocation) {
		$pq = new PreparedQuery("reflirt_nieuw");
    	$pq->setQuery("SELECT count(*) AS aantal " .
				"FROM ".$mylocation->getTable()->getTablename().
				" WHERE user = " .$mylocation->getUser()->getKey().
				" AND ".$mylocation->getSpotColumn(). " = '".$mylocation->getString($mylocation->getSpotColumn())."'"
			);
		$rs = $pq->execute();
		return $rs["aantal"];
	}
	
	//TODO: verhuizen naar een nieuw aan ta maken page voor een progiel voor een
	//andere gebruiker
	/**
	 * @WebAction
	 * @JSON
	 */	
	public function sendmessage() {
		$userid = $this->getString("id");
		$message = $this->getString("message");
		$user = UserFactory::getUserBySystemid($userid);
		
		$note = new Message();
		$note->setUser($user);
		$note->put("subject", "(geen onderwerp)");
		$note->put("message", $message);
		$note->put("sender", $this->getUser()->getKey());

		$note->save();
		
		//header("Location: /?page=userprofile&action=view&user=".$user->getString("username"));
	}
	
	/**
	 * @WebAction
	 * @Ajax
	 */
	public function photos () {
		$systemid = $this->getInt("systemid", -1);
		$oq = ObjectQuery::buildACS(new Photo(), $this->getProfileUser());
		if($systemid) {
			$oq->addConstraint(Constraint::gt("systemid", $systemid));
		}
		$oq->setLimit(1);
		$photo = SearchObject::search($oq);
		$this->put("photo", $photo);
	}
	
	/**
	 * De user waarvan je de pagina bezoekt 
	 */
	private function initProfileUser() {
		$userid = $this->getString("id");
		if(isset($userid)) {
			$user = UserFactory::getUserBySystemid($userid);
			Utils::assertTrue("user == null!", $user!=null && $user->getKey() != -1);
			$this->profileUser = $user;
		}
		$this->put("profileuser", $this->profileUser);
	}
	
	private function getProfileUser() {
		return $this->profileUser;
	}
	
	/**
	 * Toevoegen aan favorieten
	 * 
	 * @WebAction
	 * @Login
	 * @JSON
	 */
	public function remember() {
		$systemid = $this->get("id");
		$otherUser = UserFactory::getUserBySystemid($systemid);
		
		$fav = new UserFavorite();
		$fav->setUser($this->getUser());
		$fav->put("otheruser", $otherUser->getKey());
		$fav->save();
	}
	
	/**
	 * Vraag of de gebruiker zijn profiel wil aanvullen
	 * 
	 * @WebAction
	 * @Login
	 * @JSON
	 */
	public function fillprofilerequest() {}

}

?>
