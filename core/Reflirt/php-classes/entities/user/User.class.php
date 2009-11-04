<?php
/* @version $Id: UserEntity.class.php,v 1.1 2007/03/04 16:46:19 pieter Exp $ */

include_once(PHP_CLASS.'entities/db/PreparedQuery.class.php');
include_once(PHP_CLASS.'entities/db/DatabaseEntity.class.php');
include_once(PHP_CLASS.'searchers/DefaultSearcher.class.php');
include_once(PHP_CLASS.'utils/Utils.class.php');
include_once(PHP_CLASS.'entities/spot/MySpot.class.php');

class User extends DatabaseEntity{
	
	public static $MAPPING = array(
		"systemid"	=> "uid",
		"username"	=> "username",
		"filename"	=> "filename",
		"lat"		=> "lat",
		"lng"		=> "lng"
	);
	
	function __construct() {
		parent::__construct("reflirt_nieuw", 'users');
	//	$this->setLoadSearcher(new UserLoadSearcher($this));
    }
    
    function profileIsEmpty() {
    	return $this->get("firstname") == null 
    			&& $this->get("lastname") == null
    			&& $this->get("") == null;
    }
    
    function getUrl() {
    	return "/?page=userprofile&action=view&id=" . $this->getKey();
    }
    
    public function getDefaultSearcher() {
    	return new DefaultUserSearcher();
    }
    
    public function getUsername() {
    	return $this->get("username");
    }
    
   	public function getCity() {
    	return $this->loadEntityByForeignKey(new City, "cityid");
   		//return $this->get("cicityname");
    }
    
    public function save () {
    	//$this->putCol("lastaction", DateUtils::now());
    	parent::save();
    	$_SESSION["user"] = serialize($this);
    }
    
    public function getHtmlRenderer() {
    	return new UserHtmlRenderer($this);
    }
    
    public function getSpots() {
   		$systemUser = UserFactory::getSystemUser();
   		$oq = ObjectQuery::build(new Spot(), $systemUser);
   		$oq->addConstraint(Constraint::eq("user", $this->getKey()));
//   		$oq->setSearcher(new DefaultMySpotSearcher());
   		$oq->setSearcher(new UserSpotSearcher());
		return SearchObject::search($oq);
    }
    
    public function getUser() {
    	return $this;
    }
    
    public function getEmailAddress() {
    	return $this->get("email");
    }
    
    public function isOnline() {
    	/*
    	$settingOnline = $this->getBoolean("online", false);
    	if(!$settingOnline) {
    		return;
    	}
    	*/
    	$date = $this->get("lastaction");
    	$lastAction = DateUtils::DateTime2Timestamp($this->get("lastaction"));
    	if ((mktime() - $lastAction) < Page::$SESSION_TIMEOUT) {
    		$lastLogout = DateUtils::DateTime2Timestamp($this->get("lastlogout"));
    		if( $lastLogout < $lastAction) {
    			return true;	
    		}
    	}
    	return false;
    }
    
    public function hasPhoto() {
		return $this->get("photoid") != null;
    }
    
    public function getPhoto() {
		return $this->loadEntityByForeignKey(new Photo(), "photoid");
    }
    
    public function getProfile() {
    	$profile = new UserProfile();
    	$profile->putAll($this);
    	return $profile;	
    }
    
    public function getPhotoUrl() {
    	$photoid = $this->getInt("photoid", -1);
    	$sex = $this->getInt("sex", 0);
    	if($photoid != -1) {
    		include_once PHP_CLASS.'entities/photo/Photo.class.php';
    		$photo = $this->getPhoto();
    		$path = $photo->getFile()->getPath();
	    	if(!file_exists($path)) {
				return $this->getNoPictureUrl($sex);
			}
    		if ($photo != null) {
    			$options = new MediaOptions();
    			$options->setSize(PhotoResizer::SMALL);
    			return MediaServlet::getUrl($photo, $options);
    		}
    	}
    	
    	return $this->getNoPictureUrl($sex);
    }
    
    private function getNoPictureUrl($sex) {
    	if ($sex == 0) {
    		return "/images/global/girl_small.gif";
    	}
    	return "/images/global/dude_small.gif";
    }
    
    public function hasLocation() {
    	$lat = $this->getString("lat");
    	return !empty($lat);
    }
    
}

class UserHtmlRenderer extends DefaultHTMLRenderer {
	
	public function get($what) {
		if($what == "profileurl") {
			return '/?page=userprofile&action=view&id='	. $this->ent->getKey();
		}
		return parent::get($what);
	}
}
class DefaultUserSearcher extends DefaultSearcher {
	
	public function getTables(DataSource $ds) {
		return " FROM users ".
				" LEFT JOIN city ".
				" ON city.systemid= users.cityid ";
	}
}

class UserSpotSearcher extends DefaultSearcher {
	public function getFields(DataSource $ds) {
		return "*, spot.systemid AS systemid";
	} 
	
	public function getTables(DataSource $ds) {
		return " FROM myspot ".
			" JOIN spot ".
			" ON myspot.spotid = spot.systemid" .
			" LEFT JOIN city " .
			" ON spot.cityid = city.systemid ";
	}
}

//class UserLoadSearcher extends LoadSearcher {
//	
//	public function getTables(DataSource $ds) {
//		$table = $this->entity->getTable()->getTableName();
//		$select = " FROM $table ".
//					" LEFT JOIN photo " .
//	    			" ON users.photoid = photo.systemid " .
//	    			" LEFT JOIN city ".
//					" ON city.systemid = users.cityid ".
//					" LEFT JOIN country ".
//					" ON country.systemid = city.cicountryid ";
//		return $select;
//	}
//}

?>